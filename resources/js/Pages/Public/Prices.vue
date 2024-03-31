<template>
    <GuestLayout>
        <div class="block">
            <h1>Подготовка к экзамену на адвоката</h1>

            <div class="item">
                <div class="item-left">
                    <h3>Тест на статус адвоката 2024 в последней редакции (60 билетов по 20 вопросов)</h3>
                    <p>Вопросы теста идентичны реальному тесту на адвоката 2024. 359 вопросов адвокатского теста с
                        ответами по новой программе</p>
                    <a href="" class="link-more">Подробнее</a>
                </div>
                <div class="item-right">
                    <div class="price">{{ currency(40000, 'auto') }} руб.</div>
                    <p class="price-description">доступ на 1 месяц со дня покупки</p>
                    <a href="" class="btn btn-sm btn-green-dark btn-buy">Купить</a>
                </div>
            </div>


            <div class="item">
                <div class="item-left">
                    <h3>Список вопросов выносимый на зачет</h3>
                    <p>Вопросы теста идентичны реальному тесту на адвоката 2024. 359 вопросов адвокатского теста с
                        ответами по новой программе</p>
                    <a href="" class="link-more">Подробнее</a>
                </div>
                <div class="item-right">
                    <div class="price">{{ currency(4000, 'auto') }} руб.</div>
                    <p class="price-description">доступ на 1 месяц со дня покупки</p>
                    <a href="" class="btn btn-sm btn-green-dark btn-buy">Купить</a>
                </div>
            </div>

        </div>

    </GuestLayout>
</template>

<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import currency from "@/Filters/currency.js";

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
            width: 58%;
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


}

</style>
