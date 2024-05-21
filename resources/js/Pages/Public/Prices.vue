<template>
    <GuestLayout>
        <div class="block">
            <h1>Подготовка к экзамену на адвоката</h1>

            <div class="item" v-for="item in blocks">
                <div class="item-left">
                    <checkbox v-model="selected" :value="item.id"><h3>{{ item.title }}</h3></checkbox>
                    <div v-html="item.description" class="content"></div>
                </div>
                <div class="item-right">
                    <template v-if="item.available_till && item.my_active_tests.length == 1">
                        <p>Тестирование доступно <span class="nobr">до <span
                            class="font-inter">{{ date(item.available_till, 'dd MMM yyyy') }}</span></span></p>
                        <Link :href="route('test', {test: item.my_active_tests[0].id})" class="btn btn-sm btn-green-dark btn-buy">Пройти</Link>
                    </template>
                    <template v-else-if="item.my_active_tests.length">
                        <p>Доступно {{ item.my_active_tests.length }}
                            {{ plural(item.my_active_tests.length, 'тестирование', 'тестирования', 'тестирований') }} <span
                                class="nobr">до <span class="font-inter">{{
                                    date(item.available_till, 'dd MMM yyyy')
                                }}</span></span></p>
                        <Link :href="route('test', {test: item.id})" class="btn btn-sm btn-green-dark btn-buy">Мои тесты</Link>
                    </template>
                    <template v-else>
                        <div class="price">{{ $filters.currency(item.price, 'auto') }} руб.</div>
                        <p class="price-description">доступ на 1 месяц со дня покупки</p>
                        <a @click="submit([item.id])" class="btn btn-sm btn-green-dark btn-buy">Купить</a>
                    </template>
                </div>
            </div>

            <div class="item item-total" v-if="selected.length > 0">
                <div class="item-left">
                    <h3>{{ $filters.plural(selected.length, 'Выбран', 'Выбрано', 'Выбрано') }} {{ selected.length }}
                        {{ $filters.plural(selected.length, 'блок', 'блока', 'блоков') }}:</h3>
                    <ul class="description">
                        <li v-for="item in selectedItems">{{ item.title }}</li>
                    </ul>
                </div>
                <div class="item-right">
                    <div class="price">{{ $filters.currency(totalPrice, 'auto') }} руб.</div>
                    <p class="price-description">доступ на 1 месяц со дня покупки</p>
                    <a @click="submit(selected)" class="btn btn-sm btn-green-dark btn-buy">Оплатить все</a>
                </div>
            </div>

        </div>

    </GuestLayout>
</template>

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {router, Link, usePage} from "@inertiajs/vue3";
import date from "@/Filters/date.js"
import plural from "@/Filters/plural.js";
import _isEmpty from "lodash/isEmpty";
export default {
    components: {GuestLayout, Checkbox, Link},
    props: {
        blocks: {type: Array}
    },
    data() {
        return {
            selected: []
        }
    },
    computed: {
        selectedItems() {
            let $v = this;
            return this.blocks.filter(itm => $v.selected.indexOf(itm.id) !== -1);
        },
        totalPrice() {
            let t = 0;

            for (const itm of this.selectedItems) {
                t += itm.price;
            }
            return t;
        }
    },
    methods: {
        async submit(ids){
            const user = usePage().props.auth.user;
            if(user == null){
                router.visit(route('purchase', {ids:ids}));
            } else {
                let result = await axios.post(route('profile-payment.store'), {ids: ids});
                if (result.data.result == 'ok' && !_isEmpty(result.data.redirect_to)) {
                    document.location = result.data.redirect_to;
                } else {
                    alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
                }
            }

        },
        date: date,
        plural: plural
    }

}

</script>

<style lang="scss" scoped>
.block {
    width: 100%;
    max-width: 780px;
    margin: 0 auto;
    @media screen and (min-width: 500px) {
        margin-top: 25px;
        padding-left: 30px;
        padding-right: 30px;
    }
}

h1 {
    margin-bottom: 20px;
}

.item {
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    overflow: hidden;

    @media screen and (max-width: 700px) {
        flex-direction: column;
        margin-left: -10px;
        margin-right: -10px;
    }

    &-left {
        background: #F9F9F9;
        padding: 20px;
        flex-grow: 1;

        h3 {
            margin-bottom: 0.65em;
        }

        p {
            font-size: 12px;
            font-weight: 400;
            margin-bottom: 0.65em;
        }

        .link-more {
            font-size: 16px;
        }


    }

    &-right {
        background: #1AB69D;
        color: white;

        @media screen and (min-width: 700px) {
            flex-grow: 0;
            flex-shrink: 0;
            width: 230px;
        }

        text-align: center;
        padding: 20px;

        .price {
            font-family: Inter;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 0.2em;
            white-space: nowrap;
        }

        .btn-buy {
            width: 100%;
        }

        .price-description {
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 0.9em;
        }


    }

    &.item-total {
        margin-top: 45px;

        ul.description {
            list-style: auto;
            margin-left: 15px;
        }

    }

}


</style>
