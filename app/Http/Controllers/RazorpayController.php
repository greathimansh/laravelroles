<?php

namespace App\Http\Controllers;

use App\Http\ApiResponse;
use Illuminate\Http\Request;
use Razorpay\Api\Api;


class RazorpayController extends Controller
{
    public function listPayment(Request  $req ){
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $data = $api->payment->all()->toArray();
        
        return ApiResponse::success($data);
    }

    public function getPayment(Request  $req ){
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $paymentId = $req->payment_id;
        $data = $api->payment->fetch($paymentId);
        
        return ApiResponse::success($data);
    }

    public function createPayment(Request $req)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        // $amount = $req->amount;
        // $currency = $req->currency;
        // $email = $req->email;
        // $contact = $req->contact;
        // $order_id = $req->order_id;
        // $method = $req->method;
        // $card = $req->card;
        // $cvv = $req->cvv;
        // $expiry_month = $req->expiry_month;
        // $expiry_year = $req->expiry_year;
        // $name = $req->name;
        
        $data = $api->payment->createPaymentJson(array('amount' => 100,'currency' => 'INR','email' => 'gaurav.kumar@example.com','contact' => '9123456789','order_id' => 'order_K8fFiqxfybigei','method' => 'card','card' => array('number' => '4854980604708430','cvv' => '123','expiry_month' => '12','expiry_year' => '21','name' => 'Gaurav Kumar')));
        // $data = $api->payment->createPaymentJson(array('amount' => $amount,'currency' => $currency,'email' => $email,'contact' => $contact,'order_id' => $order_id,'method' => $method,'card' => array('number' => $card,'cvv' => $cvv,'expiry_month' => $expiry_month,'expiry_year' => $expiry_year,'name' => $name)));

        return ApiResponse::success($data);


    }
}