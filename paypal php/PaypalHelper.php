<?php

class Paypal
{

    public function generateToken()
    {

        $Client_ID = 'AeC-x11R6qznYPXPQdEKt9kuqlQSpgqePGDbIFcCVzN5lCE8gUGbPRasyJQHQqJCKUhL3IMV9ZeEbCFW';
        $secret =  'EONsSKml_b7bwU0Zej0-LKXmHGUHMqTMPX6w3JvuEOkFDWLsjboWqlBfgvHXf-uKlPW5JfXAXjkl2Vmm';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api-m.sandbox.paypal.com/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_USERPWD, $Client_ID  . ':' . $secret );
        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return  404 ;
        }
        curl_close($ch);
        $response = json_decode($response, true);
        return $response['access_token'] ;
        
    }

    public function createOrder($data)
    {
        $token = $this->generateToken() ;
        if($token =='404') 
        return ['code'=>'404','message'=>'not found'];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api-m.sandbox.paypal.com/v1/payments/payment');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = "Authorization: Bearer $token";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return ['code'=>'404','message'=>curl_error($ch)];
        }
        curl_close($ch);
        $result = json_decode($result);
        return ['code'=>'200','message'=>$result->links[1]->href??''];
        
    }
   
}