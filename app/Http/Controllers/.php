<?php

namespace App\Http\Controllers;

use App\AccountTransaction;
use App\Business;
use App\BusinessLocation;
use App\Contact;
use App\CustomerGroup;
use App\Product;
use App\PurchaseLine;
use App\TaxRate;
use App\Transaction;
use App\User;
use App\Utils\BusinessUtil;
use App\Utils\ModuleUtil;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Variation;
use App\RRAimports;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

use App\ProductVariation;
use App\Events\PurchaseCreatedOrModified;

class RRAController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $productUtil;

    protected $transactionUtil;

    protected $moduleUtil;

    /**
     * Constructor
     *
     * @param  ProductUtils  $product
     * @return void
     */
    public function __construct(ProductUtil $productUtil, TransactionUtil $transactionUtil, BusinessUtil $businessUtil, ModuleUtil $moduleUtil)
    {
        $this->productUtil = $productUtil;
        $this->transactionUtil = $transactionUtil;
        $this->businessUtil = $businessUtil;
        $this->moduleUtil = $moduleUtil;

        $this->dummyPaymentLine = ['method' => 'cash', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '',
            'is_return' => 0, 'transaction_no' => '', ];
    }

  


    public function index()
    {
       // $business_id = $request->session()->get('user.business_id');


        //$inventoryItems=Product::where('business_id', $business_id)->get();

        return view('rra.index');
    }


    public function getData(Request $request)
{
   
    $business_id = $request->session()->get('user.business_id');

    $imports = RRAimports::where('business_id', $business_id)
    ->select(['id', 'item_nm', 'qty', 'hs_cd', 'task_cd', 'pkg_qty', 'dcl_no', 'invc_fcur_amt', 'invc_fcur_cd', 'dcl_de', 'created_at']);


    
    //$imports = RRAimports::select(['id', 'item_nm', 'qty','hs_cd','task_cd','pkg_qty','dcl_no','invc_fcur_amt','invc_fcur_cd','dcl_de','created_at']);
   
   
   return DataTables::of($imports)
        ->addColumn('action', function($row){
            $btn = ''; 
            $btn .= '<button class="btn btn-sm btn-transparent text-success accept-import" 
                data-toggle="tooltip" title="Accept Import" 
                data-id="' . $row->id . '" onclick="openDetailsModal(' . $row->id . ')">
                <i class="fas fa-check-circle"></i>
            </button>';
            $btn .= '<button class="btn btn-sm btn-transparent text-success accept-import" 
                data-toggle="tooltip" title="Accept Import" 
                data-id="' . $row->id . '" onclick="openDetailsModal(' . $row->id . ')">
                <i class="fas fa-boxes"></i>
            </button>';

            
            return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
}




    public function getListimports(Request $request)
{

    
    $business_id = $request->session()->get('user.business_id');

    

    $imports = RRAimports::where('business_id', $business_id)
    ->select('*')
    ->get();
    return $imports->get(); // Fetch the result set
}



public function getLineDetails(Request $request,$id) {

    $business_id = $request->session()->get('user.business_id');

    $lineDetails = RRAimports::find($id); // Fetch the line details from the database

    $lineDetails2 = RRAimports::find($id); // Fetch the line details from the database

    
    $inventoryItems = Product::where('business_id', $business_id)->get(); // Fetch inventory items

    


    $customer_groups = Contact::where('business_id', $business_id)->where('type', 'supplier')->whereNotNull('supplier_business_name')->get();


    // Return a JSON response with both line details and inventory items
    return response()->json([
        'lineDetails' => view('rra.line_details_partial', compact('lineDetails'))->render(),
        'lineDetails2' => view('rra.line_details_partial2', compact('lineDetails2'))->render(),
        'inventoryItems' => view('rra.inventory_items_partial', compact('inventoryItems'))->render(),
        'customer_groups' => view('rra.supplier_items_partial', compact('customer_groups'))->render(),
    ]);
}




public function addStock(Request $request)
{
    // Validate incoming request data
    // $request->validate([
    //     'item_id' => 'required|exists:your_items_table,id', // Adjust as necessary
    //     'quantity' => 'required|numeric|min:1',
    //     'amount' => 'nullable|numeric',
    // ]);

    $itemId = $request->input('item_id');
    $quantity = $request->input('quantity');
    $supplierId = $request->input('contact_id'); 
    $importamount = $request->input('importamount');
    $rowId = $request->input('rowId'); 
    

    $business_id = $request->session()->get('user.business_id');

    $business_details = Business::find($business_id);

    $headquaters=$business_details['headquaters'];
    
    
    $businessLocation = BusinessLocation::where('location_id', $headquaters)->first(); //change this default location

        
    $user_id = $request->session()->get('user.id');
    $enable_product_editing = 1;

//    \Log::info("rowId rowId.....".json_encode($headquaters));

    $importdetails = RRAimports::find($rowId); // Fetch the line details from the database


 //   \Log::info("import amount.....".json_encode($importdetails));
//

    $exchange_rate=$importdetails->exchange_rate;
    $purchaseamount=$importdetails->invc_fcur_amt;

   // \Log::info("purchaseamount.....".json_encode($purchaseamount));

  //  \Log::info("exchange_rate.....".json_encode($exchange_rate));

    //$transaction_data = $request->only(['ref_no', 'status', 'contact_id', 'transaction_date', 'total_before_tax', 'location_id', 'discount_type', 'discount_amount', 'tax_id', 'tax_amount', 'shipping_details', 'shipping_charges', 'final_total', 'additional_notes', 'exchange_rate', 'pay_term_number', 'pay_term_type', 'purchase_order_ids']);
    


    $purchase_date = date('d/m/Y H:i');


    $enable_product_editing=0;
    $currency_details = $this->transactionUtil->purchaseCurrencyDetails($business_id);

    $transaction_data['contact_id'] = $supplierId;
    $transaction_data['location_id'] = $businessLocation->id;
    $transaction_data['discount_type'] = 'percentage';

    $transaction_data['total_before_tax'] = $purchaseamount*$exchange_rate;

    $transaction_data['discount_amount'] = 0;

    $transaction_data['tax_amount'] = 0;
    $transaction_data['shipping_charges'] = 0;
    $transaction_data['final_total'] = $this->productUtil->num_uf($purchaseamount, $currency_details) * $exchange_rate;

    $transaction_data['business_id'] = $business_id;
    $transaction_data['created_by'] = $user_id;
    $transaction_data['type'] = 'purchase';
    $transaction_data['payment_status'] = 'paid';
    $transaction_data['status'] = 'received';

    
    $transaction_data['transaction_date'] = $this->productUtil->uf_date($purchase_date, true);

    //upload document
    //$transaction_data['document'] = $this->transactionUtil->uploadFile($request, 'document', 'documents');

    $transaction_data['custom_field_1'] =  null;
    $transaction_data['custom_field_2'] =  null;
    $transaction_data['custom_field_3'] =  null;
    $transaction_data['custom_field_4'] =  null;

    $transaction_data['shipping_custom_field_1'] = null;
    $transaction_data['shipping_custom_field_2'] = null;
    $transaction_data['shipping_custom_field_3'] = null;
    $transaction_data['shipping_custom_field_4'] = null;
    $transaction_data['shipping_custom_field_5'] = null;

    $product = Product::find($itemId);


    $productvariation = DB::table('product_variations')
    ->where('product_id', $itemId)
    ->first();

    $taxes=TaxRate::find($product->tax);

    \Log::info("taxes..".json_encode($taxes->amount));   
   
 

    // Price including tax
    $priceIncludingTax = $purchaseamount * $exchange_rate;

    // Calculate price excluding tax
    $priceExcludingTax = $priceIncludingTax / (1 + ($taxes->amount / 100));

    // Calculate tax amount
    $taxAmount = $priceIncludingTax - $priceExcludingTax;

    // Format the results to 2 decimal places
    $priceIncludingTax = number_format($priceIncludingTax, 2, '.', '');
    $priceExcludingTax = number_format($priceExcludingTax, 2, '.', '');
    $taxAmount = number_format($taxAmount, 2, '.', '');


    $purchaseline = [
        [
            "product_id" => $itemId,
            "variation_id" =>$productvariation->id,
            "quantity" => $quantity,
            "product_unit_id" => $product->unit_id,
            "sub_unit_id" => null,
            "pp_without_discount" => $priceExcludingTax,
            "discount_percent" => "0.00",
            "purchase_price" => $priceExcludingTax,
            "purchase_line_tax_id" => $product->tax,
            "item_tax" => $taxAmount,
            "purchase_price_inc_tax" => $priceIncludingTax,
            "profit_percent" => 0,
            "default_sell_price" => $purchaseamount*$exchange_rate, //change this
            "mfg_date" => null,
            "exp_date" => null
        ]
    ];



    
    
    $transaction_data['purchases'] = $purchaseline;


            //Update reference count
    $ref_count = $this->productUtil->setAndGetReferenceCount($transaction_data['type']);
            //Generate reference number
    if (empty($transaction_data['ref_no'])) {
                $transaction_data['ref_no'] = $this->productUtil->generateReferenceNumber($transaction_data['type'], $ref_count);
         }

    $transaction = Transaction::create($transaction_data);

    
   

   $this->productUtil->createOrUpdatePurchaseLines($transaction, $purchaseline, $currency_details, $enable_product_editing);

            //Add Purchase payments
   // $this->transactionUtil->createOrUpdatePaymentLines($transaction, $request->input('payment'));

   // $this->transactionUtil->updatePaymentStatus($transaction->id, $transaction->final_total);

   $this->productUtil->adjustStockOverSelling($transaction);

   $this->transactionUtil->activityLog($transaction, 'added');

   PurchaseCreatedOrModified::dispatch($transaction);

    DB::commit();







  //  \Log::info("transaction_data..".json_encode($transaction_data)); 

 //   \Log::info("exchange_rate..".json_encode($exchange_rate));      
     




   // \Log::info("Adding stock ..".json_encode($request->all()));





    return response()->json(['success' => true]);
}






public function syncimports(Request $request)
{
    $business_id = $request->session()->get('user.business_id');

    $business = Business::find($business_id);

    $company_tin = $business->tax_number_1;

    $import_lastReqDt=$business->import_lastReqDt;

    $company_tin = str_replace(' ', '', $company_tin);

    
    $headquaters=$business['headquaters'];

   
    
    
    $getimports = [
        'tin' => $company_tin,
        'bhfId' => $headquaters,
        'lastReqDt' => $import_lastReqDt,
    ];

    // {
    //     "tin": "102598728",
    //     "bhfId": "00",
    //     "lastReqDt": "20170524000000"}

  //  $url = config('app.injonge_url') . '/getimports';

      $url='http://10.10.77.70:9015/injonge/getimports';

    //update_items

    // Initialize cURL session
    $curl = curl_init($url);

    // Set the Content-Type header to application/json
    $headers = [
        'Content-Type: application/json',
    ];

    // Set cURL options
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($getimports));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL request
    $response = curl_exec($curl);

    #\Log::info("=>>>>>>>>>>>>>>>".json_encode($response));


    // Check for errors
    if ($response === false) {
        $error = curl_error($curl);
        echo "cURL Error: $error";
    } else {
        $data_array = json_decode($response, true);

        // Check if decoding was successful
        
        $data = json_decode($response, true);

       // \Log::info("new data................".json_encode($data));


            // Check if decoding was successful
        if ($data && isset($data['data']['itemList'])) {
                // Loop through the itemList
            foreach ($data['data']['itemList'] as $item) {
                   
                    $import_details['task_cd']=$item['taskCd'];
                    $import_details['dcl_de']=$item['dclDe'];
                    $import_details['item_seq']=$item['itemSeq'];
                    $import_details['dcl_no']=$item['dclNo'];
                    $import_details['hs_cd']=$item['hsCd'];
                    $import_details['item_nm']=$item['itemNm'];
                    $import_details['impt_itemstts_cd']=$item['imptItemsttsCd'];
                    $import_details['orgn_nat_cd']=$item['orgnNatCd'];
                    $import_details['exp_nat_cd']=$item['exptNatCd'];
                    $import_details['pkg_qty']=$item['pkg'];
                    $import_details['qty']=$item['qty'];
                    $import_details['invc_fcur_amt']=$item['invcFcurAmt'];
                    $import_details['invc_fcur_cd']=$item['invcFcurCd'];
                    $import_details['tot_wt']=$item['totWt'];
                    $import_details['agnt_nm']=$item['agntNm'];
                    $import_details['business_id']=$business_id;
                    $import_details['created_by'] = $request->session()->get('user.id');
                    $import_details['status'] = 0;
                    $import_details['exchange_rate'] = $item['invcFcurExcrt'];;

                   DB::beginTransaction();

                    $imports = RRAimports::create($import_details);

                    DB::commit();

                    // \Log::error("=>>>>>>>>>>>>>>>".json_encode($import_details['item_nm']));
                   
                }

                $tdate=date("YmdHis");

                $business->import_lastReqDt = $tdate;
            
                $business->save();

                //\Log::info("new data--->>>>>>>>>>>>>>>".json_encode($tdate));



        }else {
                echo "Failed to decode JSON or no data found.";
        }


            
    }        
        

    // Close cURL session
    curl_close($curl);
}

    



    
}
