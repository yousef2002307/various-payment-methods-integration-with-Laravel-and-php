<?php
 
include('PaypalHelper.php');
$paypal = new Paypal();
 $price =  12 ;
 $data ='{ "intent": "sale", "payer": { "payment_method": "paypal" }, "transactions":  [ { "amount": { "total": "'.$price.'", "currency": "USD"  }  } ], "redirect_urls": { "return_url": "https://localhost/paypal/return.php", "cancel_url": "https://localhost/paypal/cancel.php" } }' ;
 $result = $paypal->createOrder($data) ; 
echo '<a href="'.$result['message'].'">link</a> ';
