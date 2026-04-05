<?php

namespace App\Http\Controllers;

use App\CustomerGroup;
use App\SellingPriceGroup;
use App\Utils\Util;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\BusinessUtil;

class CustomerGroupController extends Controller
{

    protected $businessUtil;


    /**
     * ConstructorZ
     *
     * @param Util $commonUtil
     * @return void
     */
    public function __construct(Util $commonUtil, BusinessUtil $businessUtil,
    )
    {
        $this->commonUtil = $commonUtil;
        $this->businessUtil = $businessUtil;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('customer.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $customer_group = CustomerGroup::where('customer_groups.business_id', $business_id)
                ->leftjoin('selling_price_groups as spg', 'spg.id', '=', 'customer_groups.selling_price_group_id')
                ->select(['customer_groups.name', 'customer_groups.amount', 'spg.name as selling_price_group', 'customer_groups.id', 'price_calculation_type']);

            return Datatables::of($customer_group)
                ->addColumn(
                    'action',
                    '@can("customer.update")
                            <button data-href="{{action(\'App\Http\Controllers\CustomerGroupController@edit\', [$id])}}" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary tw-m-0.5 edit_customer_group_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                        &nbsp;
                        @endcan

                        @can("customer.delete")
                            <button data-href="{{action(\'App\Http\Controllers\CustomerGroupController@destroy\', [$id])}}" class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-xs tw-dw-btn-error tw-m-0.5 delete_customer_group_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                        @endcan'
                )
                ->editColumn('selling_price_group', '@if($price_calculation_type=="selling_price_group") {{$selling_price_group}} @else -- @endif ')
                ->editColumn('amount', '@if($price_calculation_type=="percentage") {{$amount}} @else -- @endif ')
                ->removeColumn('id')
                ->removeColumn('price_calculation_type')
                ->rawColumns([3])
                ->make(false);
        }

        return view('customer_group.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('customer.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $price_groups = SellingPriceGroup::forDropdown($business_id, false);

        $business_details = $this->businessUtil->getDetails($business_id);

        // Remove reference to pos_settings and retrieve is_pharmacy from the correct source
        $is_pharmacy = $business_details->is_pharmacy ?? 0;

        return view('customer_group.create')->with(compact('price_groups', 'is_pharmacy'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('customer.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['name', 'amount', 'price_calculation_type', 'selling_price_group_id', 'tin']);
            $input['business_id'] = $request->session()->get('user.business_id');
            $input['created_by'] = $request->session()->get('user.id');
            $input['amount'] = !empty($input['amount']) ? $this->commonUtil->num_uf($input['amount']) : 0;

            $customer_group = CustomerGroup::create($input);
            $output = ['success' => true,
                'data' => $customer_group,
                'msg' => __('lang_v1.success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\CustomerGroup $customerGroup
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('customer.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $customer_group = CustomerGroup::where('business_id', $business_id)->find($id);

            $price_groups = SellingPriceGroup::forDropdown($business_id, false);

            // Fetch is_pharmacy directly from the business details
            $business_details = $this->businessUtil->getDetails($business_id);
            $is_pharmacy = $business_details->is_pharmacy ?? 0;

            return view('customer_group.edit')
                ->with(compact('customer_group', 'price_groups', 'is_pharmacy'));
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('customer.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['name', 'amount', 'price_calculation_type', 'tin', 'selling_price_group_id']);
                $business_id = $request->session()->get('user.business_id');

                $input['amount'] = !empty($input['amount']) ? $this->commonUtil->num_uf($input['amount']) : 0;

                $customer_group = CustomerGroup::where('business_id', $business_id)->findOrFail($id);

                $customer_group->update($input);

                $output = ['success' => true,
                    'msg' => __('lang_v1.success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('customer.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;

                $cg = CustomerGroup::where('business_id', $business_id)->findOrFail($id);
                $cg->delete();

                $output = ['success' => true,
                    'msg' => __('lang_v1.success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }
}
