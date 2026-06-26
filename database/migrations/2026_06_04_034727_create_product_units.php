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
        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_name');
            $table->string('symbol')->nullable(); // m, m², pcs, etc
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_units');
    }
};
