<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('buku_pengarang', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('buku_id');
        $table->unsignedBigInteger('pengarang_id');

        $table->foreign('buku_id')->references('id')->on('buku')->onDelete('cascade');
        $table->foreign('pengarang_id')->references('id')->on('pengarangs')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('buku_pengarang');
}
};