<template>
    <div class="block block-1">
        <h2>Результат билета №{{ticket.order}}</h2>
        <p class="summary">Правильных ответов: <span class="value color-green">{{
                ticket.right_count
            }} ({{ Math.round(100 * ticket.right_count / ticket.question_count) }}%)</span></p>
        <p class="summary">Неправильных ответов: <span class="value color-red">{{
                ticket.wrong_count
            }} ({{ Math.round(100 * ticket.wrong_count / ticket.question_count) }}%)</span></p>
        <p class="summary">Пропущенных: <span class="value color-gray">{{
                ticket.skipped_count
            }} ({{ Math.round(100 * ticket.skipped_count / ticket.question_count) }}%)</span></p>
    </div>

    <div class="block block-2">
        <ul class="tabs">
            <li v-for="tb of tabs" :class="{'active': tb.key===tab}">
                <a @click="tab=tb.key">{{ tb.label }}</a></li>
        </ul>

        <div class="block-bordered block-bordered-2">
            <template v-for="question of ticket.questions">
                <div v-if="question.result === tab || tab == 'all'" class="item">
                    <p class="answer-indexes">
                        <span>Билет <span class="answer-index font-inter">{{ ticket.order }}</span></span>
                        <span>Вопрос <span class="answer-index font-inter" :class="{
                            'color-red': question.result=='wrong',
                            'color-green': question.result=='right'
                            }">{{
                                question.order
                            }}</span></span>
                    </p>
                    <h3>{{ question.question }}</h3>
                    <template v-if="question.result === 'right'">
                        <p class="label">Ваш ответ</p>
                        <p class="answer color-green">{{ question.answer_text }}</p>
                    </template>
                    <template v-else-if="question.result === 'wrong'">
                        <p class="label">Ваш ответ</p>
                        <p class="answer color-red">{{ question.answer_text }}</p>
                        <p class="label">Правильный ответ</p>
                        <p class="answer color-green">{{ question.right_answer_text }}</p>
                    </template>
                    <template v-else>
                        <p class="label">Правильный ответ</p>
                        <p class="answer color-green">{{ question.right_answer_text }}</p>
                    </template>
                    <div class="description content">{{ question.description }}</div>
                </div>
            </template>
        </div>


    </div>

</template>

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Radio from "@/Components/Radio.vue";
import NumberLine from "@/Components/NumberLine.vue";

export default {
    components: {NumberLine, Radio, GuestLayout},
    props: [
        'ticket'
    ],
    data() {
        return {
            tabs: [],
            tab: null
        }
    },
    methods: {
        refresh(){
            let tabs = [];
            let src = {
                wrong: 'Неверные ответы',
                skipped: 'Пропущенные вопросы',
                right: 'Верные ответы',
            };
            for (const key in src) {
                if(this.ticket[key + '_count'] > 0){
                    tabs.push({
                        key: key,
                        label: src[key]
                    })
                }
            }
            if(tabs.length > 1){
                tabs.push({
                    key: 'all',
                    label: 'Все'
                })
            }
            this.tabs = tabs;
            this.tab = tabs.length ? tabs[0].key : null;
            this.$nextTick(function () {
                history.replaceState({}, '', route('test', {
                    test:           this.ticket.test_id,
                    ticketIndex:    this.ticket.order,
                    questionIndex:  'summary'
                }));
            });

        }
    },
    watch: {
        ticket(){
            this.refresh();
        }
    },
    mounted() {
        this.refresh();
    }
}


</script>

<style lang="scss" scoped>

@import "resources/css/admin-vars";
h2 {
    font-size: 16px;
    font-weight: 600;
    @media screen and (max-width: 500px) {
        text-align: left;
    }

    @media screen and (min-width: 500px) {
        margin-bottom: 25px;
        text-align: center;
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
