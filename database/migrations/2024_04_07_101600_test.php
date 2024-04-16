<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {


    public function up(): void {

        $this->down();

        Schema::create('quiz_blocks', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(true);
            $table->integer('order');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 11, 2);
            $table->boolean('is_plain_text')->default(false);
            $table->unsignedSmallInteger('passing_score')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quiz_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id');
            $table->foreign('block_id', 'tickets__block')
                ->references('id')
                ->on('quiz_blocks')
                ->cascadeOnDelete()->cascadeOnUpdate();

            $table->integer('order');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id');
            $table->foreign('ticket_id', 'questions__ticket')
                ->references('id')
                ->on('quiz_tickets')
                ->cascadeOnDelete()->cascadeOnUpdate();

            $table->integer('order');
            $table->text('text');
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quiz_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id');
            $table->foreign('question_id', 'variants__question')
                ->references('id')
                ->on('quiz_questions')
                ->cascadeOnDelete()->cascadeOnUpdate();

            $table->integer('order');
            $table->boolean('is_right');
            $table->text('text');

            $table->timestamps();
            $table->softDeletes();

        });


        Schema::create('payments', function(Blueprint $table){
            $table->id();

            $table->foreignId('user_id');
            $table->foreign('user_id', 'payments__user')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()->cascadeOnUpdate();

            $table->enum('status', ['new', 'done', 'canceled']);
            $table->decimal('amount', 11, 2);
            $table->dateTime('payed_at')->default(null);
            $table->enum('method', ['yandex']);
            $table->enum('type', ['card']);
            $table->string('payment_external_id', 512)->default(null);

            $table->timestamps();
            $table->softDeletes();

        });


        Schema::create('tests', function(Blueprint $table){
            $table->id();

            $table->foreignId('user_id');
            $table->foreign('user_id', 'tests__user')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreignId('block_id');
            $table->foreign('block_id', 'tests__block')
                ->references('id')
                ->on('quiz_blocks')
                ->restrictOnDelete()->cascadeOnUpdate();

            $table->foreignId('payment_id')->nullable();
            $table->foreign('payment_id', 'tests__payment')
                ->references('id')
                ->on('payments')
                ->restrictOnDelete()->cascadeOnUpdate();

            $table->string('title');

            $table->decimal('amount', 11, 2);

            $table->date('available_till');
            $table->dateTime('completed_at')->nullable();

            $table->unsignedMediumInteger('question_count')->default(0);
            $table->unsignedMediumInteger('right_count')->default(0);
            $table->unsignedMediumInteger('wrong_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

        });


        Schema::create('test_answers', function(Blueprint $table){
            $table->id();

            $table->foreignId('test_id');
            $table->foreign('test_id', 'test_answers__test')
                ->references('id')
                ->on('tests')
                ->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreignId('question_id');
            $table->foreign('question_id', 'test_answers__question')
                ->references('id')
                ->on('quiz_questions')
                ->restrictOnDelete()->cascadeOnUpdate();

            $table->foreignId('variant_id');
            $table->foreign('variant_id', 'test_answers__variant')
                ->references('id')
                ->on('quiz_variants')
                ->restrictOnDelete()->cascadeOnUpdate();

            $table->unsignedSmallInteger('ticket_index');
            $table->unsignedSmallInteger('question_index');
            $table->text('question_text');
            $table->text('answer_text');
            $table->boolean('is_right');
            $table->text('right_answer_text')->nullable();
            $table->text('question_description')->nullable();

            $table->timestamps();

        });



    }


    public function down(): void {


        Schema::dropIfExists('test_answers');
        Schema::dropIfExists('tests');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('quiz_variants');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quiz_tickets');
        Schema::dropIfExists('quiz_blocks');


    }
};
