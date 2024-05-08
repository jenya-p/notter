<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('backfeeds', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id', 'backfeeds__user')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()->cascadeOnUpdate();

            $table->enum('status', array_keys(\App\Models\Backfeed::STATUSES));
            $table->string('name', 126);
            $table->string('email', 126);
            $table->string('subject', 512);
            $table->text('body');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('backfeeds');
    }
};
