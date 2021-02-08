<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->Bigincrements('id')->primaryKey();
            $table->string('login') ->unique();
            $table->string('haslo');
            $table->string('imie_nazwisko');
            $table->string('PESEL',11) ->unique();
            $table->string('adres');
            $table->string('kod_pocztowy', 5);
            $table->string('miejscowosc',255);
            $table->boolean('jestPracownikiem');
            $table->timestamps();
        });
        $data = array("id" => 1, "login" => "987654", "haslo" =>Hash::make("haslo"), "imie_nazwisko" => "Jan Kowalski", "PESEL" => "79122451696", "adres" => "ul. Rzeszow 5", "kod_pocztowy" => "30123", "miejscowosc" => "Krakow", "jestPracownikiem" => 0, "created_at" => Carbon::now()->toDateTimeString());
        DB::table('clients')->insert($data);
        $data = array("id" => 2, "login" => "456789", "haslo" => Hash::make("haslo"), "imie_nazwisko" => "Bartłomiej Strzypa", "PESEL" => "19121695936", "adres" => "ul. Lwowska 15", "kod_pocztowy" =>"30512", "miejscowosc" => "Rzeszow", "jestPracownikiem" => 0, "created_at" =>Carbon::now()->toDateTimeString());
        DB::table('clients')->insert($data);
        $data = array("id" => 3, "login" => "111111", "haslo" => Hash::make("pracownicze"), "imie_nazwisko" => "Czesław Znikod", "PESEL" => "56021773438", "adres" => "ul. Krótka 75", "kod_pocztowy" => "00001", "miejscowosc" => "Warszawa", "jestPracownikiem" => 1, "created_at" => Carbon::now()->toDateTimeString());
        DB::table('clients')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
