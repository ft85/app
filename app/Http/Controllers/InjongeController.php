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
use App\Events\ProductsCreatedOrModified;
use App\Utils\Util;


use Illuminate\Support\Facades\Http; 

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;



use PDF;

class InjongeController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $productUtil;

    protected $transactionUtil;

    protected $moduleUtil;
    protected $businessUtil;

    protected $Utils;

    /**
     * Constructor
     *
     * @param  ProductUtils  $product
     * @return void
     */
    

     public function __construct(
        ProductUtil $productUtil,
        BusinessUtil $businessUtil,
        TransactionUtil $transactionUtil,
        Util $Utils,
        
        
    ) {
        $this->productUtil = $productUtil;
        $this->transactionUtil = $transactionUtil;
        $this->businessUtil = $businessUtil;
        
    }


    public function getStockItems(Request $request)
        {
            try {
                $payload = $request->json()->all();

                Log::info("Json Request...", $payload);

                $tin = $payload['tin'] ?? null;
                $last_id = $payload['last_id'] ?? 0;
                $branch_id=$payload['bhfId'];
                

                $business = DB::table('business')
                ->where('tax_number_1', $tin)
                ->first();

                $location = BusinessLocation::where('location_id', $branch_id)->where('business_id', $business->id) ->first(); 
    
                if ($business) {
                    $company_id = $business->id;
                } else {
                    $company_id = null; // or handle the "not found" case
                }

            
                Log::info("Company_id: $company_id");
                Log::info("TIN: $tin");
                Log::info("Last ID: $last_id");

                // Fetch stock items for the company after the last_id
                $stocklist = DB::table('injonge_items')
                ->where('company_id', $company_id)
                ->where('id', '>', $last_id)
                ->where('branch_id', $location->id)
                ->get();

                return response()->json($stocklist);
            } catch (\Exception $e) {
                Log::error("Error fetching stock items: " . $e->getMessage());

                return response()->json([
                    'message' => 'Error',
                    'status' => '910'
                ], 500);
            }
    }


    public function getitemupdates(Request $request)
        {
            try {
                $payload = $request->json()->all();

              //  Log::info("Json Request...", $payload);

                $tin = $payload['tin'] ?? null;
                $last_update = $payload['last_update'] ?? 0;
                $branch_id=$payload['bhfId'];
                
                $business = DB::table('business')
                ->where('tax_number_1', $tin)
                ->first();
                $location = BusinessLocation::where('location_id', $branch_id)->where('business_id', $business->id) ->first(); 
    

                if ($business) {
                    $company_id = $business->id;
                } else {
                    $company_id = null; // or handle the "not found" case
                }

            
                Log::info("Company_id: $company_id");
                Log::info("TIN: $tin");
                Log::info("Last Update: $last_update");

                // Fetch stock items for the company after the last_id
                $stocklist = DB::table('injonge_items')
                ->where('company_id', $company_id)
                ->where('updated_at', '>', $last_update)
                ->where('branch_id', $location->id)
                ->get();

               // Log::info("Last Update stocklist: $stocklist");


                return response()->json($stocklist);
            } catch (\Exception $e) {
                Log::error("Error fetching stock items: " . $e->getMessage());

                return response()->json([
                    'message' => 'Error',
                    'status' => '910'
                ], 500);
            }
    }

    public function getcontacts(Request $request)
        {
            try {
                $payload = $request->json()->all();

                Log::info("Json Request...getcontacts", $payload);

                $tin = $payload['tin'] ?? null;
                $last_id = $payload['last_id'] ?? 0;

                $business = DB::table('business')
                ->where('tax_number_1', $tin)
                ->first();

                if ($business) {
                    $company_id = $business->id;
                } else {
                    $company_id = null; // or handle the "not found" case
                }

            
                Log::info("Company_id: $company_id");
                Log::info("TIN: $tin");
                Log::info("Last ID: $last_id");

                $contactlist = Contact::selectRaw("
                        COALESCE(supplier_business_name, name) AS contact_name,
                        tax_number,
                        mobile AS phone,
                        email,
                        id
                    ")->where('business_id', $business->id)
                    ->where('id', '>', $last_id)
                    ->where('contact_status', 'active')->get();

                return response()->json($contactlist);
            } catch (\Exception $e) {
                Log::error("Error fetching contact list: " . $e->getMessage());

                return response()->json([
                    'message' => 'Error',
                    'status' => '910'
                ], 500);
            }
    }
    
    public function getcategories(Request $request)
    {
        try {
            $payload = $request->json()->all();

            Log::info("Json Request...categories", $payload);

            $tin = $payload['tin'] ?? null;
            $last_id = $payload['last_id'] ?? 0;

            $business = DB::table('business')
            ->where('tax_number_1', $tin)
            ->first();

            if ($business) {
                $company_id = $business->id;
            } else {
                $company_id = null; // or handle the "not found" case
            }

        
            Log::info("Company_id: $company_id");
            Log::info("TIN: $tin");
            Log::info("Last ID: $last_id");

            $categrorylist = DB::table('categories')
            ->where('business_id', $business->id)
            ->where('id', '>', $last_id)
            ->select('id', 'name', 'unspec as itemcode')
            ->get();

            

            return response()->json($categrorylist);
        } catch (\Exception $e) {
            Log::error("Error fetching stock items: " . $e->getMessage());

            return response()->json([
                'message' => 'Error',
                'status' => '910'
            ], 500);
        }
}


    public function editCustomer(Request $request)
    {

        $payload = $request->json()->all();

        Log::info("Json Request...", $payload);

        $tin = $payload['tin'] ?? null;

        $custNm = $payload['custNm'] ?? null;
        $custMblNo = $payload['custMblNo'] ?? null;
        $custTin = $payload['custTin'] ?? null;
        
        $email = $payload['email'] ?? null;
        $sid = $payload['sid'] ?? null;
        
        $business = DB::table('business')
        ->where('tax_number_1', $tin)
        ->first();

        $business_id = $business->id;
        

        $user = User::where('business_id', $business_id)->first();
   

        // Check if a contact with the same name or mobile number exists
        $contact = Contact::where('business_id', $business_id)
            ->where(function ($query) use ($custNm, $custMblNo) {
                $query->where('name', $custNm)
                      ->orWhere('mobile', $custMblNo);
            })
            ->first();

        

        

        $data = [
            'message' => 'Contact processed successfully',
            'contact' => $custNm,
            'resultCd' => '000',
            "resultMsg" => "Success"
        ];
    
    
        return response()->json($data);
    }
 

    public function addCustomer(Request $request)
    {

        $payload = $request->json()->all();

        Log::info("Json Request...", $payload);

        $tin = $payload['tin'] ?? null;

        $custNm = $payload['custNm'] ?? null;
        $custMblNo = $payload['custMblNo'] ?? null;
        $custTin = $payload['custTin'] ?? null;
        
        $email = $payload['email'] ?? null;
        
        $tin=$request->input('tin');
        $business = DB::table('business')
        ->where('tax_number_1', $tin)
        ->first();

        $business_id = $business->id;
        

        $user = User::where('business_id', $business_id)->first();
   

        // Check if a contact with the same name or mobile number exists
        $contact = Contact::where('business_id', $business_id)
            ->where(function ($query) use ($custNm, $custMblNo) {
                $query->where('name', $custNm)
                      ->orWhere('mobile', $custMblNo);
            })
            ->first();

        if (!$contact && (!empty($custMblNo) || !empty($custNm))) {
            // Create a new contact
            $contact = Contact::create([
                'business_id' => $business_id,
                'type' => 'customer',
                'name' => $custNm,
                'email' => $email,
                'mobile' => $custMblNo,
                'tax_number' => $custTin,
                'created_by' => $user->id,
            ]);
        }

        

        $data = [
            'message' => 'Contact processed successfully',
            'contact' => $custNm,
            'resultCd' => '000',
            "resultMsg" => "Success"
        ];
    
    
        return response()->json($data);
    }



    public function addProduct(Request $request)
    {
        // Validate the incoming request data
        // $validator = Validator::make($request->all(), [
        //     'tin' => 'required|integer',
        //     'name' => 'nullable|string|max:255',
        //     'categrory_id' => 'nullable|string|max:15',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'message' => 'Validation Error',
        //         'errors' => $validator->errors(),
        //     ], 422);
        // }

       // Log::info("Json Request..incoming request.\n" . json_encode($request));


        $data = $request->json()->all();

        $tin=$data['tin'];
        $branch_id=$data['bhfId'];
        $product_name=$data['product_name'];
        $taxname=$data['taxid'];
        $item_price=$data['price'];
        $category_id=$data['category_id'];
        $stockable=$data['stockable'];
        $unspec=$data['unspec'];
        
        $opening_stock=$data['opening_stock'];
        

        $business = DB::table('business')
        ->where('tax_number_1', $tin)
        ->first();

        $business_id = $business->id;

        $location = BusinessLocation::where('location_id', $branch_id)
        ->where('business_id', $business_id) 
        ->first();

       // Log::info("Json Request..incoming business" . json_encode($business));

       
       $tax_details = TaxRate::where('name', $taxname)
        ->where('business_id', $business_id) 
        ->first();
        $unit = DB::table('units')->where('business_id', $business_id)->first();

        $tax_exclusive_price = $item_price;

        if ($taxname === 'B') {
            $tax_rate = 18; // 18 percent
            $tax_exclusive_price = $item_price / (1 + ($tax_rate / 100));
        }

        $user = User::where('business_id', $business_id)->first();
        $product_details=[];
        $product_details['business_id'] = $business_id;
        $product_details['name'] = $product_name;
        $product_details['product_description'] = $product_name;
        $product_details['single_dsp_inc_tax'] = $item_price;
        $product_details['tax'] =$tax_details->id;
        $product_details['enable_stock'] = $stockable; 
        $product_details['type'] = 'single';
        $product_details['tax_type'] = 'inclusive';
        $product_details['unit_id'] = $unit->id;
        $product_details['created_by']=$user->id;
        $product_details['tax_type'] = 'inclusive';
        $product_details['warranty_id'] = null;
        $product_details['barcode_type'] = 'C128';
        $product_details['not_for_selling'] = '0';
        $product_details['category_id'] = $category_id;
        $product_details['brand_id'] = null;     

        if ($product_details['enable_stock'] == 1) {
            $stockable = 0;
        } else {
            $stockable = 0;
        }

        // $item_data  = [
        //     'product_name' => $product_name,
        //     'unspec' => $unspec,
        //     'price' => $item_price,
        //     'taxid' => $taxname,
        //     'tin' => $tin,
        //     'category_id' => $category_id,
        //     'image' => '',
        //     'bhfId' => $branch_id, //confirm this works
        //     'stockable' => $stockable,
        //     'opening_stock' => 0,
        // ];

        // $url = config('app.injonge_url') . '/update_items';
 
        //      // Initialize cURL session
        //      $curl = curl_init($url);
 
        //      // Set the Content-Type header to application/json
        //      $headers = [
        //          'Content-Type: application/json',
        //      ];
 
        //      // Set cURL options
        //      curl_setopt($curl, CURLOPT_POST, true);
        //      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($item_data));
        //      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 
        //      // Execute the cURL request
        //      $response = curl_exec($curl);
 
        //      // Check for errors
        //      if ($response === false) {
        //          $error = curl_error($curl);
        //      } else {
        //          // Print the response
 
        //          // echo "Response: $response";
 
        //          $responseArray = json_decode($response, true);
 
        //          // Access the values of rcptSign and rcptNo from the data array
        //          $status = $responseArray['status'];
        //          $message = $responseArray['message'];
        //          $sid = $responseArray['sid'];
        //          $injongecode = $responseArray['injongecode'];
        //          $product_details['unspec'] = $unspec;
        //          $product_details['injonge_code'] = $injongecode;
        //          $product_details['sid'] = $sid;
        //          $product_details['sku'] = $injongecode;
                 
        //    }
        
        
        $productadd =  $product = Product::create($product_details);
     
        event(new ProductsCreatedOrModified($product_details, 'added'));

        $newsku='BINJO'.$productadd->id;
        $productadd->injonge_code = $newsku;
        $productadd->sku = $newsku;
        $productadd->save();
        

        if ($product->type == 'single') {
            $this->productUtil->createSingleProductVariation($product->id, $productadd->sku, $tax_exclusive_price, $item_price,'0', $item_price, $item_price);
        }

        $product_locations = [$location->id];

        if (! empty($product_locations)) {
                     $product->product_locations()->sync($product_locations);
                 }

        if ($product_details['enable_stock'] == 1 && ! empty($opening_stock>0)) {
           
            $user_id = $user->id;
            $transaction_date = now()->format('Y-m-d H:i:s');

            $this->productUtil->addSingleProductOpeningStock($business_id, $product,$opening_stock , $transaction_date, $user_id);
        }         

        return response()->json([
            'resultCd' => '000',
            'injonge_code' => $newsku,
            'resultMsg' => 'Success'
        ]);
    }


    public function editProduct(Request $request)
    {
        

        //Log::info("Json Request..incoming request.\n" . json_encode($request));
        $data = $request->json()->all();

        $tin=$data['tin'];
        $branch_id=$data['bhfId'];
        $product_name=$data['product_name'];
        $taxname=$data['taxid'];
        $item_price=$data['price'];
        $category_id=$data['category_id'];
        $stockable=$data['stockable'];
        $unspec=$data['unspec'];
        $sid=$data['sid'];
        
        $business = DB::table('business')
        ->where('tax_number_1', $tin)
        ->first();

        $tax_details = TaxRate::where('name', $taxname)
        ->where('business_id', $business->id) 
        ->first();
        $tax_details = TaxRate::where('name', $taxname)
        ->where('business_id', $business->id) 
        ->first();
        $tax_exclusive_price = $item_price;

        if ($taxname === 'B') {
            $tax_rate = 18; // 18 percent
            $tax_exclusive_price = $item_price / (1 + ($tax_rate / 100));
        }
        $product = Product::with('variations')->find($sid);

        if ($product) {
            // Update product
            $product['name']=$product_name;
            $product['enable_stock']=$stockable;
            $product['tax']=$tax_details->id;
            $product->save();
        
            // Update variation(s)
            foreach ($product->variations as $variation) {
                $variation->sell_price_inc_tax = $item_price;
                $variation->default_sell_price = $tax_exclusive_price;

                 $variation->default_purchase_price = $tax_exclusive_price;
                 $variation->default_sell_price = $item_price;
                
                $variation->save();
            }
        }
      //  Log::info("Json Request..from db product.\n" . json_encode($product));
 
    
    }

    public function getInfoSerial(Request $request)
{
    try {
        $payload = $request->json()->all();

        Log::info("Json Request..incoming product.\n" . json_encode($payload));

        $date = now()->format('dmyHis');
        $onlinemanagement = "0";

        $serial = $payload['serial'] ?? '';

        // Check if device exists
        $checkexists = DB::table('devices')
            ->where('serial', $serial)
            ->count();

        if ($checkexists == 0) {
            return response()->json([
                'resultCd' => '940',
                'resultMsg' => 'Device not found in our database'
            ]);
        }

        // Check if device is enabled
        $enabled = DB::table('devices')
            ->where('serial', $serial)
            ->value('enabled');

        if (strtolower($enabled) === '0') {
            return response()->json([
                'resultCd' => '940',
                'resultMsg' => 'User is disabled'
            ]);
        }
        // Get company and branch info
        $business_id = DB::table('devices')
            ->where('serial', $serial)
            ->value('business_id');

        $branch_id = DB::table('devices')
            ->where('serial', $serial)
            ->value('branch_id');
        
        Log::info("branch_id..." . json_encode($branch_id));
        Log::info("business_id..." . json_encode($business_id));

    

        $business_details = Business::find($business_id); 

       // Log::info("business..." . json_encode($business_details));
        
        $location = BusinessLocation::where('location_id', $branch_id)
        ->where('business_id', $business_id) 
        ->first();

       // Log::info("location..." . json_encode($location));


        
        $company_name = $company_address = $company_email = $company_phone = $tin = "";

        $company_name=$business_details->name;
        $logo=$business_details->logo;
        $company_address=$location->landmark;
        $company_phone=$location->mobile;
        $company_email=$location->email;
        $tin=$business_details->tax_number_1;
        

       if ($business_id > 0) {
            $info = [
                'tin' => trim($tin),
                'taxprNm' => $company_name,
                'bhfId' => $branch_id,
                'bhfOpenDt' => '',
                'prvncNm' => $company_address,
                'dstrtNm' => '',
                'sctrNm' => '',
                'dvcId' => '',
                'locDesc' => $company_address,
                'mgrNm' => '',
                'mgrEmail' => $company_email,
                'mgrTelNo' => $company_phone,
                'lastPchsInvcNo' => '0',
                'lastSaleRcptNo' => '0',
                'lastInvcNo' => '0',
                'lastSaleInvcNo' => '0',
                'lastTrainInvcNo' => '0',
                'lastProfrmInvcNo' => '0',
                'lastCopyInvcNo' => '0',
                'logo' => $logo,
                'mrcNo' => '',
                'intrlKey' => '',
                'signKey' => '',
                'cmcKey' => '',
                'onlinemanagement' => $onlinemanagement
            ];

            return response()->json([
                'resultCd' => '000',
                'resultMsg' => 'Success',
                'resultDt' => $date,
                'data' => $info
            ]);
        } else {
            return response()->json([
                'resultCd' => '910',
                'resultMsg' => 'Failed',
                'resultDt' => $date
            ]);
        }

    } catch (\Exception $e) {
        \Log::error("Error in getInfoSerial: " . $e->getMessage());

        return response()->json([
            'message' => 'Error',
            'status' => '910'
        ]);
    }
}

//{"tin":"123456789","custTin":"100001498","custNm":"UNKNOWN","prcOrdCd":"null","deviceSerial":"e7c41c13875ce8d7","rcptTyCd":"S","salesTyCd":"N","bhfId":"00","orgInvcNo":"0","pmtTyCd":"01","salesSttsCd":"02","cfmDt":"20250408171252","stockRlsDt":"20250408171252","salesDt":"20250408","totItemCnt":"1","taxblAmtA":0,"taxblAmtB":70000,"taxblAmtC":0,"taxblAmtD":0,"taxRtA":0,"taxRtB":18,"taxRtC":0,"taxRtD":0,"taxAmtA":0,"taxAmtB":10677.97,"taxAmtC":0,"taxAmtD":0,"totTaxblAmt":70000,"totTaxAmt":10677.97,"totAmt":70000,"prchrAcptcYn":"Y","remark":"nk","regrId":"1234","regrNm":"Hitrag","modrId":"2342","modrNm":"Hitrag","receipt":{"custTin":"100001498","custMblNo":"721638836","rcptPbctDt":"20250408171252","intrlData":"","rcptSign":"","jrnl":"","trdeNm":"Hitrag","adrs":"Rwanda","topMsg":"Hitrag\n TIN: 123456789\nAddress: Rwanda\nEmail: \nPhone: 0788408662","btmMsg":"Thank you ","prchrAcptcYn":"Y"},"itemList":[{"itemSeq":1,"itemClsCd":"5020220600","itemCd":"RWINJON0000223","itemNm":"4TH Street Sweet Red 5L","pkgUnitCd":"NT","pkg":1,"prc":"35000.0","qty":2,"splyAmt":"70000.00","dcRt":"0","dcAmt":".00","taxTyCd":"B","taxAmt":"10677.97","taxblAmt":"70000.00","totAmt":"70000.00","qtyUnitCd":"U"}]}

public function postsales(Request $request){

    $data = $request->all();

    $tin=$data['tin'];
    $branch_id=$data['bhfId'];
    $custMblNowithprefix = $data['receipt']['custMblNo'] ?? null;

    $custMblNo = preg_replace('/\D+/',    '', $custMblNowithprefix);
    $custMblNo = preg_replace('/^(?:257|254)/', '', $custMblNo);
    $custMblNo = ltrim($custMblNo, '0');
   
    $custNm=$data['custNm'];

  

   // Log::info("contact..." . json_encode($custMblNo));
    

    //Log::info("business..." . json_encode($tin));
    
   // Log::info("business..." . json_encode($branch_id));
    
    $business_details = DB::table('business')
                    ->where('tax_number_1', $tin)
                    ->first();
    
    $business_id=$business_details->id;

    // Log::info("business..." . json_encode($business_details));
    // Log::info("business..." . json_encode($business_id));

    $location = BusinessLocation::where('location_id', $branch_id)->where('business_id', $business_id) ->first(); 
    $user = User::where('business_id', $business_id)->first();
   

    $final_total=$data['totAmt'];
    $invoice_total_tax=$data['totTaxAmt'];
    $sell_lines = [];
    foreach ($data['itemList'] as $item) {
         
        $product = Product::where('business_id', $business_id)
                            ->where('injonge_code', $item['itemCd'])
                            ->with(['variations'])
                            ->first();

        $variation = ! empty($product) ? $product->variations->first() : null;

        $tax_details = TaxRate::where('business_id', $business_id)
        ->where('name', $item['taxTyCd'])
        ->first();

        

        $sell_line = [
            'product_id' => $product->id, // Assuming itemCd corresponds to product_id
            'variation_id' => $variation->id, // Assuming itemSeq corresponds to variation_id
            'quantity' => $item['qty'],
            'unit_price' => $item['prc']- $item['taxAmt'],
            'unit_price_inc_tax' => $item['prc'], 
            'line_discount_type' => 'percentage',
            'line_discount_amount' => $item['dcAmt'],
            'item_tax' => $item['taxAmt'],
            'tax_id' => $tax_details->id, 
            'sell_line_note' => $item['itemNm'], 
            'product_unit_id' => $product->unit_id,
            'sub_unit_id' => $product->unit_id,
            'enable_stock' => $product->enable_stock, 
            'base_unit_multiplier' => 1,
            'type' => $product->type,
            'combo_variations' => [], 
        ];

        // Add this sell line to the sell_lines array
        $sell_lines[] = $sell_line;
                                
    }

    if ((empty($contact) && $data['custTin'] === '000000000' && $custMblNo === '777777777')) {
        $contact = Contact::where('business_id', $business_id)
                          ->where('name', 'Walk-In Customer')
                          ->first();
    }
   

    if (empty($contact) && ! empty($data['custTin'])) {
        $contact = Contact::where('business_id', $business_id)
                                ->where('tax_number', $data['custTin'])
                                ->first();
        }

   if (empty($contact) && !empty($custMblNo)) {
    $contact = Contact::where('business_id', $business_id)
    ->whereRaw("
      RIGHT(
        REGEXP_REPLACE(mobile, '[^0-9]', ''),   -- strip non‑digits :contentReference[oaicite:0]{index=0}
        9                                      -- take last 9 digits
      ) = ?
    ", [$custMblNo])
    ->first();
    } 
    

    if (empty($contact) && ! empty($data['custNm'])) {
        $contact = Contact::where('business_id', $business_id)
                                ->where('name', $data['custNm'])
                                ->first();
        }
    
        if (!empty($custMblNo) || !empty($custNm)) {
            $contact = Contact::firstOrCreate(
                [
                    'business_id' => $business_id,
                    'type' => 'customer',
                    'mobile' => $custMblNo,
                    'name' => $custNm,
                    'tax_number' => $data['custTin'],
                
                ],
                [
                    'email' => $custNm.'@gmail.com',
                    'created_by' => $user->id,
                ]
            );
        }

        

    $now = \Carbon::now()->toDateTimeString();
        
    $sale_data = [
        
        'location_id' => $location->id,
        'status' => 'final',
        'contact_id' => $contact->id,
        'final_total' => $final_total,
        'total_before_tax' => number_format($final_total - $invoice_total_tax, 2, '.', ''),
        'transaction_date' => $now,
        'discount_amount' => 0,
        'import_batch' => null,
        'import_time' => $now,
        'commission_agent' => null,
        'customer_group_id'=>0,
        'payment_status' => 'paid',
        'products'=>$sell_lines,
    ];

    // $sale_data['types_of_service_id'] = $types_of_service->id;
    // $sale_data['service_custom_field_1'] = ! empty($first_sell_line['service_custom_field1']) ? $first_sell_line['service_custom_field1'] : null;
    // $sale_data['service_custom_field_2'] = ! empty($first_sell_line['service_custom_field2']) ? $first_sell_line['service_custom_field2'] : null;
    // $sale_data['service_custom_field_3'] = ! empty($first_sell_line['service_custom_field3']) ? $first_sell_line['service_custom_field3'] : null;
    // $sale_data['service_custom_field_4'] = ! empty($first_sell_line['service_custom_field4']) ? $first_sell_line['service_custom_field4'] : null;

   // Log::info("sale_data.." . json_encode($sale_data));

    $invoice_total = [
        'tax' => $invoice_total_tax,
        'discount' => 0,  
        'final_total' => $final_total,
        'total_before_tax' => number_format($final_total - $invoice_total_tax, 2, '.', ''),
        
    ];

   

    $transaction = $this->transactionUtil->createSellTransaction($business_id, $sale_data, $invoice_total, $user->id, false); //change user id 

   
    $this->transactionUtil->createOrUpdateSellLines($transaction, $sell_lines, $location->id, false, null, [], false);

    $payment_status = $this->transactionUtil->updatePaymentStatus($transaction->id, $transaction->final_total);
    $transaction->payment_status = $payment_status;
    $transaction->type ='sell';
    $transaction->payment_status = 'paid';
    $transaction->save();

    $invoiceurl = $this->transactionUtil->getInvoiceUrl($transaction->id, $business_id);

    foreach ($sell_lines as $line) {
        if ($line['enable_stock']) {
            $this->productUtil->decreaseProductQuantity(
                $line['product_id'],
                $line['variation_id'],
                $location->id,
                $line['quantity']
            );
        }
    }
  
    $business_details = $this->businessUtil->getDetails($business_id);
    $pos_settings = empty($business_details->pos_settings) ? $this->businessUtil->defaultPosSettings() : json_decode($business_details->pos_settings, true);

    $business = ['id' => $business_id,
                'accounting_method' => 'fifo',
                'location_id' => $location->id,
                'pos_settings' => $pos_settings,
            ];
    //$this->transactionUtil->mapPurchaseSell($business, $transaction->sell_lines, 'purchase');  remove mapping first 


    //update payments

    $payments = [
        [
            'amount' => $final_total,
            'method' => 'cash',
        ],
        [
            'method'                     => 'cash',
            'account_id'                 => null,
            'card_number'                => null,
            'card_holder_name'           => null,
            'card_transaction_number'    => null,
            'card_type'                  => 'credit',
            'card_month'                 => null,
            'card_year'                  => null,
            'card_security'              => null,
            'cheque_number'              => null,
            'bank_account_number'        => null,
            'transaction_no_1'           => null,
            'transaction_no_2'           => null,
            'transaction_no_3'           => null,
            'transaction_no_4'           => null,
            'transaction_no_5'           => null,
            'transaction_no_6'           => null,
            'transaction_no_7'           => null,
            'amount'                     => '0.00',
            'is_return'                  => 1,
        ],
    ];

    $this->transactionUtil->createOrUpdatePaymentLines($transaction, $payments,$business_id, $user->id, false);

    //$invoiceno = explode('-', $transaction->invoice_no)[1] ?? null;
    $formattedDate = Carbon::parse($transaction->transaction_date)->format('dmyHis');


    $data = [
        "resultDt" => $formattedDate,
        "resultCd" => "000",
        "link" => $invoiceurl,
        "data" => [
            "invcNo" => $transaction->invoice_no,
            "intrlData" => $transaction->internal_data,
            "totRcptNo" => $transaction->invoiceno,
            "rcptSign" => $transaction->rcptsign,
            "rcptNo" => $transaction->invoiceno,
            "vsdcRcptPbctDate" => $formattedDate,
            
        ],
        "resultMsg" => "Success"
    ];


    return response()->json($data);


}


function normalizePhone(string $raw): string
{
    // 1. strip all non‑digits
    $digits = preg_replace('/\D+/', '', $raw);
    // 2. remove leading country code 250 or 254
    $digits = preg_replace('/^(?:257|254)/', '', $digits);
    // 3. remove any remaining leading zeros
    $digits = ltrim($digits, '0');

    return $digits;
}

public function postreturn(Request $request){
    $data = $request->all();

    Log::info("refund request..." . json_encode($data));
   

    $tin=$data['tin'];
    $branch_id=$data['bhfId'];
    $orgInvcNo=$data['orgInvcNo'];
    
    $business_details = DB::table('business')
                    ->where('tax_number_1', $tin)
                    ->first();


    $db_invoiceno=$orgInvcNo;
    
    $business_id=$business_details->id;
    $now = \Carbon::now()->toDateTimeString();
    

    $location = BusinessLocation::where('location_id', $branch_id)->where('business_id', $business_id) ->first(); 
    $user = User::where('business_id', $business_id)->first();
    $final_total=$data['totAmt'];
    $invoice_total_tax=$data['totTaxAmt'];
    $transaction_date = now()->format('Y-m-d H:i:s');

    $invoice_total = [
        'tax' => $invoice_total_tax,
        'discount' => 0,  
        'final_total' => $final_total,
        'total_before_tax' => number_format($final_total - $invoice_total_tax, 2, '.', ''),
        
    ];

    $sell = Transaction::where('business_id', $business_id)
    ->where('invoice_no', $orgInvcNo)
    ->with(['sell_lines', 'sell_lines.sub_unit'])
    ->firstOrFail();

        //Check if any sell return exists for the sale
    // $sell_return = Transaction::where('business_id', $business_id)
    //         ->where('type', 'sell_return')
    //         ->where('return_parent_id', $sell->id)
    //         ->first();

     //Log::info("sell..." . json_encode($sell));
     
    
    $sell_return_data = [
                'invoice_no' => $data['invoice_no'] ?? null,
                'discount_type' => 'percentage',
                'discount_amount' => 0,
                'tax_id' => null,
                'tax_amount' => $invoice_total['tax'],
                'total_before_tax' => $invoice_total['total_before_tax'],
                'final_total' => $invoice_total['final_total'],
                'refundreason' => '06',
                'transaction_date' =>$transaction_date ,
            ];        
   
     //Generate reference number
     
    
   // Log::info("sell..." . json_encode($sell_return_data));
   
    
    if (empty($sell_return)) {
        $sell_return_data['transaction_date'] = $sell_return_data['transaction_date'] ?? \Carbon::now();
        $sell_return_data['business_id'] = $business_id;
        $sell_return_data['location_id'] = $sell->location_id;
        $sell_return_data['contact_id'] = $sell->contact_id;
        $sell_return_data['customer_group_id'] = $sell->customer_group_id;
        $sell_return_data['type'] = 'sell_return';
        $sell_return_data['status'] = 'final';
        $sell_return_data['created_by'] = $user->id;
        $sell_return_data['return_parent_id'] = $sell->id;
        $sell_return_data['refundreason'] = '06';
        $sell_return = Transaction::create($sell_return_data);

        //$this->activityLog($sell_return, 'added');
    }
    $this->transactionUtil->updatePaymentStatus($sell_return->id, $sell_return->final_total);

    foreach ($sell->sell_lines as $sell_line) {
        $quantity = $sell_line->quantity;
        $quantity_before = $sell_line->quantity_returned;
    
        $sell_line->quantity_returned = $quantity;
        $sell_line->save();
    
        $this->transactionUtil->updateQuantitySoldFromSellLine($sell_line, $quantity, $quantity_before, false);
    
        $this->productUtil->updateProductQuantity(
            $sell_return->location_id,
            $sell_line->product_id,
            $sell_line->variation_id,
            $quantity,
            $quantity_before,
            null,
            false
        );
    }

        

        $data = [
            "resultDt" => $now,
            "resultCd" => "000",
            "link" => '',
            "data" => [
                "invcNo" => $sell_return->invoice_no,
                "intrlData" =>null ,
                "totRcptNo" => null,
              //  "rcptSign" => $responsedata['rcptSign'],
               // "rcptNo" => $responsedata['rcptNo'],
                "vsdcRcptPbctDate" => $now,
                
            ],
            "resultMsg" => "Success"
        ];
    
    
        return response()->json($data);    

        


}




public function postcreditnote(Request $request){

    $data = $request->all();

    Log::info("refund request..." . json_encode($data));
   
    $tin=$data['tin'];
    $branch_id=$data['bhfId'];
    $orgInvcNo=$data['orgInvcNo'];
    
    $business_details = DB::table('business')
                    ->where('tax_number_1', $tin)
                    ->first();

    $db_invoiceno=$orgInvcNo;
    
    $business_id=$business_details->id;
    $now = \Carbon::now()->toDateTimeString();
    

    $location = BusinessLocation::where('location_id', $branch_id)->where('business_id', $business_id) ->first(); 
    $user = User::where('business_id', $business_id)->first();
    // $final_total=$data['totAmt'];
     $invoice_total_tax=$data['totTaxAmt'];
    //$transaction_date = now()->format('Y-m-d H:i:s');

    
    
    $currentDate = Carbon::now(); // Gets the current date and time

// Format the date in 'd/m/Y H:i' format (e.g., 23/04/2025 15:53)
$transaction_date = $currentDate->format('d/m/Y H:i');

Log::info("Transaction Date being passed: " . $transaction_date);


$now = \Carbon::now()->toDateTimeString();
    

    $transaction_date = now()->format('Y-m-d H:i:s');




    // $invoice_total = [
    //     'tax' => $invoice_total_tax,
    //     'discount' => 0,  
    //     'final_total' => $final_total,
    //     'total_before_tax' => number_format($final_total - $invoice_total_tax, 2, '.', ''),
        
    // ];

    $originalsell = Transaction::where('business_id', $business_id)
    ->where('invoice_no', $orgInvcNo)
    ->with(['sell_lines', 'sell_lines.sub_unit'])
    ->firstOrFail();

    Log::info(" originalsell..id." . json_encode($originalsell->id));
   
    
   $products = [];

   foreach ($data['itemList'] as $item) {
       // Match item with sell_line by product_id
       $product = Product::where('business_id', $business_id)
                            ->where('injonge_code', $item['itemCd'])
                            ->first();

         Log::info(" productid ..." . json_encode($product->id));
         Log::info(" productid ..." . json_encode($originalsell->sell_lines));
                       
       $matched_line = $originalsell->sell_lines->firstWhere('product_id', $product->id);
   
       if ($matched_line) {
           $products[] = [
               "quantity" => $item['qty'],
               "unit_price_inc_tax" => $item['prc'],
               "sell_line_id" => $matched_line->id, // matched sell_line ID
           ];
       } else {
           \Log::warning("Sell line not found for product_id: " . $item['product_id']);
       }
   }

      
      $data = [
        "transaction_id" => $originalsell->id,
         "invoice_no" => null,
      //  "transaction_date" => $transaction_date,
       "products" => $products,
        "discount_type" => null,
        "discount_amount" => "0.0",
        "refundreason" => '05',
        "tax_id" => null,
        "tax_amount" => $invoice_total_tax,
         "tax_percent" => 0
     ];

     DB::beginTransaction(); 
    
     $sell_return = $this->transactionUtil->addSellReturn($data, $business_id,$user->id);

    // $receipt = $this->receiptContent($business_id, $sell_return->location_id, $sell_return->id);

     DB::commit();
     
    

 //   {"transaction_id":"22245","invoice_no":null,"transaction_date":"04/23/2025 15:53","products":[{"quantity":"1.00","unit_price_inc_tax":"5,000.00","sell_line_id":"43157"}],"discount_type":null,"discount_amount":"0.00","refundreason":"11","tax_id":null,"tax_amount":"0","tax_percent":"0"} 
            
   


        

        $data = [
            "resultDt" => $now,
            "resultCd" => "000",
            "link" => '',
            "data" => [
               // "invcNo" => $sell_return->invoice_no,
                "intrlData" =>null ,
                "totRcptNo" => null,
              //  "rcptSign" => $responsedata['rcptSign'],
               // "rcptNo" => $responsedata['rcptNo'],
                "vsdcRcptPbctDate" => $now,
                
            ],
            "resultMsg" => "Success"
        ];
    
    
        return response()->json($data);         


}

public function verifyTaxNumber(Request $request)
    {
         $data = $request->all();

        $tin=$data['tin'];
        $branch_id=$data['bhfId'];
        $custmtin=$data['custmtin'];
        
    
    $business_details = DB::table('business')
                    ->where('tax_number_1', $tin)
                    ->first();
    $business_id=$business_details->id ;

        $credentials = \DB::table('business_locations')
        ->where('business_id', $business_id)
        ->select('username', 'password')
        ->first();

        if (!$credentials) {
            return response()->json(['success' => false, 'message' => 'Invalid business credentials']);
        }
        $username = $credentials->username;
        $password = $credentials->password;
        $password = htmlspecialchars_decode($password, ENT_QUOTES);
        $response = Http::post('https://ebms.obr.gov.bi:9443/ebms_api/login/', [
            'username' => $username,
            'password' => $password,
        ]);

        if ($response->successful()) {
            $token = $response->json()['result']['token'];

            // Vérifier le NIF avec le token Bearer
            $verifyResponse = Http::withToken($token)->post('https://ebms.obr.gov.bi:9443/ebms_api/checkTIN/', [
                'tp_TIN' => $custmtin,
            ]);

            if ($verifyResponse->successful()) {
                $data = $verifyResponse->json();

                if (!empty($data['result']['taxpayer']) && isset($data['result']['taxpayer'][0]['tp_name'])) {
                    $tp_name = $data['result']['taxpayer'][0]['tp_name'];
                    return response()->json(['success' => true, 'name' => $tp_name]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Tax number not found']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Invalid tax number']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Authentication failed']);
        }
    }


function send_vsdc_request_refund($vsdc_request)
    {
       // $url = config('app.injonge_url') . '/injongeReceiptsRRADirect';

        $url = 'http://test.injonge.rw:9015/injonge/injongeReceiptsRRADirect';

        $response = Http::post($url, $vsdc_request);


        return  $response->json();
    }


    // public function addStock(Request $request)
    // {
    //     // Validate incoming request data
    //     // $request->validate([
    //     //     'item_id' => 'required|exists:your_items_table,id', // Adjust as necessary
    //     //     'quantity' => 'required|numeric|min:1',
    //     //     'amount' => 'nullable|numeric',
    //     // ]);
    
    //     $itemId = $request->input('item_id');
    //     $quantity = $request->input('quantity');
    //     $supplierId = $request->input('contact_id'); 
    //     $importamount = $request->input('importamount');
    //     $rowId = $request->input('rowId'); 
        
    
    //     $business_id = $request->session()->get('user.business_id');
    
    //     $business_details = Business::find($business_id);
    
    //     $headquaters=$business_details['headquaters'];
        
    //     $businessLocation = BusinessLocation::where('location_id', $headquaters)
    //                                      ->where('business_id', $business_id)
    //                                      ->first();
    
        
    //     $user_id = $request->session()->get('user.id');
    //     $enable_product_editing = 1;
    
    //      $importdetails = RRAimports::find($rowId); // Fetch the line details from the database
    
    
    //     $exchange_rate=$importdetails->exchange_rate;
    //     $purchaseamount=$importdetails->invc_fcur_amt;
    
    //    // \Log::info("purchaseamount.....".json_encode($purchaseamount));
    
    //   //  \Log::info("exchange_rate.....".json_encode($exchange_rate));
    
    //     //$transaction_data = $request->only(['ref_no', 'status', 'contact_id', 'transaction_date', 'total_before_tax', 'location_id', 'discount_type', 'discount_amount', 'tax_id', 'tax_amount', 'shipping_details', 'shipping_charges', 'final_total', 'additional_notes', 'exchange_rate', 'pay_term_number', 'pay_term_type', 'purchase_order_ids']);
        
    
    
    //     $purchase_date = date('d/m/Y H:i');
    
    
    //     $enable_product_editing=0;
    //     $currency_details = $this->transactionUtil->purchaseCurrencyDetails($business_id);
    
    //     $transaction_data['contact_id'] = $supplierId;
    //     $transaction_data['location_id'] = $businessLocation->id;
    //     $transaction_data['discount_type'] = 'percentage';
    
    //     $transaction_data['total_before_tax'] = $purchaseamount*$exchange_rate;
    
    //     $transaction_data['discount_amount'] = 0;
    
    //     $transaction_data['tax_amount'] = 0;
    //     $transaction_data['shipping_charges'] = 0;
    //     $transaction_data['final_total'] = $this->productUtil->num_uf($purchaseamount, $currency_details) * $exchange_rate;
    
    //     $transaction_data['business_id'] = $business_id;
    //     $transaction_data['created_by'] = $user_id;
    //     $transaction_data['type'] = 'purchase';
    //     $transaction_data['payment_status'] = 'paid';
    //     $transaction_data['status'] = 'received';
    
        
    //     $transaction_data['transaction_date'] = $this->productUtil->uf_date($purchase_date, true);
    
    //     //upload document
    //     //$transaction_data['document'] = $this->transactionUtil->uploadFile($request, 'document', 'documents');
    
    //     $transaction_data['custom_field_1'] =  null;
    //     $transaction_data['custom_field_2'] =  null;
    //     $transaction_data['custom_field_3'] =  null;
    //     $transaction_data['custom_field_4'] =  null;
    
    //     $transaction_data['shipping_custom_field_1'] = null;
    //     $transaction_data['shipping_custom_field_2'] = null;
    //     $transaction_data['shipping_custom_field_3'] = null;
    //     $transaction_data['shipping_custom_field_4'] = null;
    //     $transaction_data['shipping_custom_field_5'] = null;
    
    //     $product = Product::find($itemId);
    
    
    //     $productvariation = DB::table('product_variations')
    //     ->where('product_id', $itemId)
    //     ->first();
    
    //     $taxes=TaxRate::find($product->tax);
    
    //    // \Log::info("taxes..".json_encode($product));   
       
    //     // Price including tax
    //     $priceIncludingTax = $purchaseamount * $exchange_rate;
    
    //     // Calculate price excluding tax
    //     $priceExcludingTax = $priceIncludingTax / (1 + ($taxes->amount / 100));
    
    //     // Calculate tax amount
    //     $taxAmount = $priceIncludingTax - $priceExcludingTax;
    
    //     // Format the results to 2 decimal places
    //     $priceIncludingTax = number_format($priceIncludingTax, 2, '.', '');
    //     $priceExcludingTax = number_format($priceExcludingTax, 2, '.', '');
    //     $taxAmount = number_format($taxAmount, 2, '.', '');
    
    
    //     $purchaseline = [
    //         [
    //             "product_id" => $itemId,
    //             "variation_id" =>$productvariation->id,
    //             "quantity" => $quantity,
    //             "product_unit_id" => $product->unit_id,
    //             "sub_unit_id" => null,
    //             "pp_without_discount" => $priceExcludingTax,
    //             "discount_percent" => "0.00",
    //             "purchase_price" => $priceExcludingTax,
    //             "purchase_line_tax_id" => $product->tax,
    //             "item_tax" => $taxAmount,
    //             "purchase_price_inc_tax" => $priceIncludingTax,
    //             "profit_percent" => 0,
    //             "default_sell_price" => $purchaseamount*$exchange_rate, //change this
    //             "mfg_date" => null,
    //             "exp_date" => null
    //         ]
    //     ];
    
    
    
    //    // \Log::info("purchaseline..".json_encode($purchaseline)); 
    
    
    
        
        
    //     $transaction_data['purchases'] = $purchaseline;
    
    
    //             //Update reference count
    //     $ref_count = $this->productUtil->setAndGetReferenceCount($transaction_data['type']);
    //             //Generate reference number
    //     if (empty($transaction_data['ref_no'])) {
    //                 $transaction_data['ref_no'] = $this->productUtil->generateReferenceNumber($transaction_data['type'], $ref_count);
    //          }
    
    //     $transaction = Transaction::create($transaction_data);
    
        
       
    
    //    $this->productUtil->createOrUpdatePurchaseLines($transaction, $purchaseline, $currency_details, $enable_product_editing);
    
    //             //Add Purchase payments
    //    // $this->transactionUtil->createOrUpdatePaymentLines($transaction, $request->input('payment'));
    
    //    // $this->transactionUtil->updatePaymentStatus($transaction->id, $transaction->final_total);
    
    //    $this->productUtil->adjustStockOverSelling($transaction);
    
    //    $this->transactionUtil->activityLog($transaction, 'added from imports');
    
    //    PurchaseCreatedOrModified::dispatch($transaction);
    
    //     DB::commit();
    
    
    //     return response()->json(['success' => true]);
    // }



}




