<?php

namespace App\Http\Controllers;

use App\Customer;
use App\GoodObject;
use App\SubscriberPhone;
use Barryvdh\DomPDF\Facade as PDF;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

class TermsController extends Controller{


    public function downloadTerm(){

        $customer = Customer::latest()->first();
        return view('terms.terms',[
            "date" =>Carbon::now()->toDateString(),
            "customer" =>Customer::latest()->first(),
            "phones" =>SubscriberPhone::where('customer_id',$customer->id)->get(),
        ]);

        $pdf = PDF::loadView('terms.terms', [
            "date" =>Carbon::now()->toDateString(),
            "customer" =>Customer::latest()->first(),
            "phones" =>SubscriberPhone::where('customer_id',$customer->id)->get(),
        ]);
        return $pdf->download('terms.pdf');
    }

    public function exportCustomerContract(Request $request){

        $customer = Customer::find($request->input('id'));
//        return view('terms.terms',[
//            "date" =>Carbon::now()->toDateString(),
//            "customer" =>Customer::latest()->first(),
//            "phones" =>SubscriberPhone::where('customer_id',$customer->id)->get(),
//        ]);

        $pdf = PDF::loadView('terms.terms', [
            "date" =>Carbon::now()->toDateString(),
            "customer" =>Customer::latest()->first(),
            "phones" =>SubscriberPhone::where(['customer_id'=>$customer->id,'has_accepted_terms'=>true])->get(),
        ]);
        return $pdf->download('terms.pdf');
    }

}


