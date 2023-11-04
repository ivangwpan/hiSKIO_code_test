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
    
    // 排列組合推導
    function ladder($n)
    {
        function H($m, $n)
        {
            function factorial($num)
            {
                if ($num < 0) {
                    echo "不接受負數";
                } elseif ($num == 0) {
                    return 1;
                } else {
                    $fact = 1;
                    while ($num > 0) {
                        $fact *= $num;
                        $num--;
                    }
                    return $fact;
                }
            }
            return factorial($m + $n - 1) / (factorial($n) * factorial($m - 1));
        }

        $solution = 0;
        for ($a = 0; $a <= $n / 2; $a++) {
            $b = $n - 2 * $a;
            $solution += H($b + 1, $a);
        }

        return intval($solution);
    }

    // 動態記憶演算法
    function climbStairs($n)
    {
        if ($n < 1) return 0;
        if ($n === 1) return 1;
        if ($n === 2) return 2;

        $current = 2;
        $prev = 1;
        $sum = 0;

        for ($i = 3; $i <= $n; $i++) {
            $sum = $current + $prev;
            $prev = $current;
            $current = $sum;
        }

        return $current;
    }

    
    // welcome首頁
    public function Cart()
    {
        $n = 50;
        $ways = $this->ladder($n);
        $ways = $this->climbStairs($n);
        return view('welcome', compact('ways'));
    }

    //存款按鈕功能
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

    // 提款按鈕功能
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
