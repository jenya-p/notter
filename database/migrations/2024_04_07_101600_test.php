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
            $table->unsignedMediumInteger('order');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 11, 2);
            // $table->unsignedMediumInteger('ticket_count')->default(0);
            $table->boolean('is_plain_text')->default(false);
            $table->unsignedSmallInteger('passing_score')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id');
            $table->foreign('block_id', 'questions__block')
                ->references('id')
                ->on('quiz_blocks')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedMediumInteger('ticket')->nullable();
            $table->unsignedMediumInteger('order');
            $table->text('text');
            $table->text('description')->nullable();
            $table->json('options')->nullable();
            $table->unsignedSmallInteger('right')->nullable();

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

            $table->unsignedSmallInteger('passing_score')->nullable();
            $table->date('available_till');
            $table->dateTime('completed_at')->nullable();

            $table->unsignedMediumInteger('ticket_count')->default(0);
            $table->unsignedMediumInteger('ticket_passed_count')->default(0);
            $table->unsignedMediumInteger('ticket_failed_count')->default(0);

            $table->unsignedMediumInteger('question_count')->default(0);
            $table->unsignedMediumInteger('question_right_count')->default(0);
            $table->unsignedMediumInteger('question_wrong_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('test_tickets', function(Blueprint $table){
            $table->id();

            $table->foreignId('test_id');
            $table->foreign('test_id', 'test_tickets__test')
                ->references('id')
                ->on('tests')
                ->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedMediumInteger('order');

            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            $table->timestamps();

        });

        Schema::create('test_questions', function(Blueprint $table){
            $table->id();

            $table->foreignId('ticket_id');
            $table->foreign('ticket_id', 'test_questions__ticket')
                ->references('id')
                ->on('test_tickets')
                ->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreignId('quiz_question_id');
            $table->foreign('quiz_question_id', 'test_questions__quiz_question')
                ->references('id')
                ->on('quiz_questions')
                ->restrictOnDelete()->cascadeOnUpdate();

            $table->unsignedMediumInteger('order');

            $table->text('question');
            $table->text('description');
            $table->json('options');
            $table->unsignedSmallInteger('right');
            $table->unsignedSmallInteger('answer')->nullable();
            $table->dateTime('solved_at')->nullable();

            $table->timestamps();

        });



    }


    public function down(): void {


        Schema::dropIfExists('test_questions');
        Schema::dropIfExists('test_tickets');
        Schema::dropIfExists('tests');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('quiz_variants');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quiz_tickets');
        Schema::dropIfExists('quiz_blocks');


    }
};
