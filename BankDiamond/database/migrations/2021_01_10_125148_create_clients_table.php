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
        $data = array("id" => 1, "login" => "123456", "haslo" =>Hash::make("haslo123"), "imie_nazwisko" => "Adam Adamski", "PESEL" => "77122451696", "adres" => "ul. Krakowska 20", "kod_pocztowy" => "35123", "miejscowosc" => "Rzeszow", "jestPracownikiem" => 0, "created_at" => Carbon::now()->toDateTimeString());
        DB::table('clients')->insert($data);
        $data = array("id" => 2, "login" => "43432145", "haslo" => Hash::make("haslo4321"), "imie_nazwisko" => "Bartłomiej Babacki", "PESEL" => "79121695936", "adres" => "ul. Rzeszowska 15", "kod_pocztowy" =>"30012", "miejscowosc" => "Kraków", "jestPracownikiem" => 0, "created_at" =>Carbon::now()->toDateTimeString());
        DB::table('clients')->insert($data);
        $data = array("id" => 3, "login" => "111111", "haslo" => Hash::make("pracownicze"), "imie_nazwisko" => "Czesław Cabacki", "PESEL" => "56021773438", "adres" => "ul. Krótka 75", "kod_pocztowy" => "00001", "miejscowosc" => "Warszawa", "jestPracownikiem" => 1, "created_at" => Carbon::now()->toDateTimeString());
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
