<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('payments', function(Blueprint $table){
            $table->json('items')->after('amount');
            $table->dateTime('payed_at')->nullable()->change();
            $table->string('payment_external_id', 512)->nullable()->change();
            $table->renameColumn('payment_external_id', 'external_id');
            $table->dropColumn('type');
            $table->dropColumn('method');
        });
    }

    public function down(): void {
        Schema::table('payments', function(Blueprint $table){
            $table->dropColumn('items');
            $table->dateTime('payed_at')->nullable(false)->change();
            $table->string('external_id', 512)->nullable(false)->change();
            $table->renameColumn('external_id', 'payment_external_id');
            $table->enum('method', ['yandex'])->after('external_id');
            $table->enum('type', ['card'])->after('method');
        });
    }
};
