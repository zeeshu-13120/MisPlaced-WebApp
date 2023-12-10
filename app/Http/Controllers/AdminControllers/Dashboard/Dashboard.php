<?php

namespace App\Http\Controllers\AdminControllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Dashboard extends Controller
{
    public function dashboard()
    {

        $users = User::select('created_at')
            ->orderBy('created_at')
            ->get();

// Format the data for Chart.js
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'x' => $user->created_at->format('Y-m-d'), // Format the timestamp as needed
                'y' => 1, // Each user represents a value of 1 on the y-axis
            ];
        }
        $totalLost = 0;
        $totalFound = 0;
        $recovered = 0;
        $unrecovered = 0;

        try {
            // Retrieve all forms with their associated child tables
            $forms = DB::table('forms')
                ->select('forms.*')
                ->get();

            // Initialize counts
            $totalLost = 0;
            $totalFound = 0;
            $unrecovered = 0;
            $recovered = 0;

            // Loop through each form
            foreach ($forms as $form) {
                $tableName = $form->table;

                // Check if the child table exists
                if (Schema::hasTable($tableName)) {
                    // Count rows with post_type = 'lost' or 'found'
                    $totalLost += DB::table($tableName)
                        ->where('form_id', $form->id)
                        ->where('post_type', 'lost')
                        ->count();

                    $totalFound += DB::table($tableName)
                        ->where('form_id', $form->id)
                        ->where('post_type', 'found')
                        ->count();

                    // Count rows with status = 'pending' or 'recovered'
                    $unrecovered += DB::table($tableName)
                        ->where('form_id', $form->id)
                        ->where('status', 'pending')
                        ->count();

                    $recovered += DB::table($tableName)
                        ->where('form_id', $form->id)
                        ->where('status', 'recovered')
                        ->count();
                }
            }

        } catch (\Exception $e) {

        }

        $totalProfit = Transaction::sum('amount');
        $totalCustomer = count($data);
        return view('AdminViews.Dashboard.index', compact(['totalLost', 'totalFound', 'recovered', 'unrecovered', 'totalCustomer', 'totalProfit', 'data']));
    }

}
