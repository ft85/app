<?php

namespace App\Http\Controllers\Restaurant;

use App\BusinessLocation;
use App\Restaurant\ResTable;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (! auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $tables = ResTable::where('res_tables.business_id', $business_id)
                        ->join('business_locations AS BL', 'res_tables.location_id', '=', 'BL.id')
                        ->select(['res_tables.name as name', 'BL.name as location',
                            'res_tables.description', 'res_tables.id', ]);

            return Datatables::of($tables)
                ->addColumn(
                    'action',
                    '@role("Admin#'.$business_id.'")
                    <button data-href="{{action(\'App\Http\Controllers\Restaurant\TableController@edit\', [$id])}}" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary edit_table_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                        &nbsp;
                    @endrole
                    @role("Admin#'.$business_id.'")
                        <button data-href="{{action(\'App\Http\Controllers\Restaurant\TableController@destroy\', [$id])}}" class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-xs tw-dw-btn-error delete_table_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                    @endrole'
                )
                ->removeColumn('id')
                ->escapeColumns(['action'])
                ->make(true);
        }

        return view('restaurant.table.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (! auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id);

        return view('restaurant.table.create')
            ->with(compact('business_locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['name', 'description', 'location_id']);
            $business_id = $request->session()->get('user.business_id');
            $input['business_id'] = $business_id;
            $input['created_by'] = $request->session()->get('user.id');

            $table = ResTable::create($input);
            $output = ['success' => true,
                'data' => $table,
                'msg' => __('lang_v1.added_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    /**
     * Show the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        if (! auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        return view('restaurant.table.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit($id)
    {
        if (! auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $table = ResTable::where('business_id', $business_id)->find($id);

            return view('restaurant.table.edit')
                ->with(compact('table'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['name', 'description']);
                $business_id = $request->session()->get('user.business_id');

                $table = ResTable::where('business_id', $business_id)->findOrFail($id);
                $table->name = $input['name'];
                $table->description = $input['description'];
                $table->save();

                $output = ['success' => true,
                    'msg' => __('lang_v1.updated_success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy($id)
    {
        if (! auth()->user()->can('access_tables')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;

                $table = ResTable::where('business_id', $business_id)->findOrFail($id);
                $table->delete();

                $output = ['success' => true,
                    'msg' => __('lang_v1.deleted_success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    /**
     * Returns tables categorized by availability for the selected waiter (table dashboard modal)
     */
    public function getTableStatus(Request $request)
    {
        $business_id = $request->session()->get('user.business_id');
        $location_id = $request->get('location_id');
        $selected_waiter_id = $request->get('selected_waiter_id');

        if (!$location_id) {
            return response()->json(['success' => false, 'msg' => 'Location ID is required'], 400);
        }

        $location = \App\BusinessLocation::find($location_id);

        $tables = ResTable::where('business_id', $business_id)
            ->where('location_id', $location_id)
            ->with(['activeTransaction', 'activeTransaction.service_staff'])
            ->orderBy('name')
            ->get();

        $table_data = [];
        $summary = ['total' => 0, 'available' => 0, 'occupied' => 0];

        foreach ($tables as $table) {
            $summary['total']++;
            $data = ['id' => $table->id, 'name' => $table->name, 'is_disabled' => false, 'transaction_id' => null];

            // Table is occupied as long as is_table_open = 1 (explicit close required)
            if ($table->is_table_open) {
                $transaction = $table->activeTransaction;
                $waiter      = $transaction ? $transaction->service_staff : null;
                $data['status']            = 'occupied';
                $data['waiter_name']       = $waiter ? ($waiter->first_name . ' ' . ($waiter->last_name ?? '')) : 'Unknown';
                $data['assigned_waiter_id']= $transaction ? $transaction->res_waiter_id : null;
                $data['time_elapsed']      = $transaction ? $this->getTimeElapsed($transaction->transaction_date) : '';
                $data['transaction_id']    = $transaction ? $transaction->id : null;
                $data['is_paid']           = $transaction && $transaction->status === 'final';
                $summary['occupied']++;
            } else {
                $data['status'] = 'available';
                $summary['available']++;
            }

            $table_data[] = $data;
        }

        return response()->json([
            'success' => true,
            'tables' => $table_data,
            'summary' => $summary,
            'location_name' => $location->name ?? '',
        ]);
    }

    private function getTimeElapsed($transaction_date)
    {
        $start = \Carbon\Carbon::parse($transaction_date);
        $diff  = $start->diff(\Carbon\Carbon::now());
        return $diff->h > 0 ? $diff->h . 'h ' . $diff->i . 'm' : $diff->i . ' min';
    }

    /**
     * Load items of an existing draft table order into the POS cart
     */
    public function loadTableOrder(Request $request, $transaction_id)
    {
        $business_id = $request->session()->get('user.business_id');

        $transaction = \App\Transaction::where('id', $transaction_id)
            ->where('business_id', $business_id)
            ->where('status', 'draft')
            ->with('sell_lines.product')
            ->first();

        if (!$transaction) {
            return response()->json(['success' => false, 'msg' => 'Transaction not found or already completed']);
        }

        $items = [];
        foreach ($transaction->sell_lines as $line) {
            if (!empty($line->parent_sell_line_id)) continue;
            $items[] = [
                'product_id'               => $line->product_id,
                'product_name'             => $line->product->name ?? 'Unknown',
                'variation_id'             => $line->variation_id,
                'quantity'                 => $line->quantity,
                'unit_price'               => $line->unit_price_inc_tax,
                'subtotal'                 => $line->quantity * $line->unit_price_inc_tax,
                'line_discount_amount'     => $line->line_discount_amount ?? 0,
                'item_tax'                 => $line->item_tax ?? 0,
                'transaction_sell_lines_id'=> $line->id,
                'sell_line_note'           => $line->sell_line_note ?? '',
                'line_discount_type'       => $line->line_discount_type ?? 'fixed',
                'tax_id'                   => $line->tax_id ?? null,
            ];
        }

        return response()->json([
            'success'        => true,
            'items'          => $items,
            'transaction_id' => $transaction->id,
            'total'          => $transaction->final_total ?? 0,
        ]);
    }

    /**
     * Explicitly close/release a table (called by staff when customers leave)
     */
    public function closeTable(Request $request)
    {
        try {
            $business_id = $request->session()->get('user.business_id');
            $table_id    = $request->input('table_id');

            $table = ResTable::where('id', $table_id)
                ->where('business_id', $business_id)
                ->first();

            if (!$table) {
                return response()->json(['success' => false, 'msg' => 'Table not found'], 404);
            }

            $table->update(['is_table_open' => 0, 'assigned_waiter_id' => null]);

            return response()->json(['success' => true, 'msg' => 'Table closed successfully']);

        } catch (\Exception $e) {
            \Log::error('closeTable error: ' . $e->getMessage());
            return response()->json(['success' => false, 'msg' => 'Error closing table'], 500);
        }
    }

    /**
     * Transfer a table from one waiter to another
     */
    public function transferTable(Request $request)
    {
        try {
            $business_id    = $request->session()->get('user.business_id');
            $table_id       = $request->input('table_id');
            $transaction_id = $request->input('transaction_id');
            $from_waiter_id = $request->input('from_waiter_id');
            $to_waiter_id   = $request->input('to_waiter_id');

            if (!$table_id || !$transaction_id || !$from_waiter_id || !$to_waiter_id) {
                return response()->json(['success' => false, 'msg' => 'Missing required parameters'], 400);
            }

            $transaction = \App\Transaction::where('id', $transaction_id)
                ->where('business_id', $business_id)
                ->where('res_table_id', $table_id)
                ->where('res_waiter_id', $from_waiter_id)
                ->where('status', 'draft')
                ->first();

            if (!$transaction) {
                return response()->json(['success' => false, 'msg' => 'Transaction not found or not authorized'], 403);
            }

            $to_waiter = \App\User::where('id', $to_waiter_id)->where('business_id', $business_id)->first();
            if (!$to_waiter) {
                return response()->json(['success' => false, 'msg' => 'Target service staff not found'], 404);
            }

            \DB::beginTransaction();
            $transaction->update(['res_waiter_id' => $to_waiter_id]);
            \App\TransactionSellLine::where('transaction_id', $transaction_id)
                ->where(function ($q) {
                    $q->where('res_line_order_status', '!=', 'served')->orWhereNull('res_line_order_status');
                })
                ->update(['res_service_staff_id' => $to_waiter_id]);
            ResTable::where('id', $table_id)->update(['assigned_waiter_id' => $to_waiter_id]);
            \DB::commit();

            return response()->json(['success' => true, 'msg' => 'Table transferred successfully']);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('transferTable error: ' . $e->getMessage());
            return response()->json(['success' => false, 'msg' => 'Error transferring table'], 500);
        }
    }
}
