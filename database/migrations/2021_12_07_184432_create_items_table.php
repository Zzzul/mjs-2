<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('akun_beban_id')->constrained('coas');
            $table->foreignId('akun_retur_pembelian_id')->constrained('coas');
            $table->foreignId('akun_penjualan_id')->constrained('coas');
            $table->foreignId('akun_retur_penjualan_id')->constrained('coas');
            $table->string('kode', 30);
            $table->string('nama', 100);
            $table->string('type', 20);
            $table->text('deskripsi');
            $table->integer('stok');
            $table->string('foto');
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
        Schema::dropIfExists('items');
    }
}
