<?php

namespace App\Http\Controllers\UserControllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stripe\Charge;
use Stripe\Refund;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function charge(Request $request)
    {
        $request->validate([
            'post1' => 'required',
            'post2' => 'required',
            'price' => 'required',
            'table' => 'required',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $token = $request->input('stripeToken');

        DB::beginTransaction();
        $chargeId = null;
        try {
            // Create a customer in Stripe
            $customer = \Stripe\Customer::create([
                'name' => Auth::user()->first_name . " " . Auth::user()->last_name,
                'email' => Auth::user()->email,
                'source' => $token,
            ]);

            // Create the charge with the customer ID
            $charge = Charge::create([
                'customer' => $customer->id,
                'amount' => $request->input('price') * 100,
                'currency' => 'PKR',
                'description' => 'Chat Purchase , Customer ID - ' . Auth::user()->id,
            ]);

            $chargeId = $charge->id;

            // Handle the result of the charge
            if ($charge) {
                // Transaction data for the customer
                Transaction::create([
                    'user_id' => Auth::user()->id,
                    'post1' => $request->input('post1'),
                    'post2' => $request->input('post2'),
                    'amount' => $request->input('price'),
                    'table' => $request->input('table'),
                    'transaction_id' => $chargeId,
                ]);

                // Call the createChat method
                $createChatStatus = $this->createChat($request->input('post1'), $request->input('post2'), $request->input('table'));

                if ($createChatStatus == 200) {
                    DB::commit();
                    return redirect()->route('chat.get')->with('success', 'Payment successful. You can now chat with the selected person.');
                }
            }
        } catch (\Exception $e) {

            return $e;
            // Refund the payment using the stored Charge ID
            if ($chargeId) {
                try {
                    Stripe::setApiKey(env('STRIPE_SECRET'));
                    $refund = Refund::create([
                        'charge' => $chargeId,
                    ]);

                    // Check if the refund was successful
                    if ($refund->status === 'succeeded') {
                        DB::rollBack();
                        return redirect()->back()->with('error_msg', 'Payment unsuccessful! Refunded.');
                    }
                } catch (\Exception $refundException) {
                    DB::rollBack();
                    return redirect()->back()->with('error_msg', 'Payment unsuccessful! Refund failed.');
                }
            }

            DB::rollBack();
            return redirect()->back()->with('error_msg', 'Payment unsuccessful!');
        }

        // If control reaches here, it means createChat was not successful
        DB::rollBack();
        return redirect()->back()->with('error_msg', 'Payment unsuccessful. Chat creation failed.');
    }

    public function createChat($post1, $post2, $table)
    {

        $url = 'https://us-central1-missplaced-1780b.cloudfunctions.net/createChatDocument';

        $user1 = DB::table($table)->where('id', $post1)->first();
        $user2 = DB::table($table)->where('id', $post2)->first();

        $fcm1 = User::find($user1->user_id);
        $fcm2 = User::find($user2->user_id);
        if ($user1 && $user2) {
            // Example data to send to the Firebase Cloud Function
            $data = [
                'userIds' => [$user1->user_id, $user2->user_id],
                'tokens' => [$fcm1->fcm_token, $fcm2->fcm_token],
                'postIds' => [$post1, $post2],
                'databaseTable' => $table,

            ];

            try {
                $response = Http::post($url, $data);

                // Handle the response from the Firebase Cloud Function
                $statusCode = $response->status();
                $body = $response->json();

                // Check for errors
                if ($statusCode != 200) {
                    // Handle error based on status code and response body
                    if ($statusCode == 400) {
                        // Bad request
                        return 400;
                    } else if ($statusCode == 500) {
                        // Internal server error
                        return 500;
                    } else {
                        // Other error
                        return "Error: " . $body['error'];
                    }
                } else {
                    // Chat creation successful
                    return $statusCode;
                }
            } catch (\Exception $e) {
                // Handle exceptions
                return "Error: " . $e->getMessage();
            }
        } else {
            return 404;
        }
    }

}
