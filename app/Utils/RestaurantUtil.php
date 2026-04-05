<?php

namespace App\Utils;

use App\Printer;
use App\Restaurant\Booking;
use App\Transaction;
use App\TransactionSellLine;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RestaurantUtil extends Util
{
    /**
     * Retrieves all orders/sales
     *
     * @param  int  $business_id
     * @param  array  $filter
     * *For new orders order_status is 'received'
     * @return obj $orders
     */
    public function getAllOrders($business_id, $filter = [])
    {
        $query = Transaction::leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
            ->leftjoin('business_locations AS bl', 'transactions.location_id', '=', 'bl.id')
            ->leftjoin('res_tables AS rt', 'transactions.res_table_id', '=', 'rt.id')
            ->leftJoin('users AS staff', 'transactions.res_waiter_id', '=', 'staff.id') // Join the users table for service staff
            ->where('transactions.business_id', $business_id)
            ->where('transactions.type', 'sell')
            ->where('transactions.status', 'draft')
            ->whereDate('transactions.created_at', Carbon::today()); // Filter by current date

        // Exclude orders that are served, unless otherwise specified
        if (empty($filter['order_status'])) {
            $query->where(function ($q) {
                $q->where('res_order_status', '!=', 'served')
                    ->orWhereNull('res_order_status');
            });
        }

        // Handle 'received' order status filter
        if (!empty($filter['order_status']) && $filter['order_status'] == 'received') {
            $query->whereNull('res_order_status');
        }

        // Handle line order statuses like 'received', 'cooked', 'served'
        if (!empty($filter['line_order_status'])) {
            if ($filter['line_order_status'] == 'received') {
                $query->whereHas('sell_lines', function ($q) {
                    $q->whereNull('res_line_order_status')
                        ->orWhere('res_line_order_status', 'received');
                }, '>=', 1);
            }

            if ($filter['line_order_status'] == 'cooked') {
                $query->whereHas('sell_lines', function ($q) {
                    $q->where('res_line_order_status', '!=', 'cooked');
                }, '=', 0);
            }

            if ($filter['line_order_status'] == 'served') {
                $query->whereHas('sell_lines', function ($q) {
                    $q->where('res_line_order_status', '!=', 'served');
                }, '=', 0);
            }
        }

        // Primary filter: printer_id through category association
        if (!empty($filter['printer_id'])) {
            $query->whereHas('sell_lines.variations.product.category', callback: function ($q) use ($filter) {
                $q->where('categories.printer_id', $filter['printer_id']);
            });
        }

        // For kitchen orders
        if (!empty($filter['is_kitchen_order']) && $filter['is_kitchen_order'] == 1) {
            $query->where('is_kitchen_order', 1);
        }

        // Limit to permitted locations
        $permitted_locations = auth()->user()->permitted_locations();
        if ($permitted_locations != 'all') {
            $query->whereIn('transactions.location_id', $permitted_locations);
        }

        // Fetch and return the orders with sell_lines and service staff
        $orders = $query->select(
            'transactions.*',
            'contacts.name as customer_name',
            'bl.name as business_location',
            'rt.name as table_name',
            'staff.first_name as service_staff_first_name', // Select service staff name
            'staff.last_name as service_staff_last_name'
        )
            ->with(['sell_lines', 'service_staff']) // Eager load service staff relationship
            ->orderBy('created_at', 'desc')
            ->get();

        return $orders;
    }


    public function service_staff_dropdown($business_id)
    {
        //Get all service staff roles
        $service_staff_roles = Role::where('business_id', $business_id)
            ->where('is_service_staff', 1)
            ->get()
            ->pluck('name')
            ->toArray();

        $service_staff = [];

        //Get all users of service staff roles
        if (! empty($service_staff_roles)) {
            $service_staff = User::where('business_id', $business_id)->role($service_staff_roles)->get()->pluck('first_name', 'id');
        }

        return $service_staff;
    }

    public function is_service_staff($user_id)
    {
        $is_service_staff = false;
        $user = User::find($user_id);
        if ($user->roles->first()->is_service_staff == 1) {
            $is_service_staff = true;
        }

        return $is_service_staff;
    }


    /**
     * Retrieves line orders/sales
     *
     * @param  int  $business_id
     * @param  array  $filter
     * *For new orders order_status is 'received'
     * @return obj $orders
     */

        public function getLineOrders($business_id, $filter = [])
        {
            try {
                $query = TransactionSellLine::with(['modifiers', 'modifiers.product', 'modifiers.variations'])
                    ->leftJoin('transactions as t', 't.id', '=', 'transaction_sell_lines.transaction_id')
                    ->leftJoin('contacts as c', 't.contact_id', '=', 'c.id')
                    ->leftJoin('variations as v', 'transaction_sell_lines.variation_id', '=', 'v.id')
                    ->leftJoin('products as p', 'v.product_id', '=', 'p.id')
                    ->leftJoin('categories as cat', 'p.category_id', '=', 'cat.id')
                    ->leftJoin('units as u', 'p.unit_id', '=', 'u.id')
                    ->leftJoin('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
                    ->leftJoin('users as line_service_staff', 'transaction_sell_lines.res_service_staff_id', '=', 'line_service_staff.id')
                    ->leftJoin('business_locations as bl', 't.location_id', '=', 'bl.id')
                    ->leftJoin('res_tables as rt', 't.res_table_id', '=', 'rt.id')
                    ->where('t.business_id', $business_id)
                    // ->where('t.type', 'sell')
                    // ->where('t.status', 'draft')
                    ->whereDate('t.created_at', Carbon::today());

                // Apply filters
                if (!empty($filter['printer_id'])) {
                    $query->where('cat.printer_id', $filter['printer_id']);
                }

                if (empty($filter['order_status'])) {
                    $query->where(function ($q) {
                        $q->where('res_line_order_status', '!=', 'served')
                            ->orWhereNull('res_line_order_status');
                    });
                }

                if (!empty($filter['line_id'])) {
                    $query->where('transaction_sell_lines.id', $filter['line_id']);
                }

                $permitted_locations = auth()->user()->permitted_locations();
                if ($permitted_locations != 'all') {
                    $query->whereIn('t.location_id', $permitted_locations);
                }

                // Select only main products (not modifiers)
                $query->whereNull('transaction_sell_lines.parent_sell_line_id');

                // Select specific columns
                $orders = $query->select(
                    'p.name as product_name',
                    'p.type as product_type',
                    'v.name as variation_name',
                    'pv.name as product_variation_name',
                    't.id as transaction_id',
                    'c.name as customer_name',
                    'bl.name as business_location',
                    'rt.name as table_name',
                    't.created_at',
                    't.invoice_no',
                    'transaction_sell_lines.quantity',
                    'transaction_sell_lines.sell_line_note',
                    'transaction_sell_lines.res_line_order_status',
                    'transaction_sell_lines.is_printed',
                    'u.short_name as unit',
                    'transaction_sell_lines.id',
                    'cat.name as category_name',
                    'cat.printer_id',
                    DB::raw("CONCAT(COALESCE(line_service_staff.surname, ''),' ',COALESCE(line_service_staff.first_name, ''),' ',COALESCE(line_service_staff.last_name,'')) as service_staff_name"),
                    'line_service_staff.id as line_service_staff_id'
                )
                    ->orderBy('transaction_sell_lines.is_printed', 'asc')
                    ->orderBy('t.created_at', 'desc');

                $orders = $query->get();

                if ($orders->isEmpty()) {
                    return collect();
                }

                // Group orders by transaction_id and printer_id
                $groupedOrders = $orders->groupBy(['transaction_id', 'printer_id'])->map(function ($transactionGroup) {
                    return $transactionGroup->map(function ($printerGroup) {
                        $firstItem = $printerGroup->first();
                        return [
                            'transaction_id' => $firstItem->transaction_id,
                            'customer_name' => $firstItem->customer_name,
                            'business_location' => $firstItem->business_location,
                            'table_name' => $firstItem->table_name,
                            'created_at' => $firstItem->created_at,
                            'invoice_no' => $firstItem->invoice_no,
                            'printer_id' => $firstItem->printer_id,
                            'category_name' => $firstItem->category_name,
                            'line_service_staff' => [
                                'id' => $firstItem->line_service_staff_id,
                                'name' => $firstItem->service_staff_name
                            ],
                            'items' => $printerGroup->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'product_name' => $item->product_name,
                                    'product_type' => $item->product_type,
                                    'variation_name' => $item->variation_name,
                                    'product_variation_name' => $item->product_variation_name,
                                    'quantity' => $item->quantity,
                                    'sell_line_note' => $item->sell_line_note,
                                    'res_line_order_status' => $item->res_line_order_status,
                                    'unit' => $item->unit,
                                    'is_printed' => $item->is_printed,
                                    'service_staff_name' => $item->service_staff_name,
                                    'modifiers' => $item->modifiers->map(function ($modifier) {
                                        return [
                                            'name' => $modifier->product->name,
                                            'variation_name' => $modifier->variations->name ?? null,
                                            'quantity' => $modifier->quantity,
                                        ];
                                    })->toArray(),
                                ];
                            })->values()->all(),
                        ];
                    })->values();
                })->values();

                return $groupedOrders;
            } catch (\Exception $e) {
                \Log::error('Error in getLineOrders: ' . $e->getMessage());
                return ['error' => 'An error occurred while fetching orders: ' . $e->getMessage()];
            }
        }



    /**
     * Function to show booking events on a calendar
     *
     * @param  array  $filters
     * @return array
     */
    public function getBookingsForCalendar($filters)
    {
        $start_date = request()->start;
        $end_date = request()->end;
        $query = Booking::where('business_id', $filters['business_id'])
            ->whereBetween(DB::raw('date(booking_start)'), [$filters['start_date'], $filters['end_date']])
            ->with(['customer', 'table']);

        if (! empty($filters['user_id'])) {
            $query->where('created_by', $filters['user_id']);

            $query->where(function ($q) use ($filters) {
                $q->where('created_by', $filters['user_id'])
                    ->orWhere('correspondent_id', $filters['user_id'])
                    ->orWhere('waiter_id', $filters['user_id']);
            });
        }

        if (! empty($filters['location_id'])) {
            $query->where('bookings.location_id', $filters['location_id']);
        }
        $bookings = $query->get();

        $events = [];

        foreach ($bookings as $booking) {

            //Skip event if customer not found
            if (empty($booking->customer)) {
                continue;
            }

            $customer_name = $booking->customer->name;
            $table_name = $booking->table?->name;

            $backgroundColor = '#3c8dbc';
            $borderColor = '#3c8dbc';
            if ($booking->booking_status == 'completed') {
                $backgroundColor = '#00a65a';
                $borderColor = '#00a65a';
            } elseif ($booking->booking_status == 'cancelled') {
                $backgroundColor = '#f56954';
                $borderColor = '#f56954';
            } elseif ($booking->booking_status == 'waiting') {
                $backgroundColor = '#FFAD46';
                $borderColor = '#FFAD46';
            }
            if (! empty($filters['color'])) {
                $backgroundColor = $filters['color'];
                $borderColor = $filters['color'];
            }
            $title = $customer_name;
            if (! empty($table_name)) {
                $title .= ' - ' . $table_name;
            }
            $events[] = [
                'title' => $title,
                'title_html' => $customer_name . '<br>' . $table_name,
                'start' => $booking->booking_start,
                'end' => $booking->booking_end,
                'customer_name' => $customer_name,
                'table' => $table_name,
                'url' => action([\App\Http\Controllers\Restaurant\BookingController::class, 'show'], [$booking->id]),
                'event_url' => action([\App\Http\Controllers\Restaurant\BookingController::class, 'index']),
                // 'start_time' => $start_time,
                // 'end_time' =>  $end_time,
                'backgroundColor' => $backgroundColor,
                'borderColor' => $borderColor,
                'allDay' => false,
                'event_type' => 'bookings',
            ];
        }

        return $events;
    }

    public function getLineOrdersForPos($business_id, $filter = [])
    {
        try {
            $user_id = auth()->user()->id; // Get the logged-in user's ID

            $query = TransactionSellLine::with(['modifiers', 'modifiers.product', 'modifiers.variations'])
                ->leftJoin('transactions as t', 't.id', '=', 'transaction_sell_lines.transaction_id')
                ->leftJoin('contacts as c', 't.contact_id', '=', 'c.id')
                ->leftJoin('variations as v', 'transaction_sell_lines.variation_id', '=', 'v.id')
                ->leftJoin('products as p', 'v.product_id', '=', 'p.id')
                ->leftJoin('categories as cat', 'p.category_id', '=', 'cat.id')
                ->leftJoin('units as u', 'p.unit_id', '=', 'u.id')
                ->leftJoin('printers', 'cat.printer_id', '=', 'printers.id')
                ->leftJoin('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
                ->leftJoin('users as line_service_staff', 'transaction_sell_lines.res_service_staff_id', '=', 'line_service_staff.id')
                ->leftJoin('business_locations as bl', 't.location_id', '=', 'bl.id')
                ->leftJoin('res_tables as rt', 't.res_table_id', '=', 'rt.id')
                ->where('t.business_id', $business_id)
                ->where('t.created_by', $user_id)
//                ->where('t.type', 'sell')
//                ->where('t.status', 'draft')
                ->whereDate('t.created_at', Carbon::today());

            // Apply filters
            if (!empty($filter['printer_id'])) {
                $query->where('cat.printer_id', $filter['printer_id']);
            }

            if (empty($filter['order_status'])) {
                $query->where(function ($q) {
                    $q->where('res_line_order_status', '!=', 'served')
                        ->orWhereNull('res_line_order_status');
                });
            }

            if (!empty($filter['line_id'])) {
                $query->where('transaction_sell_lines.id', $filter['line_id']);
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);
            }

            // Select only main products (not modifiers)
            $query->whereNull('transaction_sell_lines.parent_sell_line_id');

            // Select specific columns
            $orders = $query->select(
                'p.name as product_name',
                'p.type as product_type',
                'v.name as variation_name',
                'pv.name as product_variation_name',
                't.id as transaction_id',
                'c.name as customer_name',
                'bl.name as business_location',
                'rt.name as table_name',
                't.created_at',
                't.invoice_no',
                'transaction_sell_lines.quantity',
                'transaction_sell_lines.sell_line_note',
                'transaction_sell_lines.res_line_order_status',
                'transaction_sell_lines.is_printed',
                'u.short_name as unit',
                'transaction_sell_lines.id',
                'cat.name as category_name',
                'printers.name as printer_name',
                'cat.printer_id',
                DB::raw("CONCAT(COALESCE(line_service_staff.surname, ''),' ',COALESCE(line_service_staff.first_name, ''),' ',COALESCE(line_service_staff.last_name,'')) as service_staff_name"),
                'line_service_staff.id as line_service_staff_id'
            )
                ->orderBy('transaction_sell_lines.is_printed', 'asc')
                ->orderBy('t.created_at', 'desc');

            $orders = $query->get();

            if ($orders->isEmpty()) {
                return collect();
            }

            // Group orders by transaction_id and printer_id
            $groupedOrders = $orders->groupBy(['transaction_id', 'printer_id'])->map(function ($transactionGroup) {
                return $transactionGroup->map(function ($printerGroup) {
                    $firstItem = $printerGroup->first();
                    return [
                        'transaction_id' => $firstItem->transaction_id,
                        'customer_name' => $firstItem->customer_name,
                        'business_location' => $firstItem->business_location,
                        'table_name' => $firstItem->table_name,
                        'created_at' => $firstItem->created_at,
                        'invoice_no' => $firstItem->invoice_no,
                        'printer_id' => $firstItem->printer_id,
                        'printer_name' => $firstItem->printer_name,
                        'category_name' => $firstItem->category_name,
                        'line_service_staff' => [
                            'id' => $firstItem->line_service_staff_id,
                            'name' => $firstItem->service_staff_name
                        ],
                        'items' => $printerGroup->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'product_name' => $item->product_name,
                                'product_type' => $item->product_type,
                                'variation_name' => $item->variation_name,
                                'product_variation_name' => $item->product_variation_name,
                                'quantity' => $item->quantity,
                                'sell_line_note' => $item->sell_line_note,
                                'res_line_order_status' => $item->res_line_order_status,
                                'unit' => $item->unit,
                                'printer_name' => $item->printer_name,
                                'is_printed' => $item->is_printed,
                                'service_staff_name' => $item->service_staff_name,
                                'modifiers' => $item->modifiers->map(function ($modifier) {
                                    return [
                                        'name' => $modifier->product->name,
                                        'variation_name' => $modifier->variations->name ?? null,
                                        'quantity' => $modifier->quantity,
                                    ];
                                })->toArray(),
                            ];
                        })->values()->all(),
                    ];
                })->values();
            })->values();

            return $groupedOrders;
        } catch (\Exception $e) {
            \Log::error('Error in getLineOrders: ' . $e->getMessage());
            return ['error' => 'An error occurred while fetching orders: ' . $e->getMessage()];
        }
    }

    public function shouldShowButton(): bool
    {
        $business_id = request()->session()->get('user.business_id');
        //Log::info("Business ID from session: " . $business_id);

        $business = DB::table('business')->where('id', $business_id)->first();

        if (!$business) {
           // Log::warning("No business found for ID: " . $business_id);
            return false;
        }

        //Log::info("Business found: ", ['id' => $business->id, 'enabled_module' => $business->enabled_modules]);

        if ($business->enabled_modules === null) {
           // Log::info("enabled_module is null for business");
            return false;
        }

        $enabled_modules = json_decode($business->enabled_modules, true);

        if (!is_array($enabled_modules)) {
           // Log::warning("enabled_module is not a valid JSON array", ['enabled_module' => $business->enabled_modules]);
            return false;
        }

       // Log::info("Enabled modules: ", $enabled_modules);

        $should_show = in_array('kitchen', $enabled_modules);
      //  Log::info("Should show button: " . ($should_show ? 'Yes' : 'No'));

        return $should_show;
    }
}
