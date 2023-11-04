<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
  function climbStairs($n)
  {
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
}
