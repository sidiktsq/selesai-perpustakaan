<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('peminjaman_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
        $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
        $table->integer('jumlah');
        $table->timestamps();
    });
}
    public function down()
    {
        Schema::dropIfExists('peminjaman_details');
    }
};
