<?php

namespace App\Http\Controllers\Restaurant;

use App\Printer;
use App\TransactionSellLine;
use App\User;
use App\Utils\RestaurantUtil;
use App\Utils\Util;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class OrderController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $commonUtil;

    protected $restUtil;

    /**
     * Constructor
     *
     * @param  Util  $commonUtil
     * @param  RestaurantUtil  $restUtil
     * @return void
     */
    public function __construct(Util $commonUtil, RestaurantUtil $restUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->restUtil = $restUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
    {
        $business_id = request()->session()->get('user.business_id');
        $user_id = request()->session()->get('user.id');

        $is_service_staff = false;
        $orders = [];
        $groupedOrders = [];
        $printers = [];

        // Fetch all orders without filtering by printer_id
        $orders = $this->restUtil->getAllOrders($business_id);

        // Check if a printer is selected
        if (!empty(request()->printer_id)) {
            $printer_id = request()->printer_id;
            // Get grouped line orders for the selected printer
            $groupedOrders = $this->restUtil->getLineOrders($business_id, ['printer_id' => $printer_id]);
        } else {
            // If no printer is selected, get all line orders
            $groupedOrders = $this->restUtil->getLineOrders($business_id);
        }

        // Fetch printers
        $printers = Printer::where('business_id', $business_id)->pluck('name', 'id');

        return view('restaurant.orders.index', compact('orders', 'is_service_staff', 'printers', 'groupedOrders'));
    }


    /**
     * Marks an order as served
     *
     * @return json $output
     */
    public function markAsServed($id)
    {
        // if (!auth()->user()->can('sell.update')) {
        //     abort(403, 'Unauthorized action.');
        // }
        try {
            $business_id = request()->session()->get('user.business_id');
            $user_id = request()->session()->get('user.id');

            $query = TransactionSellLine::leftJoin('transactions as t', 't.id', '=', 'transaction_sell_lines.transaction_id')
                ->where('t.business_id', $business_id)
                ->where('transaction_id', $id);

            if ($this->restUtil->is_service_staff($user_id)) {
                $query->where('res_waiter_id', $user_id);
            }

            $query->update(['res_line_order_status' => 'served']);

            $output = [
                'success' => 1,
                'msg' => trans('restaurant.order_successfully_marked_served'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => trans('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    /**
     * Marks an line order as served
     *
     * @return json $output
     */
    public function markLineOrderAsServed($ids)
    {
        try {
            $business_id = request()->session()->get('user.business_id');
            $user_id = request()->session()->get('user.id');

            $idsArray = explode(',', $ids);

            $query = TransactionSellLine::whereIn('id', $idsArray);

            if ($this->restUtil->is_service_staff($user_id)) {
                $query->where('res_service_staff_id', $user_id);
            }

            $sell_lines = $query->get();

            if ($sell_lines->count() > 0) {
                foreach ($sell_lines as $sell_line) {
                    $sell_line->res_line_order_status = 'served';
                    $sell_line->save();
                }

                $output = [
                    'success' => 1,
                    'msg' => trans('Order Marked As served Successfully!'),
                ];
            } else {
                $output = [
                    'success' => 0,
                    'msg' => trans('messages.something_went_wrong'),
                ];
            }
        } catch (\Exception $e) {
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => trans('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    public function printLineOrder(Request $request)
    {
        try {
            $business_id = $request->session()->get('user.business_id');
            $line_ids = explode(',', $request->ids);

            // Fetch the printer details
            $printer_id = $request->input('printer_id');
            $printer = Printer::find($printer_id);
            $printer_name = $printer ? $printer->name : 'Default Printer';

            // Fetch the line orders with related data
            $query = TransactionSellLine::whereIn('transaction_sell_lines.id', $line_ids)
                ->where('transaction_sell_lines.is_printed', false)
                ->with(['modifiers', 'modifiers.product', 'modifiers.variations', 'product', 'product.unit'])
                ->leftJoin('transactions as t', 't.id', '=', 'transaction_sell_lines.transaction_id')
                ->leftJoin('contacts as c', 't.contact_id', '=', 'c.id')
                ->leftJoin('res_tables as rt', 't.res_table_id', '=', 'rt.id')
                ->leftJoin('business_locations as bl', 't.location_id', '=', 'bl.id')
                ->leftJoin('users as u', 't.res_waiter_id', '=', 'u.id') // Join waiter from transactions
                ->select(
                    'transaction_sell_lines.*',
                    't.invoice_no',
                    't.created_at as transaction_date',
                    'c.name as customer_name',
                    'rt.name as table_name',
                    'bl.name as business_location',
                    'u.first_name as waiter_name' // Fetch waiter name from the transaction
                );

            $line_orders = $query->get();

            // Check if any orders were found
            if ($line_orders->isEmpty()) {
                throw new Exception(trans('restaurant.no_orders_found'));
            }

            // Group orders by transaction
            $grouped_orders = $line_orders->groupBy('transaction_id')->map(function ($group) {
                $first_item = $group->first();
                return [
                    'invoice_no' => $first_item->invoice_no,
                    'created_at' => $first_item->transaction_date,
                    'customer_name' => $first_item->customer_name ?? 'Walk-in Customer',
                    'table_name' => $first_item->table_name ?? 'N/A',
                    'business_location' => $first_item->business_location,
                    'line_service_staff' => [
                        'id' => $first_item->res_waiter_id ?? 'N/A',
                        'name' => $first_item->waiter_name ?? 'N/A', // Using waiter info
                    ],
                    'items' => $group->map(function ($item) {
                        return [
                            'product_name' => $item->product->name,
                            'product_type' => $item->product->type,
                            'quantity' => $item->quantity,
                            'unit' => $item->product->unit->short_name ?? '',
                            'sell_line_note' => $item->sell_line_note,
                            'res_line_order_status' => $item->res_line_order_status,
                            'service_staff_name' => $item->waiter_name ?? 'N/A', // Using waiter info in each item
                            'modifiers' => $item->modifiers->map(function ($modifier) {
                                return [
                                    'name' => $modifier->product->name ?? null,
                                    'variation_name' => $modifier->variations->name ?? null,
                                    'quantity' => $modifier->quantity,
                                ];
                            })->toArray(),
                        ];
                    })->toArray(),
                ];
            })->values();

            // Initialize the HTML content
            $html_content = '';
            // dd($grouped_orders);
            // Loop through each grouped order and render the corresponding HTML
            foreach ($grouped_orders as $order) {
                // Render the view for each order and concatenate it to the HTML content
                $html_content .= view('restaurant.partials.print_line_order', ['order' => $order, 'printer_name' => $printer_name])->render();
            }

            // Update the is_printed field for the selected order lines
            TransactionSellLine::whereIn('id', $line_ids)->update(['is_printed' => true]);

            // Prepare the output response
            $output = [
                'success' => 1,
                'msg' => trans('lang_v1.success'),
                'html_content' => $html_content,
            ];
        } catch (Exception $e) {
            \Log::emergency('File:' . $e->getFile() . ' Line:' . $e->getLine() . ' Message:' . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => trans('messages.something_went_wrong') . ': ' . $e->getMessage(),
            ];
        }

        // Return the output
        return $output;
    }
}
