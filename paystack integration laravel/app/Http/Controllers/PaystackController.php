<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaystackController extends Controller
{
    public function callback(Request $request)
    {
        //dd($request->all());
        $reference = $request->reference;
        $secret_key = env('PAYSTACK_SECRET_KEY');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $secret_key",
                "Cache-Control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        //dd($response);
        $meta_data = $response->data->metadata->custom_fields;
        if($response->data->status == 'success')
        {
            $obj = new Payment;
            $obj->payment_id = $reference;
            $obj->product_name = $meta_data[0]->value;
            $obj->quantity = $meta_data[1]->value;
            $obj->amount = $response->data->amount / 100;
            $obj->currency = $response->data->currency;
            $obj->payment_status = "Completed";
            $obj->payment_method = "Paystack";
            $obj->save();
            return redirect()->route('success');
        } else {
            return redirect()->route('cancel');
        }
    }

    public function success()
    {
        return "Payment is successful";
    }
    public function cancel()
    {
        return "Payment is cancelled";
    }
}