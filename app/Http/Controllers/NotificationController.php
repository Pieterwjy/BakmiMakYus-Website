<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Notification;
use App\Models\Order;


class NotificationController extends Controller
{
    public function handleNotification(Request $request)
    {
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');

        try {
            $notif = new Notification();
        }
        catch (\Exception $e) {
            exit($e->getMessage());
        }
        
        $notif = $notif->getResponse();
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;
        
        $order = Order::findOrFail($order_id);

        if ($transaction == 'capture') {
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    // TODO set payment status in merchant's database to 'Challenge by FDS'
                    // TODO merchant should decide whether this transaction is authorized or not in MAP
                    echo "Transaction order_id: " . $order_id ." is challenged by FDS";
                    $order->status = 'Challenge by FDS';
                } else {
                    // TODO set payment status in merchant's database to 'Success'
                    echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
                    $order->status = 'Success';
                    $order->order_status = 'Diteruskan Ke Koki';
                }
            }
        } else if ($transaction == 'settlement') {
            // TODO set payment status in merchant's database to 'Settlement'
            echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
            $order->status = 'Settlement';
            $order->order_status = 'Diteruskan Ke Koki';
        } else if ($transaction == 'pending') {
            // TODO set payment status in merchant's database to 'Pending'
            echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
            $order->status = 'Pending';
        } else if ($transaction == 'deny') {
            // TODO set payment status in merchant's database to 'Denied'
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
            $order->status = 'Denied';
        } else if ($transaction == 'expire') {
            // TODO set payment status in merchant's database to 'expire'
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
            $order->status = 'Expired';
        } else if ($transaction == 'cancel') {
            // TODO set payment status in merchant's database to 'Denied'
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
            $order->status = 'Canceled';
        }
        $order->save();


        // // $notification = new Notification($request->notification);

        // $requestData = json_decode($request->getContent());

        // // dd($requestData);
        // $rawPayload = $request->getContent();
        // error_log('Raw Payload: ' . $rawPayload);

        // $notif = new Notification();
        // error_log(json_encode($notif));
        // $transaction = $notif->transaction_status;
        // $fraud = $notif->fraud_status;
        // $orderId = $notif->order_id;
    
        // error_log("Order ID $orderId: transaction status = $transaction, fraud status = $fraud");
    
        // $order = Order::findOrFail($orderId);
    
        // if ($transaction == 'capture') {
        //     if ($fraud == 'challenge') {
        //         // Set payment status in merchant's database to 'challenge'
        //         $order->payment_status = 'challenge';
        //         return response()->json(['status' => 'success']);
        //     } else if ($fraud == 'accept') {
        //         // Set payment status in merchant's database to 'success'
        //         $order->payment_status = 'success';
        //         return response()->json(['status' => 'success']);
        //     }
        // } else if ($transaction == 'cancel') {
        //     if ($fraud == 'challenge' || $fraud == 'accept') {
        //         // Set payment status in merchant's database to 'failure'
        //         $order->payment_status = 'failure';
        //         return response()->json(['status' => 'success']);
        //     }
        // } else if ($transaction == 'deny') {
        //     // Set payment status in merchant's database to 'failure'
        //     $order->payment_status = 'failure';
        //     return response()->json(['status' => 'success']);
        // }
    
        // $order->save(); // Save the changes




        $serverKey = config('midtrans.server_key');
    //     $hashed = hash("sha512",$request->order_id.$request->status_code.$request->gross_amount.$serverKey);
    //     if($hashed == $request->signature_key){
    //         // $notif = new Notification($request->all());
    //         // error_log(json_encode($notif));
    //         // $transaction = $notif->transaction_status;
    //         // $fraud = $notif->fraud_status;
    //         // $orderId = $notif->order_id;
        
    //         error_log("Order ID $orderId: transaction status = $transaction, fraud status = $fraud");
        
    //         $order = Order::findOrFail($orderId);
        
    //         if ($request->transaction_status == 'capture') {
    //             if ($fraud == 'challenge') {
    //                 // Set payment status in merchant's database to 'challenge'
    //                 $order->payment_status = 'challenge';
    //             } else if ($fraud == 'accept') {
    //                 // Set payment status in merchant's database to 'success'
    //                 $order->payment_status = 'success';
    //             }
    //         } else if ($transaction == 'cancel') {
    //             if ($fraud == 'challenge' || $fraud == 'accept') {
    //                 // Set payment status in merchant's database to 'failure'
    //                 $order->payment_status = 'failure';
    //             }
    //         } else if ($transaction == 'deny') {
    //             // Set payment status in merchant's database to 'failure'
    //             $order->payment_status = 'failure';
    //         }
        
    //         $order->save(); // Save the changes
        
    //         // return response()->json(['status' => 'success']);
    //     }else{
    //         return response()->json(['status' => 'error']);
    //     }

    // $payload = json_decode($request->getContent(), true); // Decode JSON payload
    // error_log($payload);
    // $transaction = $payload['transaction_status'];


    
    }
}