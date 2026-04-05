<?php

namespace Modules\Hms\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Transaction;
use Illuminate\Support\Carbon;
use Modules\Hms\Entities\HmsRoom;
use Modules\Hms\Entities\HmsRoomType;
use Modules\Hms\Entities\HmsBookingLine;
use App\Charts\CommonChart;
use App\Utils\ModuleUtil;
use Modules\Hms\Entities\HmsTransactionClass;


class RRAController extends Controller
{
    protected $moduleUtil;

    public function __construct(ModuleUtil $moduleUtil)
    {
        $this->moduleUtil = $moduleUtil;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $business_id = request()->session()->get('user.business_id');
        
       

        return view('rra::index', compact('business_id'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('rra::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('hms::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('hms::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function room_count($status){
        $today = Carbon::now();
        $business_id = request()->session()->get('user.business_id');

        return Transaction::join('hms_booking_lines', 'transactions.id', '=', 'hms_booking_lines.transaction_id')
            ->where('transactions.type', 'hms_booking')
            ->where('transactions.business_id', $business_id)
            ->whereDate('transactions.hms_booking_arrival_date_time', '<=', $today)
            ->whereDate('transactions.hms_booking_departure_date_time', '>=', $today)
            ->where('status', $status)
            ->count('hms_booking_lines.hms_room_id');
    }

    public function leave_arrive_count_today($type){
            $today = Carbon::now();
            $business_id = request()->session()->get('user.business_id');

            return HmsBookingLine::join('transactions', 'hms_booking_lines.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'confirmed')
            ->where('transactions.business_id', $business_id)
            ->where('transactions.type', 'hms_booking')
            ->whereDate('transactions.'.$type.'', '=', $today)
            ->selectRaw('SUM(hms_booking_lines.adults) as adult_guests, SUM(hms_booking_lines.childrens) as child_guests')
            ->get();
    }

    public function get_today_arrival_departure_booking($type){
            $today = Carbon::now();
            $business_id = request()->session()->get('user.business_id');

            return HmsTransactionClass::where('transactions.business_id', $business_id)
            ->where('transactions.status', 'confirmed')
            ->with(['contact', 'hms_booking_lines'])
            ->whereDate('transactions.'.$type.'', '=', $today)
            ->where('transactions.type', 'hms_booking')
            ->get();
    }

    private function __chartOptions($title)
    {
        return [
            'yAxis' => [
                'title' => [
                    'text' => $title,
                ],
            ],
            'legend' => [
                'align' => 'right',
                'verticalAlign' => 'top',
                'floating' => true,
                'layout' => 'vertical',
                'padding' => 20,
            ],
        ];
    }

    public function get_booking_count($date){
        $business_id = request()->session()->get('user.business_id');

        return Transaction::where('transactions.type', 'hms_booking')
            ->where('transactions.business_id', $business_id)
            ->where('status', 'confirmed')
            ->whereDate('transactions.hms_booking_arrival_date_time', '<=', $date)
            ->whereDate('transactions.hms_booking_departure_date_time', '>=', $date)
            ->count();
    }

}
