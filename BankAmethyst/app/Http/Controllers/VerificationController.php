<?php

namespace App\Http\Controllers;

use App\Transfer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function systemVerification(Transfer $transfer) {
        $date = Carbon::now()->subDays(3);
        $date = strtotime($date." +1 day");
        $fd = Date("d-m-Y",$date);
        $finalDate = Carbon::createFromFormat('d-m-Y', $fd)->toDateString();
        $ostatnie = Transfer::where('nadawca','=',$transfer->nadawca)->where('odbiorca','=',$transfer->odbiorca)->where('created_at','>=', $finalDate)->get();
        $suma = 0;
        if($ostatnie != null) {
            foreach($ostatnie as $i) {
                $suma = $suma + $i->kwota;
            }
        }
        if($suma > 5000) {
            //do ręcznej weryfikacji
            $transfer->status = 1;
            $transfer->update();
            return 1;
        }
        else {
            $transfer->status = 4;
            app('App\Http\Controllers\AccountController')->changeInternalBillingAccountBalance($transfer->kwota*(-1));
            app('App\Http\Controllers\AccountController')->changeBalance($transfer->odbiorca, $transfer->kwota);
            $transfer->update();
            return 1;
        }
    }

    public function manualVerification(Request $request) {
        $id = $request->get('id');
        $ver = $request->get('ver');
        $transfer = Transfer::where('id','=',$id)->get()->first();
        if($ver) {
            $transfer->status = 4;
            app('App\Http\Controllers\AccountController')->changeInternalBillingAccountBalance($transfer->kwota*(-1));
            app('App\Http\Controllers\AccountController')->changeBalance($transfer->odbiorca, $transfer->kwota);
            $transfer->update();
        }
        else {
            $transfer->status = 5;
            app('App\Http\Controllers\AccountController')->changeInternalBillingAccountBalance($transfer->kwota*(-1));
            app('App\Http\Controllers\AccountController')->changeBalance($transfer->nadawca, $transfer->kwota);
            $transfer->update();
        }
        return 1;
    }
}
