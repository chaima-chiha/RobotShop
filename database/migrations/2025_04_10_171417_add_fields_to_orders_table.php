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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('nom')->after('user_id');
            $table->string('adresse')->after('nom');
            $table->string('telephone')->after('adresse');
            $table->enum('livraison', ['domicile', 'retrait'])->after('telephone');
            $table->string('status')->default('en_attente')->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['nom', 'adresse', 'telephone', 'livraison', 'status']);
        });
    }
};
