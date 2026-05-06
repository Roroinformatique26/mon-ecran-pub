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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id('id_incident');
            $table->date('date_incident')->nullable();
            $table->string('photo')->nullable();
            $table->date('date_maj')->nullable();
            $table->string('departement')->nullable();
            $table->string('systeme')->nullable();
            $table->string('lot_travail')->nullable();
            $table->string('zone')->nullable();
            $table->string('etiquette')->nullable();
            $table->text('description')->nullable();
            $table->string('categorie')->nullable();
            $table->string('interne')->nullable();
            $table->string('statut')->nullable();
            $table->string('responsabilite')->nullable();
            $table->string('emis_par')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
