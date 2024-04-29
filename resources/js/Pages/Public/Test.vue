<template>
    <GuestLayout wrapper-class="profile-page" :title="test.title">
        <h1 class="d-only">{{ test.title }}</h1>
        <div class="block block-1">

            <h1 class="m-only">{{ test.title }}</h1>
            <NumberLine v-model="ticketIndex" :count="tickets.length" class="number-line--gray" label="Билет"
                        :item-class="ticketClasses"/>
        </div>

        <div v-if="ticket.unavailable == true"  class="block block-2">
            <h3 class="unavailable-message">Тестирование недоступно</h3>
        </div>
        <Solve v-else-if="ticket.completed_at == null "
               :ticket="ticket"
               @complete="complete"
               ref="solve"
        />
        <Summary v-else :ticket="ticket"/>

    </GuestLayout>
</template>

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Radio from "@/Components/Radio.vue";
import {ref} from "vue";
import NumberLine from "@/Components/NumberLine.vue";
import {router} from "@inertiajs/vue3";
import Solve from "@/Pages/Public/Solve.vue";
import Summary from "@/Pages/Public/Summary.vue";

export default {
    components: {Summary, Solve, NumberLine, Radio, GuestLayout},
    props: [
        'test'
    ],
    data() {
        return {
            ticketIndex: 1
        }
    },
    methods: {
        async complete() {
            let result = await axios.get(route('test.complete', {ticket: this.ticket.id}));
            if (result.data.result == 'ok') {
                this.test.tickets[this.ticketIndex - 1] = result.data.ticket;
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        },

        ticketClasses(index) {
            if (this.tickets[index - 1].result == 'passed') {
                return 'green';
            } else if (this.tickets[index - 1].result == 'failed') {
                return 'red';
            }
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
        }
    },
    computed: {
        tickets() {
            return this.test.tickets;
        },
        ticket() {
            return this.tickets[this.ticketIndex - 1];
        }
    },
    created() {
        this.ticketIndex = parseInt(this.$attrs.ticketIndex);
        this.$nextTick(function(){
            if(this.ticket.completed_at == null ){
                let index = parseInt(this.$attrs.questionIndex);
                if(index > 0 && index <= this.ticket.questions.length){
                    this.$refs.solve.index = index;
                }
            }
        });

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
    .unavailable-message{
        // color: $red;
        text-align: center;
        margin-bottom: 25px;
    }
}


::v-deep(.number-line--gray) {
    .number-line-inner {

        a {
            color: #999;

            &.red {
                background: $red;
                color: white;
                border-color: $red;
            }

            &.green {
                background: $green;
                color: white;
                border-color: $green;
            }
        }

        span {
            background: white;
            color: black;

            &.red {
                border-color: $red;
            }

            &.green {
                border-color: $green;
            }
        }

    }

}

::v-deep(.number-line--white) {
    margin-bottom: 25px;

    .number-line-inner {
        a {
            color: #333;
            border: 1px solid #f1f1f1;
            padding: 1px;
            background: #f1f1f1;

            &.green {
                background: $green;
                border-color: $green;
                color: white;
            }
        }

        span {
            border: 2px solid #DADADA;
            background: white;
            color: black;
            padding: 0px;

            &.green {
                border-color: $green;
            }
        }
    }
}

</style>
