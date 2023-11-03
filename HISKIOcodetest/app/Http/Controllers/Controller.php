<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function Cart()
    {
        return view('welcome');
    }

    public function deposit(Request $request, User $user)
    {
        $amount = $request->input('amount');

        if ($amount <= 0) {
            return response()->json(['message' => 'Amount must be greater than 0'], 400);
        }

        $user->balance += $amount;
        $user->save();


        return response()->json(['message' => 'Deposit successful', 'new_balance' => $user->balance]);
    }

    public function withdraw(Request $request, User $user)
    {
        $amount = $request->input('amount');

        if ($amount <= 0) {
            return response()->json(['message' => 'Amount must be greater than 0'], 400);
        }

        if ($amount > $user->balance) {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }

        $user->balance -= $amount;
        $user->save();


        return response()->json(['message' => 'Withdrawal successful', 'new_balance' => $user->balance]);
    }
}
