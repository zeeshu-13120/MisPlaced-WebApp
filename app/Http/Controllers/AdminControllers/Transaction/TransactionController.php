<?php

namespace App\Http\Controllers\AdminControllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {

        $transactions = Transaction::where('amount', '>', 0)->with('user')->get();

        return view('AdminViews.Transactions.index', compact('transactions'));
    }
}
