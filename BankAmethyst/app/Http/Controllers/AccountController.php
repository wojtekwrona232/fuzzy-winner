<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
   public function calculateAccountNumber($number) {
        $bankNumber = "10205226";
        $countryNumber = "252100";

        $subnum = substr($bankNumber, 0, 4);
        $sk1 = $subnum % 97;
        $sk1 = strval($sk1);

        $num = $sk1;
        $subnum = substr($bankNumber, 4, 4);
        $num .= $subnum;
        $num = (int)$num;
        $sk1 = $num % 97;
        $sk1 = strval($sk1);

        $num = $sk1;
        $subnum = substr($number, 0, 4);
        $num .= $subnum;
        $num = (int)$num;
        $sk1 = $num % 97;
        $sk1 = strval($sk1);

        $num = $sk1;
        $subnum = substr($number, 4, 4);
        $num .= $subnum;
        $num = (int)$num;
        $sk1 = $num % 97;
        $sk1 = strval($sk1);

        $num = $sk1;
        $subnum = substr($number, 8, 4);
        $num .= $subnum;
        $num = (int)$num;
        $sk1 = $num % 97;
        $sk1 = strval($sk1);

        $num = $sk1;
        $subnum = substr($number, 12, 4);
        $num .= $subnum;
        $num = (int)$num;
        $sk1 = $num % 97;
        $sk1 = strval($sk1);

        $num = $sk1;
        $num .= $countryNumber;
        $num = (int)$num;

        $roznica = $num % 97;
        $sk = 98 - $roznica;
        if ($sk < 10) {
            $sk2 = $sk;
            $sk = '0';
            $sk .=$sk2;
        }
        $sk = strval($sk);
        $account = $sk;
        $account .= $bankNumber;
        $account .= $number;
        return $account;
    }

    public function calculateAccountNumberReq(Request $request) {
       $number = $request->get('number');
        $bankNumber = "13242154";
        $countryNumber = "252100";

        $subnum = substr($bankNumber, 0, 4);
        $sk1 = $subnum % 97;
        $sk1 = strval($sk1);

        $num = $sk1;
        $subnum = substr($bankNumber, 4, 4);
        $num .= $subnum;
        $num = (int)$num;
        $sk1 = $num % 97;
        $sk1 = strval($sk1);

        $num = $sk1;
        $subnum = substr($number, 0, 4);
        $num .= $subnum;
        $num = (int)$num;
        $sk1 = $num % 97;
        $sk1 = strval($sk1);

        $num = $sk1;
        $subnum = substr($number, 4, 4);
        $num .= $subnum;
        $num = (int)$num;
        $sk1 = $num % 97;
        $sk1 = strval($sk1);

        $num = $sk1;
        $subnum = substr($number, 8, 4);
        $num .= $subnum;
        $num = (int)$num;
        $sk1 = $num % 97;
        $sk1 = strval($sk1);

        $num = $sk1;
        $subnum = substr($number, 12, 4);
        $num .= $subnum;
        $num = (int)$num;
        $sk1 = $num % 97;
        $sk1 = strval($sk1);

        $num = $sk1;
        $num .= $countryNumber;
        $num = (int)$num;

        $roznica = $num % 97;
        $sk = 98 - $roznica;
        if ($sk < 10) {
            $sk2 = $sk;
            $sk = '0';
            $sk .=$sk2;
        }
        $sk = strval($sk);
        $account = $sk;
        $account .= $bankNumber;
        $account .= $number;
        return $account;
    }

    public function changeBalance(string $account, float $amount) {
        $account = Account::where('numer','=',$account)->get()->first();
        $newBalance = $account->saldo + $amount;
        $account->saldo = $newBalance;
        $account->update();
        return true;
    }

    public function changeInternalBillingAccountBalance(float $amount) {
       $account = Account::where('id','=', 1)->get()->first();
       $newBalance = $account->saldo + $amount;
       $account->saldo = $newBalance;
       $account->update();
       return true;
    }
    public function changeStandardBillingAccountBalance(float $amount) {
        $account = Account::where('id','=',2)->get()->first();
        $newBalance = $account->saldo + $amount;
        $account->saldo = $newBalance;
        $account->update();
        return true;
    }

    public function changeExpressBillingAccountBalance(float $amount) {
        $account = Account::where('id','=',3)->get()->first();
        $newBalance = $account->saldo + $amount;
        $account->saldo = $newBalance;
        $account->update();
    }

    public function changeAdinBillingAccountBalance(float $amount) {
       $account = Account::where('id','=',4)->get()->first();
        $newBalance = $account->saldo + $amount;
        $account->saldo = $newBalance;
        $account->update();
    }
}
