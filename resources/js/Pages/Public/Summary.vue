<template>
    <GuestLayout wrapper-class="profile-page" :title="test.title">
        <h1 class="d-only">Результаты тестирования</h1>
        <h2 class="d-only">{{ test.title }}</h2>
        <div class="block block-1">
            <h1 class="m-only">Результаты тестирования</h1>
            <h2 class="m-only">{{ test.title }}</h2>

            <p class="summary">Правильных ответов: <span class="value color-green">{{
                    test.right_count
                }} ({{ Math.round(100 * test.right_count / test.question_count) }}%)</span></p>
            <p class="summary">Неправильных ответов: <span class="value color-red">{{
                    test.wrong_count
                }} ({{ Math.round(100 * test.wrong_count / test.question_count) }}%)</span></p>
            <p class="summary">Пропущенных: <span class="value color-gray">{{
                    test.skipped_count
                }} ({{ Math.round(100 * test.skipped_count / test.question_count) }}%)</span></p>

        </div>
        <div class="block block-2">

            <div class="block-bordered">
                <div class="tabs-fake">
                    <span>Неверные ответы</span>
                </div>
            </div>

            <div class="block-bordered block-bordered-2">
                <div v-for="item of test.answers" class="item">
                    <p class="answer-indexes">
                        <span>Билет <span class="answer-index font-inter">{{ item.ticket_index }}</span></span>
                        <span>Вопрос <span class="answer-index font-inter color-red">{{
                                item.question_index
                            }}</span></span>
                    </p>
                    <h3>{{ item.question_text }}</h3>
                    <template v-if="item.is_right">
                        <p class="label">Ваш ответ</p>
                        <p class="answer color-green">{{ item.answer_text }}</p>
                    </template>
                    <template v-else>
                        <p class="label">Ваш ответ</p>
                        <p class="answer color-red">{{ item.answer_text }}</p>
                        <p class="label">Правильный ответ</p>
                        <p class="answer color-green">{{ item.right_answer_text }}</p>
                    </template>
                    <div class="description content">{{ item.description }}</div>
                </div>
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
        return {}
    },
    methods: {},
    watch: {},
    computed: {}
}


</script>

<style lang="scss" scoped>

@import "resources/css/admin-vars";

@media screen and (max-width: 500px) {
    .d-only {
        display: none;
    }
    h1 {
        margin-bottom: 8px;
        text-align: left;
    }
    h2 {
        text-align: left;
    }
}

@media screen and (min-width: 500px) {
    .m-only {
        display: none;
    }

    h1 {
        line-height: 0;
        text-align: center;
        margin-bottom: 8px;
    }

    h2 {
        margin-bottom: 10px;
        text-align: center;
    }

}

h1 {

    font-weight: 600;
}

h2 {

    font-size: 16px;
    font-weight: 600;
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
        padding-top: 30px
    }
    @media screen and (max-width: 500px) {
        padding-top: 0;
        border-top: none;
        box-shadow: none;

    }

}

.tabs-fake {
    margin-bottom: 0;
}

.block-bordered-2 {

    @media screen and (min-width: 500px) {
        margin-top: 10px;
    }
    @media screen and (max-width: 500px) {
        border: none;
        padding-left: 0;
        padding-right: 0;
    }
}

.summary {
    margin: 0 0 10px 0;
    font-weight: 600;

    &:last-child {
        margin: 0;
    }

    .value {
        font-family: 'Inter', sans-serif, serif;
        margin-left: 10px;

        &.color-green {
            color: #1AB69D;
        }

        &.color-gray {
            color: #818181;
        }
    }
}

.item {
    background: #FAFAFA;
    margin-bottom: 10px;
    padding: 15px;
    border-radius: 7px;

    .answer-indexes {
        line-height: 24px;
        color: #666;
        font-weight: 600;
        margin-bottom: 8px;

        .answer-index {
            font-size: 12px;
            width: 24px;
            height: 24px;
            background: #F1F1F1;
            font-weight: 600;
            box-sizing: content-box;
            display: inline-block;
            text-align: center;
            border-radius: 7px;
            margin: 0 20px 0 5px;

            &.color-green {
                background: $green;
                color: white;
            }

            &.color-red {
                background: $red;
                color: white;
            }
        }
    }


    h3 {
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 13px;
    }

    .label {
        font-style: italic;
        font-weight: 400;
        margin-bottom: 10px;
    }

    .answer {
        font-weight: 600;

        &:before {
            content: '●';
            font-size: 1em;
            margin-right: 10px;
        }
    }

    .description {
        font-size: 12px;
        font-weight: 400;
    }

}


</style>
