<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarnesTable extends Migration
{
    public function up(): void
    {
        Schema::create('carnes', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor_total', 10, 2);
            $table->integer('qtd_parcelas');
            $table->date('data_primeiro_vencimento');
            $table->enum('periodicidade', ['mensal', 'semanal']);
            $table->decimal('valor_entrada', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carnes');
    }
}
