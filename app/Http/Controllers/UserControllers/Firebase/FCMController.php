<?php

namespace App\Http\Controllers\UserControllers\Firebase;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FCMController extends Controller
{
    public function index(Request $req)
    {
        $input = $req->all();
        $fcm_token = $input['fcm_token'];
        $user_id = $input['user_id'];

        $user = User::findOrFail($user_id);

        $user->fcm_token = $fcm_token;
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'User token updated successfully.',
        ]);
    }
    public function sendNotification(Request $request)
    {
        // Firebase Cloud Function URL
        $functionUrl = 'https://us-central1-missplaced-1780b.cloudfunctions.net/sendPushNotificationToUser';

        // Example data
        $data = [
            'token' => $request->input('token'),
            'title' => $request->input('title'),
            'body' => $request->input('body'), // Remove the extra dollar sign here
        ];

        try {
            // Make the HTTP POST request
            $response = Http::post($functionUrl, $data);

            // Handle the response
            if ($response->successful()) {
                $responseData = $response->json();
                return response()->json(['success' => true, 'data' => $responseData]);
            } else {
                $errorData = $response->json();
                return response()->json(['success' => false, 'error' => $errorData]);
            }
        } catch (\Exception $exception) {
            // Handle exceptions
            return response()->json(['success' => false, 'error' => $exception->getMessage()]);
        }
    }

}
