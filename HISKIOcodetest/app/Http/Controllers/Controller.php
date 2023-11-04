<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

/**
 * 題目三：
請您使用 Laravel 建立一個 API 系統，此系統需具備以下功能：

會員登入、註冊，至少需 account, password 欄位。
帳戶存款的 CRUD 功能，至少需要兩張表。
accounts：紀錄使用者帳號、目前存款餘額。
balances：紀錄使用者存取款紀錄。
畫面不需特別美化，至少需簡單呈現以下資料，其他資料可自行評估後增加！

/accounts

用戶 ID	帳號	存款餘額	詳細資料
1	aaa	50	a link -> /accounts/1
/accounts/1

金額	存款餘額	時間
100	100	2022/05/01 00:00:00
-100	0	2022/05/02 00:00:00
50	50	2022/05/03 00:00:00
切記：每人的帳戶存款餘額不能為負值
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
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
        $n = 5;
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
