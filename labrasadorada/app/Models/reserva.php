<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('telefono');
            $table->date('fecha');
            $table->time('hora');
            $table->string('personas');
            $table->text('notas')->nullable();
            $table->enum('estado', ['pendiente','confirmada','cancelada'])->default('pendiente');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('reservas');
    }
};