<?php

namespace App\Http\Controllers\UserControllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserControllers\PaymentController;
use App\Models\Transaction;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Chat extends Controller
{

    public function setup_client_create(string $projectId = null)
    {

        // Create the Cloud Firestore client
        if (empty($projectId)) {
            // The `projectId` parameter is optional and represents which project the
            // client will act on behalf of. If not supplied, the client falls back to
            // the default project inferred from the environment.
            $db = new FirestoreClient();

            printf('Created Cloud Firestore client with default project ID.' . PHP_EOL);
        } else {
            $db = new FirestoreClient([
                'projectId' => $projectId,
            ]);
            printf('Created Cloud Firestore client with project ID: %s' . PHP_EOL, $projectId);
        }
    }
    public function getChats()
    {

        return view('Chat.index');
    }

    public function createChat(Request $request)
    {
        $post1 = $request->post1;
        $post2 = $request->post2;
        $table = $request->table;
        $payment = Transaction::where(function ($query) use ($post1, $post2) {
            $query->orWhere(['post1' => $post1, 'post2' => $post2])
                ->orWhere(['post1' => $post2, 'post2' => $post1]);
        })
            ->where(['table' => $table])
            ->first();

        if ($payment) {
            return view('Chat.index', compact('post1', 'post2', 'table'));
        } else {

            $post = DB::table($table)
                ->where(function ($query) use ($post1, $post2) {
                    $query->where('id', $post1)
                        ->orWhere('id', $post2);
                })
                ->where('user_id', Auth::user()->id)
                ->first();

            if ($post) {
                if ($post->post_type == "found") {
                    Transaction::create([
                        'user_id' => Auth::user()->id,
                        'post1' => $post1,
                        'post2' => $post2,
                        'amount' => 00,
                        'table' => $table,
                        'transaction_id' => "free",
                    ]);
                    $paymentController = new PaymentController();
                    $createChatStatus = $paymentController->createChat($post1, $post2, $table);
                    if ($createChatStatus == 200) {

                        return redirect()->route('chat.get')->with('success', 'You can now chat with the selected person.');
                    }
                }



            }

            return view('Checkout.index', compact('post1', 'post2', 'table'));
        }
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . rand(10000000, 99999999) . '.' . $file->getClientOriginalExtension();

            // Store the file to the 'uploads' directory
            $filePath = $file->storeAs('uploads', $fileName, 'public');

            // Save the file path in the database for the specific input.
            $path = "/storage/" . $filePath;

            return response()->json(['fileURL' => $path]);
        }

        return response()->json(['error' => 'File not provided.'], 400);
    }

    
}
