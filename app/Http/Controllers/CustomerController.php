<?php

namespace App\Http\Controllers;

use Otp;
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

class CustomerController extends Controller
{
    public function signup(Request $request)
    {
        $this->validator($request->all())->validate();

        // Check if customer exist, for returned customer
        $phone = str_replace("+", "", PhoneNumber::make($request->phone, 'TZ')->formatE164());

        $customer = Customer::where('phone', $phone)->first();

        if ($customer) {
            if (!$customer->verified) {
                // Sending an activation code this is the returned customer
                $otp = $this->generateOtp($customer->phone);
                $request->session()->put('customer', $customer);

                event(new CustomerRegistered($otp, $customer));
                return response()->json(['status' => 'success', 'message' => trans('customer.welcome')]);

                // return redirect()->route('verify.customer', ['phone' => $customer->phone]);
            }
            event(new CustomerVerified($customer));
            return response()->json(['status' => 'success', 'message' => trans('customer.welcome')]);
            // return redirect('welcome')->with('status', trans('customer.welcome'));
        } else {
            $customer = $this->create($request->all());
            $request->session()->put('customer', $request->all());

            event(new CustomerRegistered($this->generateOtp($phone), $customer));

            return response()->json(['status' => 'success', 'message' => trans('customer.welcome')]);

            // return redirect()->route('verify.customer', ['phone' => $customer->phone]);
        }
    }

    public function customer($phone)
    {
        return view('verify', ['phone' => $phone]);
    }

    public function generateOtp($owner)
    {
        $otp = new Otp();
        return $otp->generate($owner, 6, 15);;
    }

    public function verify(Request $request)
    {
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
     *
     * @param  array  $data
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
     *
     * @param  array  $data
     * @return \App\Customer
     */
    protected function create(array $data)
    {
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

    public function resend(Request $request)
    {
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

    public function admin()
    {
        $customers = DB::table('customers')->latest()->simplePaginate(15);
        // dd($customers->all());
        return view('admin', ['customers' => $customers]);
    }

    public function exportFrom(Request $request)
    {
        return (new CustomersFromExport($request->from, $request->to))->download('customers.xlsx');
    }

    public function exportCustomer(Request $request)
    {
        return (new CustomerExport($request->id))->download('customer.xlsx');
    }

    public function exportAll()
    {
        return Excel::download(new AllCustomersExport, 'customers.xlsx');
    }
}
