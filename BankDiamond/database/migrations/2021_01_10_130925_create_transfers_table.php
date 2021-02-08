<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->increments('id')->primaryKey();
            $table->string('nadawca',26);
            $table->string('nazwa_nad', 255);
            $table->string('adres_nad');
            $table->string('kod_pocztowy_nad',5);
            $table->string('miejscowosc_nad',255);
            $table->decimal('kwota',20,2);
            $table->enum('typ',['wewnetrzny','miedzybankowy','ekspresowy']);
            $table->string('tytul',140);
            $table->string('odbiorca',26);
            $table->string('nazwa_odb', 255);
            $table->string('adres_odb')->nullable();
            $table->string('kod_pocztowy_odb',5)->nullable();
            $table->string('miejscowosc_odb',255)->nullable();
            $table->boolean('jawny');
            $table->enum('status',['oczekuje na weryfikacje','w trakcie realizacji','wyslany','zrealizowany','odrzucony','przychodzacy-zrealizowany','przychodzacy-odrzucony']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
}
