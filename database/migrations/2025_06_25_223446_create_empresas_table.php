<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social');             
            $table->string('nombre_comercial')->nullable(); 
            $table->string('ruc', 11)->unique()->nullable();         
            $table->string('direccion_fiscal');        
            $table->string('ubigeo', 6)->nullable();     
            $table->string('departamento')->nullable();   
            $table->string('provincia')->nullable();    
            $table->string('distrito')->nullable();       
            $table->string('telefono')->nullable();       
            $table->string('correo')->nullable();        

         

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
