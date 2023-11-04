<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function climbStairs($n) {
        if ($n <= 2) {
            return $n;
        }
    
        $dp = array_fill(0, $n + 1, 0);
        $dp[1] = 1;
        $dp[2] = 2;
    
        for ($i = 3; $i <= $n; $i++) {
            $dp[$i] = $dp[$i - 1] + $dp[$i - 2];
        }
    
        return $dp[$n];
    }
    
    public function Cart()
    {
        $n = 50; 
        $ways = $this->climbStairs($n); 
        return view('welcome', compact('ways'));
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'money' => 'required|min:0|numeric'
        ]);
        $user = Auth::user();
        $passedAccounts = $user->accounts;
        $currentAccounts = $passedAccounts + $request->money;
        Balance::create([
            'user_id' => $request->user()->id,
            'current_balance' => $currentAccounts,
            'passed_balance' => $passedAccounts,
        ]);

        $user->update(['accounts' => $currentAccounts]);

        return redirect(route('dashboard'));
    }

    public function withdraw(Request $request)
    {
        $user = Auth::user();
        $passedAccounts = $user->accounts;
        $request->validate([
            'money' => [
                'required',
                'numeric',
                'min:0',
                'max:' . $passedAccounts,
            ]
        ]);

        $currentAccounts = $passedAccounts - $request->money;

        Balance::create([
            'user_id' => $request->user()->id,
            'current_balance' => $currentAccounts,
            'passed_balance' => $passedAccounts,
        ]);

        $user->update(['accounts' => $currentAccounts]);

        return redirect(route('dashboard'));
    }
}
