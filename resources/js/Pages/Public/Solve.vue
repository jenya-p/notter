<template>

    <div class="block block-2">
    <NumberLine v-model="index" :count="questions.length" class="number-line--white" label="Вопрос"
                :item-class="questionClasses"/>
    <div class="block-bordered">
        <div class="question">
            {{ question.question }}
        </div>

        <div class="variants">
            <div class="variant-wrapper" :class="{active: answer == index}"
                 v-for="(option, index) of question.options">
                <Radio v-model="answer" :value="'' + index">{{ option }}</Radio>
            </div>
        </div>
    </div>

    <div class="buttons">
        <button class="btn btn-green btn-next" @click.prevent="confirm" :disabled="answer == null">
            Следующий
        </button>
        <a class="btn btn-white btn-skip" @click.prevent="skip">Пропустить</a>
        <a class="btn btn-white btn-skip" @click.prevent="complete">Завершить</a>
    </div>
    </div>
</template>

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Radio from "@/Components/Radio.vue";
import {ref} from "vue";
import NumberLine from "@/Components/NumberLine.vue";
import {router} from "@inertiajs/vue3";

export default {
    components: {NumberLine, Radio, GuestLayout},
    props: [
        'ticket'
    ],
    data() {
        return {
            index: 1,
            answer: this.ticket.questions[0].answer,
        }
    },
    methods: {

        async confirm() {
            if (this.answer === null) {
                alert('Выберете вариант ответа');
                return;
            }
            let result = await axios.post(route('test.answer', {question: this.question.id, answer: this.answer}));
            if (result.data.result == 'ok') {
                this.question.answer = this.answer;
                this.next();
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        },

        async skip() {
            if (this.question.answer === null) {
                this.next();
            } else {
                let result = await axios.delete(route('test.skip', {question: this.question.id}));
                if (result.data.result == 'ok') {
                    this.question.answer = null;
                    this.next();
                } else {
                    alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
                }
            }
        },

        async next() {
            if(this.isAllResolved()){
                this.complete();
            } else if (this.index === this.questions.length) {
                this.findUnresolvedQuestion();
            } else {
                this.index++;
            }

        },

        complete() {
            if (confirm('Завершить выполнение теста и перейти к результатам?')) {
                this.$emit('complete');
            }
        },

        questionClasses(index) {
            return {green: this.questions[index - 1].answer !== null};
        },
        isAllResolved(){
            for (const question of this.questions) {
                if(question.answer === null){
                    return false;
                }
            }
            return true;
        },
        findUnresolvedQuestion(){
            for (const question of this.questions) {
                if(question.answer === null){
                    this.index = question.order;
                    return;
                }
            }
            this.index = 1;
        }

    },
    watch: {
        index() {
            this.answer = this.question.answer;
            let $v = this;
            setTimeout(function(){
                history.replaceState({}, '', route('test', {
                    test:           $v.ticket.test_id,
                    ticketIndex:    $v.ticket.order,
                    questionIndex:  $v.question.order
                }));
            },100);
            // this.$nextTick(function(){
            //
            // });
        },
        ticket(){
            this.findUnresolvedQuestion();
        }
    },
    mounted() {
        this.findUnresolvedQuestion();
    },
    computed: {
        questions(){
            return this.ticket.questions;
        },
        question() {
            return this.questions[this.index - 1];
        },
    }
}


</script>

<style lang="scss" scoped>

@import "resources/css/admin-vars";



.block-bordered {
    padding: 17px 25px;
    min-height: 358px;
    box-sizing: border-box;
    margin-bottom: 82px;

    @media screen and (max-width: 500px) {
        padding-left: 10px;
        padding-right: 10px;
        margin-bottom: 30px;
    }

    .question {
        font-size: 20px;
        font-weight: 600;
        line-height: 1.2em;
        margin-bottom: 10px;
    }

    .variants {
        display: flex;
        flex-direction: column;
        gap: 11px;

        .variant-wrapper {
            padding: 10px;
            border-radius: 10px;
            @media screen and (max-width: 500px) {
                margin-left: -10px;
                margin-right: -10px;
                border-radius: 0;
            }


            &.active {
                background: #F1F1F1;
            }
        }

    }
}

.buttons {
    display: flex;
    gap: 15px;


    .btn-next {
        width: unset;
        flex-grow: 1;
    }

    .btn-skip {
        // width: 180px;
        width: 130px;

        //line-height: inherit;
        padding-left: 0;
        padding-right: 0;
    }


    @media screen and (max-width: 500px) {
        flex-direction: column;
        align-items: center;
        gap: 0;
        width: 100%;
        .btn-next {
            width: 100%;
        }
    }
}

</style>


