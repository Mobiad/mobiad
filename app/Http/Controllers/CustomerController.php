<?php

namespace App\Http\Controllers;

use App\GoodObject;
use App\SubscriberPhone;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Customer;
use Illuminate\Http\Request;
use App\Events\CustomerVerified;
use App\Events\CustomerRegistered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;

use App\Exports\CustomerExport;
use App\Exports\AllCustomersExport;
use App\Exports\CustomersFromExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller{


    public function signup(Request $request){


        // Check if customer exist, for returned customer
        $inputPhone=$request->phone;
        $inputPhone=str_replace("+", "",$inputPhone);
        $prefix = substr($inputPhone, 0, 3);
        if($prefix!='255'){
            $inputPhone=  '255'.substr($inputPhone, 1, strlen($inputPhone)-1 );
        }

        $request->phone = $inputPhone;
        $this->validator($request->all())->validate();

        $phone = str_replace("+", "", PhoneNumber::make($request->phone, 'TZ')->formatE164());


        $data = $request->all();
        $customer = Customer::create([
            'fullname' => $data['fullname'],
            'phone' => str_replace("+", "", PhoneNumber::make($data['phone'], 'TZ')),
            'businessname' => $data['businessname'],
            'businessdesc' => $data['businessdesc'],
            'subscription_period' => $data['subscription_period'],
            'subscriber_numbers' => $data['subscriber_numbers'],
            'tune' => $data['tune'],
            'voice' => $data['voice'],
            'ref' => $data['ref'],
            'terms_and_conditions' => $data['terms_and_conditions'] ? '1' : '0',
        ]);
        $request->session()->put('customer', $request->all());

        foreach ($data['numbers'] as $number) {

            $subscriberPhone=$number['phone'];
            $subscriberPhone=str_replace("+", "",$subscriberPhone);
            $prefix = substr($subscriberPhone, 0, 3);
            if($prefix!='255'){
                $subscriberPhone=  '255'.substr($subscriberPhone, 1, strlen($subscriberPhone)-1 );
            }
            Log::debug($subscriberPhone);

            SubscriberPhone::create([
                'customer_id' => $customer->id,
                'phone' => $subscriberPhone,
                'name' => $number['name'],
                'otp' => rand(111111,999999),
                'has_accepted_terms' => false,
                'is_activated' => false
            ]);
        }

        $subscribers = SubscriberPhone::where(['customer_id' => $customer->id])->get();
        foreach ($subscribers as $subscriber) {
            $message= $subscriber->otp." is your mobiad verification code";
            //NotificationController::sendSms($subscriber->phone,$message);
        }

        //event(new CustomerRegistered($this->generateOtp($phone), $customer));
        return response()->json(['status' => 'success', 'message' => trans('customer.welcome'), 'customer'=>$customer]);

    }

    public function getCustomerInfo(Request $request){
        $responseData['customer'] = Customer::where(['id'=>$request->input('customer_id')])
            ->with(['phones'])->first();
        return response()->json($responseData);
    }



    /*** Phone-OTP Verication **/
    public function showOtpForm(Request $request){
        return response()->view('verification')->cookie(
            'customer_id', $request->input('customer_id'), 3600, null, null, false, false
        );
    }

    public function showSingleVerification(Request $request){
        return response()->view('verification_single')->cookie(
            'phone_number', $request->input('phone_number'), 3600, null, null, false, false
        );
    }

    public function verifyPhoneOtp(Request $request){

        $phone = SubscriberPhone::where([
            'phone' => $request->input('phone_number')
        ])->latest()->first();

        if(!$phone){
            return  response()->json([ "message"=>"Phone not found"],412);
        }
        Log::debug($request->input('otp'));
        Log::debug( $phone->otp);

        if( $request->input('otp') != $phone->otp ){
            return  response()->json([ "message"=>"Wrong code"],412);
        }

        SubscriberPhone::where([
            'phone' => $request->input('phone_number')
        ])->update([
            'has_accepted_terms' => true
        ]);

        return response()->json(["message"=>"Success"]);
    }
    /*** [end] Phone-OTP Verication **/





    /***  Payment **/
    public function showPaymentForm(Request $request){
        return response()->view('payment')->cookie(
            'customer_id', $request->input('customer_id'), 3600, null, null, false, false
        );
    }

    public function  paymentInit(Request $request){

        Log::debug("Start payment init");
        Log::debug($request->all());
        $customer = Customer::where(['id'=>$request->input('customer_id')])->with(['phones'])->first();

        if(!$customer){
            return  response()->json([ "message"=>"Customer not found"],412);
        }

        $validator = Validator::make($request->all(), [
            'payment_phone' => 'required|phone:TZ',
        ]);

        if ($validator->fails()) {
            return  response()->json([ "message"=>$validator->errors()->first() ],412);
        }

        /** Subscription Reference */
        $reference = "MOB".$customer->id.dechex(time());
        $customer->subscription_reference =  $reference;
        $customer->save();

        /** Amount due */
        $amount = 15000 * $customer->subscription_period * count($customer->phones);

        /** Phone */
        $paymentPhone = $request->input('payment_phone');

        $status = $this->submitTransactionToCodeblock($amount,$reference,  $paymentPhone);
        if(!$status){
            return response()->json(["message"=>"Transaction failed, try again later or contact us now"],400);
        }

        Log::debug("End payment init, status: ".$status);

        return response()->json(["message"=>"Success"]);
    }
    /*** [End] Payment **/







    /*** **/
    public function customer($phone)
    {
        return view('verify', ['phone' => $phone]);
    }

    public function generateOtp($owner){
        $otp = new Otp();
        return $otp->generate($owner, 6, 15);;
    }

    public function verify(Request $request){
        $request->validate([
            'otp' => 'required|min:6',
        ]);

        $otp = new Otp();
        $phone = str_replace("+", "", PhoneNumber::make($request->phone, 'TZ')->formatE164());

        $customer = Customer::where('phone', $phone)->first();

        if ($customer) {
            $token = $otp->validate($customer->phone, $request->otp);

            if ($token->status) {
                $customer->update([
                    'verified' => true,
                    'phone_verified_at' => now(),
                ]);
                event(new CustomerVerified($customer));
                return redirect('welcome')->with('status', trans('customer.welcome'));
            } else {

                // Send new OTP code then flash message that otp hasbeen sent
                $newotp = $this->generateOtp($customer->phone);
                event(new CustomerRegistered($newotp, $customer));

                return redirect()->route('verify.customer', ['phone' => $customer->phone])->with('warning', 'Oops!, Looks like you entered expired token, A new verification code was sent to your phone number');
            }
        }
        return redirect('home')->with('warning', 'We don not have your records, please sign up');
    }

    /**
     * Get a validator for an incoming registration request.
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['phone'] = str_replace("+", "", PhoneNumber::make($data['phone'], 'TZ')->formatE164());

        return Validator::make($data, [
            'fullname' => 'required|max:255',
            'phone' => 'required|phone:TZ',
            'businessname' => 'required',
            'businessdesc' => 'required',
            'subscription_period' => 'required',
            'subscriber_numbers' => 'required',
            'tune' => 'required',
            'terms_and_conditions' => 'required',
        ]);
    }

    /**
     * Create a new customer instance after a valid registration.
     * @param array $data
     * @return \App\Customer
     */
    protected function create(array $data){
        return Customer::create([
            'fullname' => $data['fullname'],
            'phone' => str_replace("+", "", PhoneNumber::make($data['phone'], 'TZ')),
            'businessname' => $data['businessname'],
            'businessdesc' => $data['businessdesc'],
            'subscription_period' => $data['subscription_period'],
            'subscriber_numbers' => $data['subscriber_numbers'],
            'tune' => $data['tune'],
            'voice' => $data['voice'],
            'ref' => $data['ref'],
            'terms_and_conditions' => $data['terms_and_conditions'] ? '1' : '0',
        ]);
    }

    public function resend(Request $request){
        $phone = str_replace("+", "", PhoneNumber::make($request->phone, 'TZ')->formatE164());
        $customer = Customer::where('phone', $phone)->first();

        if ($customer) {
            # Check verification status
            if ($customer->verified) {
                # customer already verified
                event(new CustomerVerified($customer));
                return redirect('welcome')->with('status', trans('customer.welcome'));
            }
            event(new CustomerRegistered($this->generateOtp($customer->phone), $customer));

            return redirect()->route('verify.customer', ['phone' => $customer->phone])->with('status', 'Verification code was sent to your phone number');
        }

        return redirect('home')->with('status', 'We don not have your records, please sign up');
    }

    public function admin(){
        $customers = DB::table('customers')->latest()->simplePaginate(15);
        // dd($customers->all());
        return view('admin', ['customers' => $customers]);
    }

    public function exportFrom(Request $request){
        return (new CustomersFromExport($request->from, $request->to))->download('customers.xlsx');
    }

    public function exportCustomer(Request $request){
        return (new CustomerExport($request->id))->download('customer.xlsx');
    }

    public function exportAll(){
        return Excel::download(new AllCustomersExport, 'customers.xlsx');
    }



    /** Codeblocks API ***/
    public function submitTransactionToCodeblock($amount, $reference, $paymemntPhone){

        $payment = new GoodObject();
        $payment->amount = $amount;
        $payment->reference = $reference;
        $payment->account_id = Config::get('codeblocks.account_id') ;
        $payment->customer_msisdn = $paymemntPhone ;
        $payment->callback_url = Config::get('codeblocks.callback_url') ;

        $url =  Config::get('codeblocks.transaction_submission_url') ;
        $token = Config::get('codeblocks.token') ;
        $body =  json_encode($payment);

        $client = new Client();
        $options = [
            'connect_timeout' => 320,
            'read_timeout' => 320,
            'timeout' => 320,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ],
            'body' => $body,
        ];

        try{
            $response = $client->request('POST', $url, $options);
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }

        $responseData['status'] = $response->getStatusCode(); // 200
        $responseData['reason'] = $response->getReasonPhrase(); // OK
        $responseData['body'] = json_encode($response->getBody()); // OK

        if ($responseData['status'] != "201") {
            Log::error($responseData['reason']);
            Log::error("Transaction creation failed");
            return false;
        }

        return true;
    }

    public function confirmPayment(Request $request){


        Log::debug($request->all());
        $customer = Customer::where('subscription_reference',$request->input('reference'))->first();

        if($customer){
            if($request->input('status')== "SUCCESS"){
                $customer->is_paid = true;
                $customer->save();
                return response()->json(["success"=>true,"message"=>"Transaction confirmed"]);
            }else{
                Log::debug("Callback received but not paid");
                return response()->json(["success"=>false,"message"=>"Callback received but not paid"]);

            }
        }

        return response()->json(["success"=>false,"message"=>"Transaction NOT confirmed"]);

    }

    /*** Helper **/
    public function  simulateSubmission(Request $request){

        $payment = new GoodObject();
        $payment->amount = "1000";
        $payment->reference = "MOB5463635633W4";
        $payment->account_id = Config::get('codeblocks.account_id') ;
        $payment->customer_msisdn = "255712294253";
        $payment->callback_url = Config::get('codeblocks.callback_url') ;

        $url =  Config::get('codeblocks.transaction_submission_url') ;
        $token = Config::get('codeblocks.token') ;
        $body =  json_encode($payment);


        $client = new Client();
        $options = [
            'connect_timeout' => 320,
            'read_timeout' => 320,
            'timeout' => 320,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ],
            'body' => $body,
        ];

        return $options;

        try{
            $response = $client->request('POST', $url, $options);
        } catch (\Exception $e) {
            return [false, $e->getMessage()];
        }

        $responseData['status'] = $response->getStatusCode(); // 200
        $responseData['reason'] = $response->getReasonPhrase(); // OK
        $responseData['body'] = json_encode($response->getBody()); // OK

        if ($responseData['status'] != "201") {
            Log::error($responseData['reason']);
            Log::error("Transaction creation failed");
            return false;
        }

        return true;
    }


}
