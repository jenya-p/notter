<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Payment;
use App\Models\Question;
use App\Models\Test;
use App\Models\Ticket;
use App\Models\Variant;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestsSeeder extends Seeder {

    public function run(): void {

        \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        Payment::truncate();
        Question::truncate();
        Test::truncate();
        Variant::truncate();
        Question::truncate();
        Ticket::truncate();
        Block::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        for ($i = 1; $i <= 2; $i++){
            $b = Block::create([
                'title' => 'Тестовое тестирование для натариусов (Блок ' . $i . ')',
                'price' => $i * 1000 + 3500,
                'description' => "<p>Вопросы теста идентичны реальному тесту на адвоката 2024. 359 вопросов адвокатского теста с ответами по новой программе</p>
                <details>
                    <summary>Подробнее</summary>
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
                </details>"
            ]);

            for ($i2 = 1; $i2 <= [0, 5, 60][$i]; $i2++){
                /** @var Ticket $t */
                $t = $b->tickets()->create();

                if($i == 1){

                    /** @var Question $q */
                    $q = $t->questions()->create([
                        'text' => 'Билет ' . $i2 . '. Вопрос 1. Имущество умершего по праву наследования переходит к государству, если:',
                        'description' => 'Билет ' . $i2 . '. Вопрос 1. Значимость этих проблем настолько очевидна, что дальнейшее развитие различных форм деятельности требует от нас системного анализа направлений прогрессивного развития.'
                    ]);

                    $q->variants()->createMany([
                        ['text' => 'нет наследников по завещанию (!)', 'is_right' => true],
                        ['text' => 'наследники лишены завещателем наследства', 'is_right' => false],
                        ['text' => 'нет наследников по закону', 'is_right' => false],
                        ['text' => 'нет наследников ни по закону, ни по завещанию, либо ни один из наследников не принял наследство, либо все наследники лишены завещателем наследства', 'is_right' => false],
                    ]);


                    /** @var Question $q */
                    $q = $t->questions()->create([
                        'text' => 'Билет ' . $i2 . '. Вопрос 2. Отказ от наследства совершается подачей наследником заявления нотариусу:',
                        'description' => 'Билет ' . $i2 . '. Вопрос 2. Значимость этих проблем настолько очевидна, что дальнейшее развитие различных форм деятельности требует от нас системного анализа направлений прогрессивного развития.'
                    ]);

                    $q->variants()->createMany([
                        ['text' => 'в любом месте', 'is_right' => false],
                        ['text' => 'по месту жительства наследника', 'is_right' => false],
                        ['text' => 'по месту открытия наследства (!)', 'is_right' => true],
                        ['text' => 'по месту нахождения нотариуса', 'is_right' => false],
                    ]);

                    /** @var Question $q */
                    $q = $t->questions()->create([
                        'text' => 'Билет ' . $i2 . '. Вопрос 3. Получив сообщение об открывшемся наследстве, нотариус обязан известить об этом:',
                        'description' => 'Билет ' . $i2 . '. Вопрос 3. Значимость этих проблем настолько очевидна, что дальнейшее развитие различных форм деятельности требует от нас системного анализа направлений прогрессивного развития.'
                    ]);

                    $q->variants()->createMany([
                        ['text' => 'других нотариусов', 'is_right' => false],
                        ['text' => 'наследников (!)', 'is_right' => true],
                        ['text' => 'помощника нотариуса', 'is_right' => false],
                        ['text' => 'прессу', 'is_right' => false],
                    ]);
                } else {
                    for($i3 = 1; $i3<=20; $i3++){
                        /** @var Question $q */
                        $q = $t->questions()->create([
                            'text' => 'Билет ' . $i2 . '. Вопрос ' . $i3 . '. Получив сообщение об открывшемся наследстве, нотариус обязан известить об этом:',
                            'description' => 'Билет ' . $i2 . '. Вопрос ' . $i3 . '. Значимость этих проблем настолько очевидна, что дальнейшее развитие различных форм деятельности требует от нас системного анализа направлений прогрессивного развития.'
                        ]);

                        $q->variants()->createMany([
                            ['text' => 'других нотариусов', 'is_right' => false],
                            ['text' => 'наследников (!)', 'is_right' => true],
                            ['text' => 'помощника нотариуса', 'is_right' => false],
                            ['text' => 'прессу', 'is_right' => false],
                        ]);
                    }
                }
            }
        }



        $b = Block::create([
            'title' => 'Список вопросов выносимый на зачет',
            'price' => 5000,
            'is_plain_text' => true,
        ]);
        /** @var Ticket $t */
        $t = $b->tickets()->create();

        /** @var Question $q */
        $q = $t->questions()->create([
            'text' => 'Современные технологии достигли такого уровня, что понимание сути ресурсосберегающих технологий однозначно фиксирует необходимость системы обучения кадров, соответствующей насущным потребностям.'
        ]);

        /** @var Question $q */
        $q = $t->questions()->create([
            'text' => 'В рамках спецификации современных стандартов, активно развивающиеся страны третьего мира объединены в целые кластеры себе подобных!'
        ]);

        /** @var Question $q */
        $q = $t->questions()->create([
            'text' => 'Вот вам яркий пример современных тенденций — синтетическое тестирование, в своём классическом представлении, допускает внедрение своевременного выполнения сверхзадачи.'
        ]);

        /** @var Question $q */
        $q = $t->questions()->create([
            'text' => 'Наше дело не так однозначно, как может показаться: граница обучения кадров играет важную роль в формировании экономической целесообразности принимаемых решений.'
        ]);

        /** @var Question $q */
        $q = $t->questions()->create([
            'text' => 'Есть над чем задуматься: сторонники тоталитаризма в науке смешаны с не уникальными данными до степени совершенной неузнаваемости, из-за чего возрастает их статус бесполезности.'
        ]);

        /** @var Question $q */
        $q = $t->questions()->create([
            'text' => 'Приятно, граждане, наблюдать, как некоторые особенности внутренней политики и по сей день остаются уделом либералов, которые жаждут быть смешаны с не уникальными данными до степени совершенной неузнаваемости, из-за чего возрастает их статус бесполезности.'
        ]);

        /** @var Question $q */
        $q = $t->questions()->create([
            'text' => 'Ясность нашей позиции очевидна: экономическая повестка сегодняшнего дня требует определения и уточнения кластеризации усилий.'
        ]);

        /** @var Question $q */
        $q = $t->questions()->create([
            'text' => 'Безусловно, существующая теория играет важную роль в формировании как самодостаточных, так и внешне зависимых концептуальных решений.'
        ]);

        /** @var Question $q */
        $q = $t->questions()->create([
            'text' => 'С другой стороны, консультация с широким активом играет важную роль в формировании новых предложений.'
        ]);

        /** @var Question $q */
        $q = $t->questions()->create([
            'text' => 'Есть над чем задуматься: независимые государства формируют глобальную экономическую сеть и при этом — описаны максимально подробно.'
        ]);

        /** @var Question $q */
        $q = $t->questions()->create([
            'text' => 'Противоположная точка зрения подразумевает, что ключевые особенности структуры проекта, которые представляют собой яркий пример континентально-европейского типа политической культуры, будут объективно рассмотрены соответствующими инстанциями. Внезапно, представители современных социальных резервов функционально разнесены на независимые элементы. Есть над чем задуматься: диаграммы связей освещают чрезвычайно интересные особенности картины в целом, однако конкретные выводы, разумеется, описаны максимально подробно.'
        ]);
    }


}
