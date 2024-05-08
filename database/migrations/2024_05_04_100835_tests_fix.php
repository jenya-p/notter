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

//        Schema::table('tests', function(Blueprint $table){
//            $table->boolean('is_plain_text')->default(false)->after('title');
//        });

        Schema::table('test_questions', function(Blueprint $table){
            $table->text('description')->nullable()->after('question')->change();
            $table->json('options')->nullable()->after('description')->change();
            $table->unsignedSmallInteger('right')->nullable()->after('options')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        Schema::table('tests', function(Blueprint $table){
//            $table->dropColumn('is_plain_text');
//        });
    }
};
