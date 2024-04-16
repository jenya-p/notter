<template>
    <GuestLayout wrapper-class="profile-page" :title="test.title">
        <h1 class="d-only">{{ test.title }}</h1>
        <div class="block block-1">
            <h1 class="m-only">{{ test.title }}</h1>
            <NumberLine v-model="ticketIndex" :options="tickets" class="number-line--gray" label="Билет"
                        :item-class="ticketClasses"/>
        </div>
        <div class="block block-2">
            <NumberLine v-model="questionIndex" :options="questions" class="number-line--white" label="Вопрос"
                        :item-class="questionClasses"/>

            <div class="block-bordered">
                <div class="question">
                    {{ question.text }}
                </div>

                <div class="variants">
                    <div class="variant-wrapper" :class="{active: question.answer == variant.id}"
                         v-for="variant of question.variants">
                        <Radio v-model="question.answer" :value="'' + variant.id">{{ variant.text }}</Radio>
                    </div>
                </div>
            </div>

            <div class="buttons">
                <button class="btn btn-green btn-next" @click.prevent="confirm" :disabled="question.answer == null">
                    Следующий
                </button>
                <a class="btn btn-white btn-skip" @click.prevent="skip">Пропустить</a>
                <a class="btn btn-white btn-skip" @click.prevent="complete">Завершить</a>
            </div>

        </div>
    </GuestLayout>
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
        'test'
    ],
    data() {
        return {
            ticketIndex: this.$attrs.ticketIndex,
            questionIndex: this.$attrs.questionIndex,
        }
    },
    methods: {

        async confirm() {
            if (this.question.answer === null) {
                alert('Выберете вариант ответа');
                return;
            }
            let result = await axios.post(route('answer.store', {test: this.test.id, variant: this.question.answer}));
            if (result.data.result == 'ok') {
                this.question.answer = result.data.answer;
                this.next();
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        },

        async skip() {
            if (this.question.answer === null) {
                this.next();
            } else {
                let result = await axios.delete(route('answer.delete', {test: this.test.id, question: this.question.id}));
                if (result.data.result == 'ok') {
                    this.question.answer = null;
                    this.next();
                } else {
                    alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
                }
            }
        },

        async next() {
            if (this.questionIndex === this.questions.length) {
                this.questionIndex = 1;
                if (this.ticketIndex === this.tickets.length) {
                    this.complete();
                    return;
                } else {
                    this.ticketIndex++;
                }
            } else {
                this.questionIndex++;
            }
            let $v = this;
            this.$nextTick(function () {
                history.replaceState({}, '', route('test', {
                    test: $v.test.id,
                    ticketIndex: $v.ticketIndex,
                    questionIndex: $v.questionIndex
                }));
            });
        },

        complete(){
            if (confirm('Завершить выполнение теста и перейти к результатам?')) {
                router.visit(route('test.summary', {test: this.test.id}));
            }
        },

        ticketClasses(ticket) {
            return {green: ticket.passed};
        },
        questionClasses(question) {
            return {green: question.answer !== null};
        }

    },
    watch: {
        ticketIndex(val, old) {
            this.tickets[old - 1].passed = true;
            for (const question of this.tickets[old - 1].questions) {
                if (question.answer == null) {
                    this.tickets[old - 1].passed = false;
                }
            }
            this.questionIndex = 1;
            for (const index in this.test.block.tickets[this.ticketIndex - 1].questions) {
                if (this.questions[index].answer == null) {
                    this.questionIndex = parseInt(index) + 1;
                    return;
                }
            }
        },
    },
    computed: {
        tickets() {
            return this.test.block.tickets;
        },
        ticket() {
            return this.tickets[this.ticketIndex - 1];
        },
        questions() {
            return this.ticket.questions;
        },
        question() {
            return this.questions[this.questionIndex - 1];
        },
    }
}


</script>

<style lang="scss" scoped>

@import "resources/css/admin-vars";

h1 {
    text-align: center;
    margin-bottom: 17px;
    @media screen and (max-width: 500px) {
        &.d-only {
            display: none;
        }
    }
    @media screen and (min-width: 500px) {
        &.m-only {
            display: none;
        }
    }
}

.block-1 {
    @media screen and (min-width: 500px) {
        margin-bottom: 10px;
    }
    @media screen and (max-width: 500px) {
        margin-bottom: -13px;
        border-bottom: none;
        box-shadow: none;
    }
}

.block-2 {
    @media screen and (min-width: 500px) {
        padding-top: 40px
    }
    @media screen and (max-width: 500px) {
        padding-top: 0;
        border-top: none;
        box-shadow: none;
    }

}

.number-line--gray, .number-line--white {
    padding: 5px;

}

.number-line--white {
    margin-bottom: 25px;
}

::v-deep(.number-line--gray) {
    a.red {
        background: $red;
        color: white;
        border-color: $red;
    }

    a.green {
        background: $green;
        color: white;
        border-color: $green;
    }
}

::v-deep(.number-line--white) {
    a.red {
        background: $red;
        color: white;
        border-color: $red;
    }

    a.green {
        background: $green;
        color: white;
        border-color: $green;
    }
}

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
