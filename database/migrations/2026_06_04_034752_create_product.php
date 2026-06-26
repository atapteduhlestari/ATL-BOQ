<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk')->unique();
            $table->string('nama_produk');
            $table->string('slug')->unique();
            $table->foreignId('kategori_id')->constrained('product_categories')->onDelete('restrict');
            $table->foreignId('unit_id')->constrained('product_units')->onDelete('restrict');
            $table->enum('tipe_produk', ['material', 'accessory', 'combo'])->default('material');
            $table->decimal('hpp_produk', 15, 2)->default(0);
            $table->decimal('harga_price_list', 15, 2)->default(0);
            $table->foreignId('area_id')->nullable()->constrained('product_areas')->onDelete('set null');
            $table->decimal('satuan_terkecil', 10, 2)->default(1); // in meters
            $table->foreignId('brand_id')->constrained('product_brands')->onDelete('restrict');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('kode_produk');
            $table->index('nama_produk');
            $table->index('kategori_id');
            $table->index('brand_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
