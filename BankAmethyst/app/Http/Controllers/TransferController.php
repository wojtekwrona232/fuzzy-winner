<?php

namespace App\Http\Controllers;

use App\Account;
use App\Client;
use App\ClientAccount;
use App\Transfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Spatie\ArrayToXml\ArrayToXml;

class TransferController extends Controller
{

    public function sendFiles()
    {
        $konto = Account::where('id','=',3)->get()->first();
        $kwotaExpress = $konto['saldo'];
        if($kwotaExpress > 0) {
            $transfer = new Transfer();
            $transfer->nadawca = '31102052260000000000000003';
            $transfer->odbiorca = '39132421540000000000001000';
            $transfer->nazwa_nad = 'Bank 2';
            $transfer->adres_nad = 'ul. Szkolna 5';
            $transfer->kod_pocztowy_nad = '35000';
            $transfer->miejscowosc_nad = 'Rzeszow';
            $transfer->jawny = 1;
            $transfer->typ = 2;
            $transfer->kwota =$kwotaExpress;
            $transfer->status = 2;
            $transfer->nazwa_odb = 'Bank 2';
            $transfer->tytul = 'Wyrównanie';
            $transfer->save();
            app('App\Http\Controllers\AccountController')->changeExpressBillingAccountBalance($kwotaExpress*(-1));
        }

        $transfers = Transfer::where('status', '=', 2)->get()->toArray();
        $returnTrueTransfers = Transfer::where('status', '=', 6)->get()->toArray();
        $returnFalseTransfers = Transfer::where('status', '=', 7)->get()->toArray();
       if($transfers == null && $returnTrueTransfers == null && $returnFalseTransfers == null) {
            return 0;
        }
        $amount = 0;
        $finalTransfers = [];
        $returnFinalTransfers = [];
        foreach ($transfers as $t) {
          //  $finalDate = $t['created_at'];
            $finalDate = Carbon::createFromFormat('d-m-Y H:i', $t['created_at']);
            $amount = $amount + $t['kwota'];
            array_push($finalTransfers, ['Sender' =>['Name' => $t['nazwa_nad'],'Account' => $t['nadawca'],'Address'=> $t['adres_nad'],'ZIP'=>$t['kod_pocztowy_nad'],'Town'=>$t['miejscowosc_nad'],'BankName' => 'Bank 2'],'Recipient'=>['Name' => $t['nazwa_odb'],'Account' => $t['odbiorca'],'Address'=> $t['adres_odb'],'ZIP'=>$t['kod_pocztowy_odb'],'Town'=>$t['miejscowosc_odb'],'BankName' => ''],'Details'=>['Timestamp'=>$finalDate,'Title'=>$t['tytul'],'Amount'=>$t['kwota']]]);
        }
        foreach($returnTrueTransfers as $t) {
            array_push($returnFinalTransfers, ['Sender' =>['Name' => $t['nazwa_nad'],'Account' => $t['nadawca'],'Address'=> $t['adres_nad'],'ZIP'=>$t['kod_pocztowy_nad'],'Town'=>$t['miejscowosc_nad'],'BankName' => 'Bank 2'],'Recipient'=>['Name' => $t['nazwa_odb'],'Account' => $t['odbiorca'],'Address'=> $t['adres_odb'],'ZIP'=>$t['kod_pocztowy_odb'],'Town'=>$t['miejscowosc_odb'],'BankName' => ''],'Details'=>['Timestamp'=>$t['created_at'],'Title'=>$t['tytul'],'Amount'=>$t['kwota'],'Executed'=>'true']]);
        }
        foreach($returnFalseTransfers as $t) {
            $amount = $amount + $t['kwota'];
            array_push($returnFinalTransfers, ['Sender' =>['Name' => $t['nazwa_nad'],'Account' => $t['nadawca'],'Address'=> $t['adres_nad'],'ZIP'=>$t['kod_pocztowy_nad'],'Town'=>$t['miejscowosc_nad'],'BankName' => 'Bank 2'],'Recipient'=>['Name' => $t['nazwa_odb'],'Account' => $t['odbiorca'],'Address'=> $t['adres_odb'],'ZIP'=>$t['kod_pocztowy_odb'],'Town'=>$t['miejscowosc_odb'],'BankName' => ''],'Details'=>['Timestamp'=>$t['created_at'],'Title'=>$t['tytul'],'Amount'=>$t['kwota'],'Executed'=>'false']]);
        }
        $array = ['BankData' => ['Name'=> 'Bank 2','AccountNumber' => '15102052260000000000000000', 'Amount' => $amount], 'Transfer' => $finalTransfers, 'ReturnTransfer'=>$returnFinalTransfers];
        $result = ArrayToXml::convert($array, 'Data');
        $response = Http::withBody($result,'xml')->post('https://pab-jr.herokuapp.com/');
            foreach($transfers as $t) {
            $tr = Transfer::where('id','=',$t['id'])->get()->first();
            $tr->status = 3;
            $tr->update();
        }
        foreach($returnTrueTransfers as $t) {
            $tr = Transfer::where('id','=',$t['id'])->get()->first();
            $tr->status = 4;
            $tr->update();
        }
        foreach($returnFalseTransfers as $t) {
            $tr = Transfer::where('id','=',$t['id'])->get()->first();
            $tr->status = 5;
            $tr->update();
        }
        $b = [];
        (array) $array = $response['ReturnTransfers'];
        if($array != null) {
            foreach($array as $a) {
                array_push($b, $a['Details']['Status']);
                if(strlen($a['Sender']['ZIP']) == 6) {
                    $first = substr($a['Sender']['ZIP'],0,2);
                    $second = substr($a['Sender']['ZIP'],3, 3);
                    $a['Sender']['ZIP'] = null;
                    $a['Sender']['ZIP'] = $first + $second;
                }
                if(strlen($a['Recipient']['ZIP']) == 6) {
                    $first = substr($a['Sender']['ZIP'],0,2);
                    $second = substr($a['Sender']['ZIP'],3, 3);
                    $a['Sender']['ZIP'] = null;
                    $a['Sender']['ZIP'] = $first + $second;
                }
                $transfer = Transfer::where('nadawca','=',$a['Sender']['Account'])->where('odbiorca','=',$a['Recipient']['Account'])->where('status','=',3)->where('kwota', '=', $a['Details']['Amount'])->where('tytul','=',$a['Details']['Title'])->get()->first();
                if(strcmp($a['Details']['Status'],"VERIFIED") == 0 || strcmp($a['Details']['Status'],"EXECUTED_TRUE")  == 0) {
                    $transfer->status = 4;
                    $transfer->update();
                }
                else {
                    $transfer->status = 5;
                    $transfer->update();
                }
            }
        }
        (array) $transfers = $response['Transfers'];
        if($transfers != null) {
            foreach($transfers as $tr) {

                if(strlen($tr['Sender']['ZIP']) == 6) {
                    $first = substr($tr['Sender']['ZIP'],0,2);
                    $second = substr($tr['Sender']['ZIP'],3, 3);
                    $tr['Sender']['ZIP'] = null;
                    $tr['Sender']['ZIP'] = $first + $second;
                }
                if(strlen($tr['Recipient']['ZIP']) == 6) {
                    $first = substr($a['Sender']['ZIP'],0,2);
                    $second = substr($a['Sender']['ZIP'],3, 3);
                    $tr['Sender']['ZIP'] = null;
                    $tr['Sender']['ZIP'] = $first + $second;
                }
                $odbiorca = $tr['Recipient']['Account'];
                $kwota = $tr['Details']['Amount'];
                app('App\Http\Controllers\AccountController')->changeStandardBillingAccountBalance($kwota);
                $transfer = new Transfer();
                $transfer->nadawca = $tr['Sender']['Account'];
                $transfer->nazwa_nad = $tr['Sender']['Name'];
                $transfer->adres_nad = $tr['Sender']['Address'];
                $transfer->kod_pocztowy_nad = $tr['Sender']['ZIP'];
                $transfer->miejscowosc_nad = $tr['Sender']['Town'];
                $transfer->kwota = $kwota;
                $transfer->typ = 2;
                $transfer->tytul = $tr['Details']['Title'];
                $transfer->odbiorca = $odbiorca;
                $transfer->nazwa_odb = $tr['Recipient']['Name'];
                $transfer -> adres_odb = $tr['Recipient']['Address'];
                $transfer->kod_pocztowy_odb = $tr['Recipient']['ZIP'];
                $transfer->miejscowosc_odb = $tr['Recipient']['Town'];
                $transfer-> jawny = 1;
                $transfer->created_at = $tr['Details']['Timestamp'];
                if(Account::where('numer','=',$odbiorca)->count() == 0) {
                    $transfer->status = 7;
                    $transfer->save();
                }
                else {
                    $transfer->status = 6;
                    app('App\Http\Controllers\AccountController')->changeStandardBillingAccountBalance($kwota*(-1));
                    app('App\Http\Controllers\AccountController')->changeBalance($odbiorca,$kwota);
                    $transfer->save();
                }
            }
        }

        return 1;
    }

    public function getTransfers(Request $request)
    {
        $account = $request->get('account_number');
        $transfers = Transfer::where('nadawca', '=', $account)->where('status', '=', 4)->orWhere('status', '=',6)->orWhere('odbiorca', '=', $account)->where('status', '=', 4)->orWhere('status', '=',6)->orderBy('created_at', 'desc')->get();
        return $transfers;
    }

    public function getTransfersDatetoDate(Request $request) {
        $account = $request->get('account_number');
        $fromDate = $request->get('from_date');

        $date = strtotime($fromDate." +1 day");
        $fd = Date("d-m-Y",$date);
        $finalDate = Carbon::createFromFormat('d-m-Y', $fd)->toDateString();
        $transfers = Transfer::where('nadawca','=', $account)->whereIn('status',[4,6])->where('created_at', '>=', $finalDate)->orWhere('odbiorca','=',$account)->whereIn('status',[4,6])->where('created_at', '>=', $finalDate)->orderBy('created_at', 'desc')->get();
        return $transfers;
    }

    public function getNewestTransfers(Request $request)
    {
        $account = $request->get('account_number');
        $transfers = Transfer::where('nadawca', '=', $account)->where('status', '=', 'zrealizowany')->orWhere('odbiorca', '=', $account)->orderBy('created_at', 'desc')->take(5)->get();
        return $transfers;
    }

    public function transfer(Request $request)
    {
        $nadawca = $request->get('nadawca');
        $odbiorca = $request->get('odbiorca');
        $standard = $request->get('standard');

        $bank1 = substr($nadawca, 2, 8);
        $bank2 = substr($odbiorca, 2, 8);

        $isSame = strcmp($bank2, $bank1) == 0; //ten sam bank

        if($isSame && $standard == "true"){
            return $this->internalTransfer($request); //przelew wewnetrzny standardowy
        }else if($isSame && $standard == "false"){
            return 0; //przelew ekspresowy wewnetrzny - blad
        }else if(!$isSame && $standard == "true"){
            return $this->standardTransfer($request); //przelew miedzybankowy standardowy
        }else if(!$isSame && $standard == "false"){
            return $this->expressTransfer($request); //przelew miedzybankowy ekspresowy
        }
    }

    public function internalTransfer(Request $request)
    {
        $kwota = $request->get('kwota');
        $kwota = floatval($kwota);
        $nadawca = $request->get('nadawca');
        $konto_nad = Account::where('numer', '=', $nadawca)->get()->first();

        $id_nad = ClientAccount::where('id_konta','=',$konto_nad->id)->get()->first();

        $dane_nad = Client::where('id','=',$id_nad->id_klienta)->get()->first();

        $odbiorca = $request->get('odbiorca');
        $konto_odb = Account::where('numer', '=', $odbiorca)->get()->first();
        $imie_nazwisko_odb = $request->get('imie_nazwisko');
        //$odb = Client::where('imie_nazwisko','=',$imie_nazwisko_odb)->get()->first();

        //jesli konto odbiorcy nie istnieje
        if(!$konto_odb){
            return 0;
        }

        $transfer = new Transfer();
        $transfer->nadawca = $nadawca;
        $transfer->nazwa_nad = $dane_nad->imie_nazwisko;
        $transfer->adres_nad = $dane_nad->adres;
        $transfer->miejscowosc_nad = $dane_nad->miejscowosc;
        $transfer->kod_pocztowy_nad = $dane_nad->kod_pocztowy;


        $transfer->odbiorca = $odbiorca;
        $transfer->tytul = $request->get('tytul');
        $transfer->kwota = $kwota;
        $transfer->nazwa_odb = $imie_nazwisko_odb;

        $transfer->adres_odb = $request->get('adres');
        $transfer->miejscowosc_odb = $request->get('miejscowosc');
        $transfer->kod_pocztowy_odb = $request->get('kod');
        $transfer->jawny = 1;
        $transfer->typ = 1;

        $transfer->created_at = Carbon::now()->toDateTimeString();

        if ($konto_nad->saldo < $kwota) {
            $transfer->status = 5;
            $transfer->save();
            return 0;
        }

        app('App\Http\Controllers\AccountController')->changeBalance($nadawca, $kwota*(-1));

        //Warunek określający weryfikację przelewu
        if ($kwota > 1000) {
            //changeBillingAccountBalance
            app('App\Http\Controllers\AccountController')->changeInternalBillingAccountBalance($kwota);
            $transfer->status = 1;
            $transfer->save();
            app('App\Http\Controllers\VerificationController')->systemVerification($transfer); //wysłanie przelewu do wewnętrnzej jednostki rozliczeniowej
            return 1;
        } else {
            $transfer->status = 4;
            app('App\Http\Controllers\AccountController')->changeBalance($odbiorca, $kwota);
            $transfer->save();
            return 1;
        }
    }

    public function standardTransfer(Request $request)
    {
        $kwota = $request->get('kwota');
        $kwota = floatval ($kwota);
        $nadawca = $request->get('nadawca');
        $konto_nad = Account::where('numer', '=', $nadawca)->get()->first();
        $id_nad = ClientAccount::where('id_konta','=',$konto_nad->id)->get()->first();
        $dane_nad = Client::where('id','=',$id_nad->id_klienta)->get()->first();
        $transfer = new Transfer();
        $transfer->nadawca = $nadawca;
        $transfer->nazwa_nad = $dane_nad->imie_nazwisko;
        $transfer->adres_nad = $dane_nad->adres;
        $transfer->miejscowosc_nad = $dane_nad->miejscowosc;
        $transfer->kod_pocztowy_nad = $dane_nad->kod_pocztowy;
        $transfer->odbiorca =$request->get('odbiorca');
        $transfer->tytul = $request->get('tytul');
        $transfer->kwota = $kwota;
        $transfer->nazwa_odb = $request->get('imie_nazwisko');
        $transfer->adres_odb = $request->get('adres');
        $transfer->miejscowosc_odb = $request->get('miejscowosc');
        $transfer->kod_pocztowy_odb = $request->get('kod');
        $transfer->typ = 2;
        $transfer->jawny = 1;
        $transfer->created_at = Carbon::now()->toDateTimeString();
        if ($konto_nad->saldo < $kwota) {
            $transfer->status = 5;
            $transfer->save();
            return 0;
        }
        $transfer->status = 2;
        app('App\Http\Controllers\AccountController')->changeBalance($nadawca, $kwota*(-1));
        app('App\Http\Controllers\AccountController')->changeStandardBillingAccountBalance($kwota);
        $transfer->save();
        return 1;
    }

    public function expressTransfer(Request $request)
    {
        $kwota = $request->get('kwota');
        $nadawca = $request->get('nadawca');
        $konto_nad = Account::where('numer', '=', $nadawca)->get()->first();
        $id_nad = ClientAccount::where('id_konta','=',$konto_nad->id)->get()->first();
        $dane_nad = Client::where('id','=',$id_nad->id_klienta)->get()->first();

        $saldo = Account::where('numer', '=', $nadawca)->get();

        $odbiorca = $request->get('odbiorca');
        $transfer = new Transfer();
        $transfer->nadawca = $nadawca;
        $transfer->nazwa_nad = $dane_nad->imie_nazwisko;
        $transfer->adres_nad = $dane_nad->adres;
        $transfer->miejscowosc_nad = $dane_nad->miejscowosc;
        $transfer->kod_pocztowy_nad = $dane_nad->kod_pocztowy;
        $transfer->odbiorca = $odbiorca;
        $transfer->tytul = $request->get('tytul');
        $transfer->kwota = $kwota;
        $transfer->nazwa_odb = $request->get('imie_nazwisko');
        $transfer->adres_odb = $request->get('adres');
        $transfer->miejscowosc_odb = $request->get('miejscowosc');
        $transfer->kod_pocztowy_odb = $request->get('kod');
        $transfer->typ = 3;
        $transfer->jawny = 1;
        $transfer->created_at = Carbon::now()->toDateTimeString();
        if ($saldo < $kwota) {
            $transfer->status = 5;
            $transfer->save();
            return 0;
        }
        $json = $transfer->toJson();

       $req = Http::withBody($json,'application/json')->post('https://bank-diamond.herokuapp.com/api/diamond/expressAdin');
         if($req == true) {
            app('App\Http\Controllers\AccountController')->changeBalance($nadawca, $kwota*(-1));
            app('App\Http\Controllers\AccountController')->changeExpressBillingAccountBalance($kwota);
            $transfer->status = 4;
            $transfer->save();
            return 1;
        }
        else {
            $transfer->status = 5;
            $transfer->save();
            return 0;
        }
    }

    public function getAllManualVerifications() {
        $transfers = Transfer::where('status','=',1)->get();
        return $transfers->toJson();
    }

    public function getAllAwaitingTransfer() {
        $transfers = Transfer::where('status','=',2)->get();
        return $transfers->toJson();
    }

    public function incomingExpressTransferFromAdin(Request $request) {
        $express = Account::where('id','=',4)->get()->first();
        $transfer = new Transfer();
        $kwota = $request->get('kwota');
        $transfer->nadawca= $request->get('nadawca');
        $odbiorca = $request->get('odbiorca');
        $transfer->odbiorca = $odbiorca;
        $transfer->tytul = $request->get('tytul');
        $transfer->kwota = $kwota;
        $transfer->nazwa_nad = $request->get('nazwa_nad');
        $transfer->adres_nad = $request->get('adres_nad');
        $transfer->miejscowosc_nad = $request->get('miejscowosc_nad');
        $transfer->kod_pocztowy_nad = $request->get('kod_pocztowy_nad');
        $transfer->nazwa_odb = $request->get('nazwa_odb');
        $transfer->adres_odb = $request->get('adres_odb');
        $transfer->kod_pocztowy_odb = $request->get('kod_pocztowy_odb');
        $transfer->miejscowosc_odb = $request->get('miejscowosc_odb');
        $transfer->jawny = 1;
        $transfer->created_at = Carbon::now()->toDateTimeString();
        $transfer->typ = 3;
        if($express->saldo < $kwota) {
            $transfer->status = 5;
            $transfer->save();
            return 0;
        }
        if(Account::where('numer','=',$odbiorca)->get()->first() == null) {
            $transfer->status = 5;
            $transfer->save();
            return 0;
        }
        app('App\Http\Controllers\AccountController')->changeAdinBillingAccountBalance($kwota*(-1));
        app('App\Http\Controllers\AccountController')->changeBalance($odbiorca, $kwota);
        $transfer->status = 4;
        $transfer->save();
        return 1;
    }
}
