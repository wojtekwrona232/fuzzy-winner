<?php

namespace App\Http\Controllers;

use App\Account;
use App\ClientAccount;
use Illuminate\Http\Request;
use App\Client;

class ClientAccountController extends Controller
{
        public function getAccounts(Request $request) {

            $owner = $request->get('id');
            $client = Client::where('id','=',$owner)->get()->first();

            $nazwisko = $client['imie_nazwisko'];
            $ids = ClientAccount::where('id_klienta','=',$owner)->get('id_konta')->toArray();
            $accounts = array();
            foreach ($ids as $id) {
                $numer = Account::where('id','=',$id)->get('numer')->first();
                $saldo = Account::where('id','=',$id)->get('saldo')->first();
                $konto = (object)[
                    'numer' => $numer,
                    'saldo' => $saldo
                ];
                array_push($accounts, $konto);
            }

            $numerGlowny = $accounts[0]->numer->numer;

            $obj = (object)[
                'nazwisko' => $nazwisko,
                'accounts' => $accounts,
                'numerGlowny' => $numerGlowny
            ];


            return json_encode($obj);

        }
}
