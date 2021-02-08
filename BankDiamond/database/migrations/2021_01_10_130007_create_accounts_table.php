<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->Bigincrements('id')->primaryKey();
            $table->string('numer',26);
            $table->decimal('saldo',20,2);
            $table->timestamps();
        });

        $internalBilling = app('App\Http\Controllers\AccountController')->calculateAccountNumber('0000000000000001');
        $mainBilling = app('App\Http\Controllers\AccountController')->calculateAccountNumber('0000000000000002');
        $expressBilling = app('App\Http\Controllers\AccountController')->calculateAccountNumber('0000000000000003');
        $secondBank = app('App\Http\Controllers\AccountController')->calculateAccountNumber('0000000000001000');
        $firstClient = app('App\Http\Controllers\AccountController')->calculateAccountNumber('0000000000253876');
        $secondClient = app('App\Http\Controllers\AccountController')->calculateAccountNumber('5870516397407055');
        $data = array("id" => 1, "numer" => $internalBilling, "saldo" => 0,"created_at" => Carbon::now()->toDateTimeString());
        DB::table('accounts')->insert($data);
        $data = array("id" => 2, "numer" => $mainBilling, "saldo" => 0,"created_at" => Carbon::now()->toDateTimeString());
        DB::table('accounts')->insert($data);
        $data = array("id" => 3, "numer" => $expressBilling, "saldo" => 0,"created_at" => Carbon::now()->toDateTimeString());
        DB::table('accounts')->insert($data);
        $data = array("id" => 4, "numer" => $secondBank, "saldo" => 2000000, "created_at" => Carbon::now()->toDateTimeString());
        DB::table('accounts')->insert($data);
        $data = array("id" => 5, "numer" => $firstClient, "saldo" => 250,"created_at" => Carbon::now()->toDateTimeString());
        DB::table('accounts')->insert($data);
        $data = array("id" => 6, "numer" => $secondClient, "saldo" => 25000,"created_at" => Carbon::now()->toDateTimeString());
        DB::table('accounts')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
