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
use App\Events\PurchaseCreatedOrModified;

use Illuminate\Support\Facades\Http; 

use App\EuclPayment;

use PDF;

class RegController extends Controller
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
        return view('reg.index');
    }

    public function index2()
    {
        return view('reg.index2');
    }

    public function index3()
    {
        return view('reg.index3');
    }
    public function index4()
    {
        return view('reg.index4');
    }

    public function index5()
    {
        return view('reg.index5');
    }

    public function index6()
    {
        return view('reg.index6');
    }

    public function index7()
    {
        return view('reg.index7');
    }


    public function list()
    {
        return view('reg.list');
    }
    

    public function list2()
    {
        return view('reg.list2');
    }

    public function list3()
    {
        return view('reg.list3');
    }

    public function pdfTemplate()
    {
        return view('reg.pdftemplate');

        
    }

   
    public function verifyMeter(Request $request)
    {
        // Get the meter number from the request
        $meterNumber = $request->input('meternumber');

        $business_id = $request->session()->get('user.business_id');

        $business_details = Business::find($business_id);
        
        $dbpassword=$business_details->eucl_password;


        // The URL of the external API
        $url = 'https://10.20.120.128:443/prod/vendor.ws';

        // Data to be sent in the request body
        $data = [
            "request" => [
                "header" => [
                    "h0"  => "Vendor-WS",
                    "h1"  => "1.0.2",
                    "h2"  => "CC04",
                    "h3"  => "b51ab024-3c86-4a08-983a-6092e497c03f",
                    "h4"  => "ASID",
                    "h5"  => $dbpassword,
                    "h6"  => "RW4200010001000100020903",
                    "h7"  => "XPS 15",
                    "h8"  => "DB4N0Q1",
                    "h9"  => "38:f9:d3:5e:d7:2e",
                    "h10" => "192.168.8.111",
                    "h11" => "20210528132902",
                    "h12" => "Local Test Server",
                    "h13" => "1.0.0",
                    "h14" => "rw"
                ],
                "body" => [
                    [
                        "p0" => $meterNumber 
                    ]
                ]
            ]
        ];


        \Log::info("request".json_encode($data));


        // Send POST request to the external API
        $response = Http::withoutVerifying() // Bypass SSL verification
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $data); // Send the request with data

        // Check if the request was successful

      //  \Log::info("response  meter verfication".json_encode($data));


       
        if ($response->successful()) {
            $responseData = $response->json();

            \Log::info("=>>>>>>>>>>>>>>>".json_encode($response->json()));

            return response()->json([
                $responseData ?? 'No result found'
            ]);
        } else {
            return response()->json([
                'result' => 'Verification failed'
            ], 500);
        }
    }







    public function  verifyretry(Request $request)
    {
        // Get the meter number from the request
        $meterNumber = $request->input('meternumber');


        $business_id = $request->session()->get('user.business_id');

        $business_details = Business::find($business_id);
        $dbpassword=$business_details->eucl_password;


        // The URL of the external API
        $url = 'https://10.20.120.128:443/prod/vendor.ws';

        // Data to be sent in the request body
        $data = [
            "request" => [
                "header" => [
                    "h0"  => "Vendor-WS",
                    "h1"  => "1.0.2",
                    "h2"  => "PR06",
                    "h3"  => "b51ab024-3c86-4a08-983a-6092e497c03f",
                    "h4"  => "ASID",
                    "h5"  => $dbpassword,
                    "h6"  => "RW4200010001000100020903",
                    "h7"  => "XPS 15",
                    "h8"  => "DB4N0Q1",
                    "h9"  => "38:f9:d3:5e:d7:2e",
                    "h10" => "192.168.8.111",
                    "h11" => "20210528132902",
                    "h12" => "Local Test Server",
                    "h13" => "1.0.0",
                    "h14" => "rw"
                ],
                "body" => [
                    [
                        "p0" => $meterNumber
                    ]
                ]
            ]
        ];


        \Log::info("request".json_encode($data));


        // Send POST request to the external API
        $response = Http::withoutVerifying() // Bypass SSL verification
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $data); // Send the request with data

        // Check if the request was successful

       
        if ($response->successful()) {


            

            $responseData = $response->json();

            $data = json_encode($responseData);


            $response_data = json_decode($data, true); 

            \Log::info("response retry.....".json_encode($response_data));
        
           
            $responseObj = $response_data['response'];

            $body = $responseObj['body'][0];
            $vendor =  $body['p4'];
            $token =  $body['p30'];
            $tokenexplanation =  $body['p65'];
            $regulatory =  $body['p90'];

            $reciept_number =  $body['p14'];

            $unit= $body['p25'];
            $tamount=$body['p15'];

            $meternumber_fromreponse=$body['p0'];
            $metername=$body['p2'];

            $signature=$body['p62'];
            $internaldata=$body['p63'];
            $sdc_id=$body['p58'];
            $invoiceno=$body['p40'];
            $fullinvoiceno=$body['p60'];
            $mrc=$body['p41'];
            $eucltin=$body['p42'];
            $clienttin=$body['p43'];
            $tdate=$body['p59'];
            $tax=$body['p55'];
            $qrtext='https://myrra.rra.gov.rw/common/link/ebm/receipt/indexEbmReceiptData?Data='.$eucltin.'00'.$signature;


            
            
            
         //   \Log::info("uuid>>>>>>>>>>>>>>>".json_encode($meterNumber));

            
            $paymentdetails = EuclPayment::where('uuid', $meterNumber)->first();

          //  \Log::info("payment_details>>>>>>>>>>>>>>>".json_encode($paymentdetails));


            $paymentdetails->amount = $tamount;
            $paymentdetails->token = $token;
            $paymentdetails->meter_number = $meternumber_fromreponse;
            $paymentdetails->reciept_number = $reciept_number;
            $paymentdetails->vendor = $vendor;
            $paymentdetails->units = $unit;
            $paymentdetails->regulatory = $regulatory;
            $paymentdetails->tokenexplanation = $tokenexplanation;
            $paymentdetails->response = $response;
            $paymentdetails->status = '1';
            $paymentdetails->signature = $signature;
            $paymentdetails->sdc_id = $sdc_id;
            $paymentdetails->internaldata = $internaldata;
            $paymentdetails->invoiceno = $invoiceno;
            $paymentdetails->fullinvoiceno = $fullinvoiceno;
            $paymentdetails->eucltin = $eucltin;
            $paymentdetails->clienttin = $clienttin;
            $paymentdetails->mrc = $mrc;
            $paymentdetails->tdate = $tdate;
            $paymentdetails->qrtext = $qrtext;
            $paymentdetails->metername = $metername;
            $paymentdetails->tax = $tax;
            
            
            $paymentdetails->save();

            session()->flash('notification', [
                'type' => 'success',
                'msg' => 'Retry done successfully.'
            ]);


        // \Log::info("after save>>>>>>>>>>>>>>>".json_encode($paymentdetails));
        
 
            return response()->json([
                $responseData ?? 'No result found'
            ]);
        } else {
            return response()->json([
                'result' => 'Verification failed'
            ], 500);
        }
    }


    public function  verifycopy(Request $request)
    {
        // Get the meter number from the request
        $meterNumber = $request->input('meternumber');


        $business_id = $request->session()->get('user.business_id');

        $business_details = Business::find($business_id);
        
        $dbpassword=$business_details->eucl_password;


        // The URL of the external API
        $url = 'https://10.20.120.128:443/prod/vendor.ws';

        // Data to be sent in the request body
        $data = [
            "request" => [
                "header" => [
                    "h0"  => "Vendor-WS",
                    "h1"  => "1.0.2",
                    "h2"  => "RC07",
                    "h3"  => "b51ab024-3c86-4a08-983a-6092e497c03f",
                    "h4"  => "ASID",
                    "h5"  => $dbpassword,
                    "h6"  => "RW4200010001000100020903",
                    "h7"  => "XPS 15",
                    "h8"  => "DB4N0Q1",
                    "h9"  => "38:f9:d3:5e:d7:2e",
                    "h10" => "192.168.8.111",
                    "h11" => "20210528132902",
                    "h12" => "Local Test Server",
                    "h13" => "1.0.0",
                    "h14" => "rw"
                ],
                "body" => [
                    [
                        "p0" => $meterNumber
                    ]
                ]
            ]
        ];


        \Log::info("request".json_encode($data));


        // Send POST request to the external API
        $response = Http::withoutVerifying() // Bypass SSL verification
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $data); // Send the request with data

        // Check if the request was successful

       
        if ($response->successful()) {
            $responseData = $response->json();

           // \Log::info("=>>>>>>>>>>>>>>>".json_encode($response->json()));

            return response()->json([
                $responseData ?? 'No result found'
            ]);
        } else {
            return response()->json([
                'result' => 'Verification failed'
            ], 500);
        }
    }



    public function  login(Request $request)
    {
        
        
        $business_id = $request->session()->get('user.business_id');

        $business_details = Business::find($business_id);
        
        $dbpassword=$business_details->eucl_password;

    
    // \Log::info("dbpassword........... ".json_encode($dbpassword));


        // The URL of the external API
        $url = 'https://10.20.120.128:443/prod/vendor.ws';

       // https://pvs.reg.rw/prod/vendor.ws

        // Data to be sent in the request body
        $data = [
            "request" => [
                "header" => [
                    "h0"  => "Vendor-WS",
                    "h1"  => "1.0.2",
                    "h2"  => "VL00",
                    "h3"  => "b51ab024-3c86-4a08-983a-6092e497c03f",
                    "h4"  => "ASID",
                    "h5"  => $dbpassword,
                    "h6"  => "RW4200010001000100020903",
                    "h7"  => "XPS 15",
                    "h8"  => "DB4N0Q1",
                    "h9"  => "38:f9:d3:5e:d7:2e",
                    "h10" => "192.168.8.111",
                    "h11" => "20210528132902",
                    "h12" => "Local Test Server",
                    "h13" => "1.0.0",
                    "h14" => "rw"
                ],
                "body" => [
                    [
                        "p0" => 'login'  
                    ]
                ]
            ]
        ];


        //\Log::info("request info login".json_encode($data));


        // Send POST request to the external API
        $response = Http::withoutVerifying() // Bypass SSL verification
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $data); // Send the request with data

        // Check if the request was successful
        \Log::info("response........... ".json_encode($response));

       
        if ($response->successful()) {
            $responseData = $response->json();

            \Log::info("login  resposnes \n".json_encode($response->json()));

            return response()->json([
                $responseData ?? 'No result found'
            ]);
        } else {
            return response()->json([
                'result' => 'Verification failed'
            ], 500);
        }
    }



    public function  meterstatement(Request $request)
    {
        // Get the meter number from the request
        $meterNumber = $request->input('meternumber');

        $login_response =$this->login($request);

        $data = json_encode($login_response);


       // \Log::info("login response.....: ".$data);


        $response_data = json_decode($data, true); 

        $original_data = $response_data['original'][0]; 


        $responseObj = $original_data['response'];

        $body = $responseObj['body'][0];

        $sessionusername =  $body['p0'];;
        $sessionpassword =  $body['p1'];;

     //   \Log::info("sessionusername0: ".$sessionusername.", sessionpassword: ".$sessionpassword);



        // The URL of the external API
        $url = 'https://10.20.120.128:443/prod/vendor.ws';

        // Data to be sent in the request body
        $data = [
            "request" => [
                "header" => [
                    "h0"  => "Vendor-WS",
                    "h1"  => "1.0.2",
                    "h2"  => "MH08",
                    "h3"  => "b51ab024-3c86-4a08-983a-6092e497c03f",
                    "h4"  => $sessionusername,
                    "h5"  => $sessionpassword,
                    "h6"  => "RW4200010001000100020903",
                    "h7"  => "XPS 15",
                    "h8"  => "DB4N0Q1",
                    "h9"  => "38:f9:d3:5e:d7:2e",
                    "h10" => "192.168.8.111",
                    "h11" => "20210528132902",
                    "h12" => "Local Test Server",
                    "h13" => "1.0.0",
                    "h14" => "rw"
                ],
                "body" => [
                    [
                        "p0" => $meterNumber,
                        "p1" => 100
                    ]
                ]
            ]
        ];


     // \Log::info("request".json_encode($data));


        // Send POST request to the external API
        $response = Http::withoutVerifying() // Bypass SSL verification
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $data); // Send the request with data

        // Check if the request was successful

       
        if ($response->successful()) {
            $responseData = $response->json();

            \Log::info("response for vendor request \n".json_encode($response->json()));

            return response()->json([
                $responseData ?? 'No result found'
            ]);
        } else {
            return response()->json([
                'result' => 'Verification failed'
            ], 500);
        }
    }


    public function  accountstatement(Request $request)
    {
        // Get the meter number from the request
        $meterNumber = $request->input('meternumber');

        $login_response =$this->login($request);

        $data = json_encode($login_response);


       // \Log::info("data.....: ".$data);


        $response_data = json_decode($data, true); 

        $original_data = $response_data['original'][0]; 


        $responseObj = $original_data['response'];

        $body = $responseObj['body'][0];

        $sessionusername =  $body['p0'];;
        $sessionpassword =  $body['p1'];;

     //   \Log::info("sessionusername0: ".$sessionusername.", sessionpassword: ".$sessionpassword);



        // The URL of the external API
        $url = 'https://10.20.120.128:443/prod/vendor.ws';

        // Data to be sent in the request body
        $data = [
            "request" => [
                "header" => [
                    "h0"  => "Vendor-WS",
                    "h1"  => "1.0.2",
                    "h2"  => "VH09",
                    "h3"  => "b51ab024-3c86-4a08-983a-6092e497c03f",
                    "h4"  => $sessionusername,
                    "h5"  => $sessionpassword,
                    "h6"  => "RW4200010001000100020903",
                    "h7"  => "XPS 15",
                    "h8"  => "DB4N0Q1",
                    "h9"  => "38:f9:d3:5e:d7:2e",
                    "h10" => "192.168.8.111",
                    "h11" => "20210528132902",
                    "h12" => "Local Test Server",
                    "h13" => "1.0.0",
                    "h14" => "rw"
                ],
                "body" => [
                    [
                        "p0" => 100
                    ]
                ]
            ]
        ];


    //  \Log::info("request".json_encode($data));


        // Send POST request to the external API
        $response = Http::withoutVerifying() // Bypass SSL verification
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $data); // Send the request with data

        // Check if the request was successful

        \Log::info("response for vendor request \n".json_encode($response->json()));

        if ($response->successful()) {
            $responseData = $response->json();

            

          
            return response()->json([
                $responseData ?? 'No result found'
            ]);
        } else {
            return response()->json([
                'result' => 'Verification failed'
            ], 500);
        }
    }



    public function  vendorsummary(Request $request)
    {
        // Get the meter number from the request
        $meterNumber = $request->input('meternumber');

        $login_response =$this->login($request);

        $data = json_encode($login_response);


       // \Log::info("data.....: ".$data);


        $response_data = json_decode($data, true); 

        $original_data = $response_data['original'][0]; 


        $responseObj = $original_data['response'];

        $body = $responseObj['body'][0];

        $sessionusername =  $body['p0'];
        $sessionpassword =  $body['p1'];

     //   \Log::info("sessionusername0: ".$sessionusername.", sessionpassword: ".$sessionpassword);



        // The URL of the external API
        $url = 'https://10.20.120.128:443/prod/vendor.ws';

        // Data to be sent in the request body
        $data = [
            "request" => [
                "header" => [
                    "h0"  => "Vendor-WS",
                    "h1"  => "1.0.2",
                    "h2"  => "AS03",
                    "h3"  => "b51ab024-3c86-4a08-983a-6092e497c03f",
                    "h4"  => $sessionusername,
                    "h5"  => $sessionpassword,
                    "h6"  => "RW4200010001000100020903",
                    "h7"  => "XPS 15",
                    "h8"  => "DB4N0Q1",
                    "h9"  => "38:f9:d3:5e:d7:2e",
                    "h10" => "192.168.8.111",
                    "h11" => "20210528132902",
                    "h12" => "Local Test Server",
                    "h13" => "1.0.0",
                    "h14" => "rw"
                ],
                "body" => [
                    [
                        "p0" => 100
                    ]
                ]
            ]
        ];


      //\Log::info("request".json_encode($data));


        // Send POST request to the external API
        $response = Http::withoutVerifying() // Bypass SSL verification
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $data); // Send the request with data

        // Check if the request was successful

        \Log::info("response for vendor request \n".json_encode($response->json()));

        if ($response->successful()) {
            $responseData = $response->json();

          //  \Log::info("response for vendor request \n".json_encode($response->json()));

            return response()->json([
                $responseData ?? 'No result found'
            ]);
        } else {
            return response()->json([
                'result' => 'Verification failed'
            ], 500);
        }
    }


    public function  changepass(Request $request)
    {

        $business_id = request()->session()->get('user.business_id');

       
        $business_details = Business::find($business_id);
        
        $oldpassword=$business_details->eucl_password;

        $newpassword1 = $request->input('newpassword1');

        

        
        // The URL of the external API
        $url = 'https://10.20.120.129:443/test/vendor.ws';

        // Data to be sent in the request body
        $data = [
            "request" => [
                "header" => [
                    "h0"  => "Vendor-WS",
                    "h1"  => "1.0.2",
                    "h2"  => "CP02",
                    "h3"  => "b51ab024-3c86-4a08-983a-6092e497c03f",
                    "h4"  => "ASID",
                    "h5"  => $oldpassword,
                    "h6"  => "RW4200010001000100020903",
                    "h7"  => "XPS 15",
                    "h8"  => "DB4N0Q1",
                    "h9"  => "38:f9:d3:5e:d7:2e",
                    "h10" => "192.168.8.111",
                    "h11" => "20210528132902",
                    "h12" => "Local Test Server",
                    "h13" => "1.0.0",
                    "h14" => "rw"
                ],
                "body" => [
                    [
                        "p0" => $oldpassword,
                        "p1" => $newpassword1,
                        "p2" => $newpassword1
                    ]
                ]
            ]
        ];


        //\Log::info("request info login".json_encode($data));


        // Send POST request to the external API
        $response = Http::withoutVerifying() // Bypass SSL verification
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $data); // Send the request with data

        // Check if the request was successful

       
        if ($response->successful()) {
            $responseData = $response->json();

            //\Log::info("login  resposnes \n".json_encode($response->json()));

            
        
        $business_details->eucl_password = $newpassword1;
        $business_details->save(); // Save the changes to the database



            return response()->json([
                $responseData ?? 'No result found'
            ]);
        } else {
            return response()->json([
                'result' => 'Verification failed'
            ], 500);
        }
    }




    public function  paymentconfirmation(Request $request)
    {
        try{

        $business_id = request()->session()->get('user.business_id');

       
        $business_details = Business::find($business_id);
        
        $oldpassword=$business_details->eucl_password;

        $newpassword1 = $request->input('newpassword1');

        $meterNumber = $request->input('meternumber');

        $tamount = $request->input('tamount');

        $tdate=date("YmdHis");


        //create uuid

        $datauuid = random_bytes(16);
        $datauuid[6] = chr(ord($datauuid[6]) & 0x0f | 0x40);
        $datauuid[8] = chr(ord($datauuid[8]) & 0x3f | 0x80);
        $uuid= vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datauuid), 4));

      //  \Log::info("request info payment of token ..".json_encode($uuid));

        // The URL of the external API
        $url = 'https://10.20.120.128:443/prod/vendor.ws';

        // Data to be sent in the request body
        $paymentdata = [
            "request" => [
                "header" => [
                    "h0"  => "Vendor-WS",
                    "h1"  => "1.0.2",
                    "h2"  => "PC05",
                    "h3"  => $uuid,
                    "h4"  => "ASID",
                    "h5"  => $oldpassword,
                    "h6"  => "RW4200010001000100020903",
                    "h7"  => "XPS 15",
                    "h8"  => "DB4N0Q1",
                    "h9"  => "38:f9:d3:5e:d7:2e",
                    "h10" => "192.168.8.111",
                    "h11" => $tdate,
                    "h12" => "Local Test Server",
                    "h13" => "1.0.0",
                    "h14" => "rw"
                ],
                "body" => [
                    [
                        "p0" => $meterNumber,
                        "p1" => $tamount
                    ]
                ]
            ]
        ];


    //    \Log::info("request info payment of token ..".json_encode($data));


        // Send POST request to the external API
        $response = Http::withoutVerifying() // Bypass SSL verification
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $paymentdata); // Send the request with data

        // Check if the request was successful

      ///  \Log::info("response from payment of token..".json_encode($response->json()));

        if ($response->successful()) {

            session()->flash('notification', [
                'type' => 'success',
                'msg' => 'Token generated successfully.'
            ]);
            $responseData = $response->json();

            $data = json_encode($responseData);


            $response_data = json_decode($data, true); 

           // $original_data = $response_data['original'][0]; 


            $responseObj = $response_data['response'];

            $body = $responseObj['body'][0];
            $vendor =  $body['p4'];
            $token =  $body['p30'];
            $tokenexplanation =  $body['p65'];
            $regulatory =  $body['p90'];

            $reciept_number =  $body['p14'];

            $unit= $body['p25'];
            

            $userId = $request->session()->get('user.first_name');
            $user = User::select(DB::raw("CONCAT(surname, ' ', first_name, ' ', last_name) AS full_name"))
                        ->where('id', $userId)
                        ->first();

            if ($user) {
                $fullName = $user->full_name;
            } else {
                // Handle if user is not found
                $fullName = null;
            }

            \Log::info("fullName...".json_encode($userId));
            
            DB::beginTransaction();
            
            $payment_details['uuid'] = $uuid;
            $payment_details['amount'] = $tamount;
            $payment_details['token'] = $token;
            $payment_details['response'] = $data;
            $payment_details['meter_number'] = $meterNumber;
            $payment_details['units'] = $unit;
            $payment_details['regulatory'] = $regulatory;
            $payment_details['vendor'] = $vendor;
            $payment_details['tokenexplanation'] = $tokenexplanation;
            $payment_details['reciept_number'] = $reciept_number;
            $payment_details['request'] = json_encode($paymentdata);
            $payment_details['status'] = '1';
            $payment_details['created_by'] = $fullName; // Corrected variable name
            
            $payment_details = EuclPayment::create($payment_details);

            DB::commit();

             // Prepare data for the PDF
        
        return response()->json([
                $responseData ?? 'No result found'
            ]);
        } else {

            DB::beginTransaction();

            //$payment_details['business_id'] = $business_id;
            $payment_details['uuid'] = $uuid;
            $payment_details['amount'] = $tamount;
            $payment_details['token'] = '';
            $payment_details['response'] = '';
            $payment_details['request'] = json_encode($paymentdata);
            $payment_details['meter_number'] = $meterNumber;   
            $payment_details['units'] = '';    
            $payment_details['regulatory'] = '';
            $payment_details['vendor'] = '';
            $payment_details['tokenexplanation'] = '';
            $payment_details['reciept_number'] = '';
            $payment_details['status'] = '0';
            

            
           // $payment_details['created_by'] = $request->session()->get('user.id');

            $payment_details = EuclPayment::create($payment_details);

            DB::commit();
            return response()->json([
                'result' => 'Verification failed'
            ], 500);
        }}catch (\Exception $e) {

            DB::beginTransaction();
          //  \Log::error('Error fetching payment data: ' . $e->getMessage());

            $payment_details['uuid'] = $uuid;
            $payment_details['amount'] = $tamount;
            $payment_details['token'] = '';
            $payment_details['response'] = '';
            $payment_details['request'] = json_encode($paymentdata);
            $payment_details['meter_number'] = $meterNumber;   
            $payment_details['units'] = '';    
            $payment_details['regulatory'] = '';
            $payment_details['vendor'] = '';
            $payment_details['tokenexplanation'] = '';
            $payment_details['reciept_number'] = '';
            $payment_details['status'] = '0';
            $payment_details = EuclPayment::create($payment_details);
            DB::commit();

            return response()->json([
                'result' => 'Verification failed'.$e->getMessage()
            ], 500);


        }
    }





    public function getPaymentData()
{
    // Fetching from the EuclPayment model with server-side processing
    $payments = EuclPayment::select(['id', 'uuid', 'amount', 'units', 'meter_number', 'vendor', 'tokenexplanation','token','reciept_number', 'created_at','created_by'])
        ->orderBy('id', 'desc'); // Ensure ordering is applied here

    // Process the data with DataTables
    return DataTables::of($payments)
    ->addColumn('action', function($row) {
        $btn = '<a href="/reg/edit/'.$row->id.'" class="edit btn btn-primary btn-sm">Show</a>';
        $btn .= ' <a href="javascript:void(0);" onclick="retryFunction(\''.$row->uuid.'\')" class="btn btn-success btn-sm">Retry</a>';
        $btn .= ' <a href="/reg/print/'.$row->id.'" class="delete btn btn-secondary btn-sm">Print</a>';
        return $btn;
    })
    ->rawColumns(['action'])
    ->make(true);

}



public function getBalanceData()
{
    $business_id=1;
    
    $balances = DB::table('vwbalance')
    ->select(['id AS id','contact_id AS contact_id', 'business_name AS name', 'balance'])
    ->where('business_id', $business_id) // Filter by business_id
    ->orderBy('contact_id', 'desc')
    ->get();
 

    // Process the data with DataTables
    return DataTables::of($balances)
    ->addColumn('action', function($row) {
        $btn = '<a href="/liststatment/'.$row->id.'" class="view btn btn-primary btn-sm">View</a>'; // Pass the contact ID
                
        $btn .= ' <a href="#" class="edit btn btn-warning btn-sm" data-id="'.$row->id.'">Load Credit</a>'; // Add data-id here
        return $btn;
    })
        ->rawColumns(['action'])
        ->make(true);
}








public function getstatement($contactId)
{
    $business_id=1;
    
    // Fetching debit, credit, and description for the given contact_id
    $balances = DB::table('account_eucl')
        ->select(['id AS id','contact_id AS contact_id', 'credit', 'debit', 'description','created_at'])
        ->where('contact_id', $contactId)
        ->orderBy('id', 'desc')
        ->get(); // Execute the query to get the data

    // Pass the fetched data to the view
    return view('reg.list3', compact('balances'));
}










public function printToken($id)
{
    $payment = EuclPayment::find($id);
    if (!$payment) {
        abort(404); // Handle case where payment is not found
    }
    $pdf = PDF::loadView('reg.printtoken', compact('payment'));
   
    return $pdf->download('payment_token_' . $payment->uuid . '.pdf');

   //return view('reg.printtoken', compact('payment'));

}


public function consumer_check(Request $request)
{
    // Get the meter number from the request
    $meterNumber = $request->input('meter_number');

    $business_id = 1;

    $business_details = Business::find($business_id);
    
    $dbpassword=$business_details->eucl_password;


    // The URL of the external API
    $url = 'https://10.20.120.129:443/test/vendor.ws';

    
    // Data to be sent in the request body
    $data = [
        "request" => [
            "header" => [
                "h0"  => "Vendor-WS",
                "h1"  => "1.0.2",
                "h2"  => "CC04",
                "h3"  => "b51ab024-3c86-4a08-983a-6092e497c03f",
                "h4"  => "ASID",
                "h5"  => '6o3xcledmsr9r',
                "h6"  => "RW4200010001000100020903",
                "h7"  => "XPS 15",
                "h8"  => "DB4N0Q1",
                "h9"  => "38:f9:d3:5e:d7:2e",
                "h10" => "192.168.8.111",
                "h11" => "20210528132902",
                "h12" => "Local Test Server",
                "h13" => "1.0.0",
                "h14" => "rw"
            ],
            "body" => [
                [
                    "p0" => $meterNumber 
                ]
            ]
        ]
    ];


    \Log::info("response  meter verfication".json_encode($data));

    

    // Send POST request to the external API
    $response = Http::withoutVerifying() // Bypass SSL verification
        ->withHeaders([
            'Content-Type' => 'application/json',
        ])
        ->post($url, $data); // Send the request with data

    // Check if the request was successful

    \Log::info("response  meter verfication".json_encode($response));


   
    if ($response->successful()) {
        $responseData = $response->json();

        $responseData = $response->json();

            $data = json_encode($responseData);


            $response_data = json_decode($data, true); 

           // $original_data = $response_data['original'][0]; 


            $responseObj = $response_data['response'];

            $body = $responseObj['body'][0];
            $MeterName =  $body['p2'];
            $min =  $body['p7'];
            $max =  $body['p8'];
            $metertype =  $body['p4'];
            
        $response = [
            "MeterNumber" => $meterNumber,
            "MeterName" => $MeterName,
            "MinAmount" => $min,
            "MaxAmount" => $max,
            "Type" => $metertype
        ];

        // Return the response as JSON
        return response()->json($response, 200);
    } else {
        return response()->json([
            'result' => 'Verification failed'
        ], 500);
    }
}






public function  buytoken(Request $request)
{
    try{

    $business_id = 1;
   
    $business_details = Business::find($business_id);
    
    $oldpassword=$business_details->eucl_password;

    
    $meterNumber = $request->input('meter_number');

    $contactId = $request->input('contact_id');

    $client_secret = $request->input('client_secret');

    $tamount = $request->input('amount');

    $tdate=date("YmdHis");

    
    

    $balance = DB::table('vwbalance')
    ->where('contact_id', $contactId)
    ->where('business_id', $business_id)
    ->value('balance');

    $actual_contact_id = DB::table('vwbalance')
    ->where('contact_id', $contactId)
    ->where('business_id', $business_id)
    ->value('id');

    if($tamount>$balance){

        $response = [
            "message" => 'Amount exceeds what  you have in your account please top up to continue',
            "account_balance" => $balance,
            "status" => 'success',
            "date" => $tdate
        ];

     return response()->json($response, 200);
   
    }

    
    //supplier_business_name

    
    $supplierBusinessName = DB::table('contacts')
    ->where('id', $contactId)
    ->value('supplier_business_name');



    //create uuid

    $datauuid = random_bytes(16);
    $datauuid[6] = chr(ord($datauuid[6]) & 0x0f | 0x40);
    $datauuid[8] = chr(ord($datauuid[8]) & 0x3f | 0x80);
    $uuid= vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datauuid), 4));

  //  \Log::info("request info payment of token ..".json_encode($uuid));

    // The URL of the external API
    $url = 'https://10.20.120.129:443/test/vendor.ws';

   // https://10.20.120.128:443/prod/vendor.ws


    // Data to be sent in the request body
    $paymentdata = [
        "request" => [
            "header" => [
                "h0"  => "Vendor-WS",
                "h1"  => "1.0.2",
                "h2"  => "PC05",
                "h3"  => $uuid,
                "h4"  => "ASID",
                "h5"  => '6o3xcledmsr9r',
                "h6"  => "RW4200010001000100020903",
                "h7"  => "XPS 15",
                "h8"  => "DB4N0Q1",
                "h9"  => "38:f9:d3:5e:d7:2e",
                "h10" => "192.168.8.111",
                "h11" => $tdate,
                "h12" => "Local Test Server",
                "h13" => "1.0.0",
                "h14" => "rw"
            ],
            "body" => [
                [
                    "p0" => $meterNumber,
                    "p1" => $tamount
                ]
            ]
        ]
    ];


   

    // Send POST request to the external API
    $response = Http::withoutVerifying() // Bypass SSL verification
        ->withHeaders([
            'Content-Type' => 'application/json',
        ])
        ->post($url, $paymentdata); // Send the request with data

    // Check if the request was successful

    //\Log::info("response...from api.".json_encode($response->json()));

   

    if ($response->successful()) {

        
        $responseData = $response->json();

        $data = json_encode($responseData);

        
        $response_data = json_decode($data, true); 

       
        $responseObj = $response_data['response'];

        $header = $responseObj['header'];
        
        $status =  $header['h5'];
        $message_log =  $header['h6'];
        
        if($status=='0'){


        $body = $responseObj['body'][0];
        $vendor =  $body['p4'];
        $token =  $body['p30'];
        $tokenexplanation =  $body['p65'];
        $regulatory =  $body['p90'];
        $reciept_number =  $body['p14'];
        $unit= $body['p25'];
    
        
        DB::beginTransaction();
        
        $payment_details['uuid'] = $uuid;
        $payment_details['amount'] = $tamount;
        $payment_details['token'] = $token;
        $payment_details['response'] = $data;
        $payment_details['meter_number'] = $meterNumber;
        $payment_details['units'] = $unit;
        $payment_details['regulatory'] = $regulatory;
        $payment_details['vendor'] = $vendor;
        $payment_details['tokenexplanation'] = $tokenexplanation;
        $payment_details['reciept_number'] = $reciept_number;
        $payment_details['request'] = json_encode($paymentdata);
        $payment_details['status'] = '1';
        $payment_details['created_by'] = $supplierBusinessName; // Corrected variable name
        $payment_details['status'] = '1';
        
        $payment_details = EuclPayment::create($payment_details);

        DB::table('account_eucl')->insert([
           'contact_id' => $actual_contact_id,
            'debit' => $tamount, 
            'credit' => 0,
            'description' => 'purchase of token  with '.$token.' and meter number '.$meterNumber,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::commit();

        $newbalance = DB::table('vwbalance')
    ->where('contact_id', $contactId)
    ->where('business_id', $business_id)
    ->value('balance');


        $response = [
            "uuid" => $uuid,
            "meter_number" => $meterNumber,
            "amount" => $tamount,
            "token" => $token,
            "regulatory" => $regulatory,
            "details" => $tokenexplanation,
            "units" => $unit,
            "status" => 'success',
            "receipt_number" => $reciept_number,
            "account_balance" => $newbalance,
            "customer" => $supplierBusinessName,
            "date" => $tdate
        ];

    }else{

        $response = [
            
            "message" => $message_log,
            "status" => 'Error',
            "date" => $tdate
        ];

    }
        

    
    return response()->json($response, 200);
    } else {

        DB::beginTransaction();

        //$payment_details['business_id'] = $business_id;
        $payment_details['uuid'] = $uuid;
        $payment_details['amount'] = $tamount;
        $payment_details['token'] = '';
        $payment_details['response'] = '';
        $payment_details['request'] = json_encode($paymentdata);
        $payment_details['meter_number'] = $meterNumber;   
        $payment_details['units'] = '';    
        $payment_details['regulatory'] = '';
        $payment_details['vendor'] = '';
        $payment_details['tokenexplanation'] = '';
        $payment_details['reciept_number'] = '';
        $payment_details['status'] = '0';
        
       // $payment_details['created_by'] = $request->session()->get('user.id');

        $payment_details = EuclPayment::create($payment_details);

        $response = [
            'message' => 'Unable to generate token ',
            "status" => 'error',
            "account_balance" => $balance,
            "date" => $tdate
        ];

        

        DB::commit();
        return response()->json($response, 200);
    }}catch (\Exception $e) {

    //     DB::beginTransaction();
        \Log::error('Error fetching payment data: ' . $e->getMessage());

        $payment_details['uuid'] = $uuid;
        $payment_details['amount'] = $tamount;
        $payment_details['token'] = '';
        $payment_details['response'] = '';
        $payment_details['request'] = json_encode($paymentdata);
        $payment_details['meter_number'] = $meterNumber;   
        $payment_details['units'] = '';    
        $payment_details['regulatory'] = '';
        $payment_details['vendor'] = '';
        $payment_details['tokenexplanation'] = '';
        $payment_details['reciept_number'] = '';
        $payment_details['status'] = '0';
        $payment_details = EuclPayment::create($payment_details);
        DB::commit();

        $response = [
            'message' => 'Unable to generate token'.$e->getMessage(),
            "status" => 'error',
            "date" => $tdate
        ];
        return response()->json($response, 200);


    }
}


public function saveCredit(Request $request)
{
    try{ 
    
    $request->validate([
        'contact_id' => 'required',
        'amount' => 'required|numeric|min:0',
        'description' => 'nullable|string|max:200', // Add description validation if needed
    ]);


    

    // Insert the credit amount into the account_eucl table
    DB::table('account_eucl')->insert([
        'contact_id' => $request->contact_id,
        'credit' => $request->amount, // Set the credit column
        'debit' => 0, // Set debit to null or 0 if required
        'description' => $request->description ?? null,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // Return a success response
    return response()->json(['success' => true, 'message' => 'Credit loaded successfully!']);

}catch (\Exception $e) {

    \Log::error('Error fetching payment data: ' . $e->getMessage());


}
}

   
}
    
    



    

