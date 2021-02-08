<?php

namespace App\Http\Controllers;
session_start();
use Illuminate\Http\Request;
use App\Client;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function getAllClients() {
        $clients = Client::all();
        return $clients;
    }
    public function getOneClient($id) {
        $client = Client::where('id','=',$id)->get();
        return $client;
    }

    public function loginUser(Request $request){
        $login = $request->get('login');
        $password = $request->get('password');
        $client = Client::where('login','=',$login)->get()->first();

       if($client != null) {
           //porównywanie zaszyfrowanych hasel - do implementacji
            if($login = $client['login'] && Hash::check($password, $client['haslo'])) {
                $result = ['ID' => $client['id'],'name' => $client['imie_nazwisko'], 'jestPracownikiem' => $client['jestPracownikiem']];
                $json = json_encode($result);

                return  $json;

            }
        }
       return  (json_encode(['ID' => -1]));
    }
}
