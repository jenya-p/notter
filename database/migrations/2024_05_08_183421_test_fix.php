<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('tests', function (Blueprint $table) {
            $table->date('available_till')->nullable()->after('passing_score')->change();
        });
    }

    public function down(): void {
    }
};
