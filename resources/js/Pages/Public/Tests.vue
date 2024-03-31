<template>
    <GuestLayout wrapper-class="profile-page">
        <div class="block">
            <h1>Нотариальный тест (Блок 1)</h1>
            <h3>Вы приступаете к тесту “Нотариальный тест (Блок 1)”</h3>
            <img src="/images/start-test.svg" alt="">
            <div class="content">
                <ul>
                    <li>Нотариальный тест (Блок 1) должен быть пройден за один раз без перерыва.</li>
                    <li>Если вы решите пройти тест повторно, выделите достаточно времени, чтобы пройти весь тест.</li>
                    <li>Убедитесь, что у вас будет стабильное интернет-соединение на протяжении всей сессии тестирования.</li>
                </ul>
            </div>

            <Link :href="route('test')" class="btn btn-green">Начать</Link>
        </div>

    </GuestLayout>
</template>

<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import {Link} from "@inertiajs/vue3";

</script>

<style lang="scss" scoped>

.block{
    @media (min-width: 500px) {
        text-align: center;
    }

    h1{
        margin-top: 17px;
        font-weight: 600;
    }
    h3 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 70px;
    }

    img{
        margin-bottom: 70px;
        max-width: 100%;
    }
    .content {
        text-align: left;
        max-width: 500px;
        margin: 0 auto 75px auto;
        line-height: 20px;

    }


    @media (min-width: 500px) {
        .btn{
            width: 270px;
        }
    }
}

</style>
