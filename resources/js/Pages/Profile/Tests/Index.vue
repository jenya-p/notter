<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import ProfileTabs from "@/Pages/Profile/Partials/ProfileTabs.vue";
import Field from "@/Components/Field.vue";
import {useForm, usePage, Link} from "@inertiajs/vue3";

export default {
    components: {Link, Field, ProfileTabs, GuestLayout},
    props: {
        user: {
            type: Object,
        },
    },
    data: () => {
        let user = usePage().props.auth.user;
        return {
            user: user,
            form: useForm({
                name: user.name,
                email: user.email,
            })
        }
    }
}


</script>

<template>
    <GuestLayout wrapper-class="profile-page">

        <div class="block">
            <ProfileTabs/>
        </div>
        <div class="block">

            <div class="table-wrapper">


                <table class="table">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Действует до</th>
                        <th colspan="2">Статистика решенных</th>
                        <th>Состояние</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr href="">
                        <td>Нотариальный тест (Блок 1)</td>
                        <td class="font-inter">4 апр. 2024 г.</td>
                        <td class="color-green font-inter">57 (98%)</td>
                        <td class="color-red font-inter">3 (2%)</td>
                        <td class="color-green">Активен</td>
                    </tr>
                    <tr href="">
                        <td>Нотариальный тест (Блок 2)</td>
                        <td class="font-inter">1 янв. 2024 г.</td>
                        <td class="color-green font-inter">57 (98%)</td>
                        <td class="color-red font-inter">3 (2%)</td>
                        <td class="color-red">Окончен</td>
                    </tr>
                    <tr href="">
                        <td>Список вопросов выносимый на зачет</td>
                        <td class="font-inter">4 апр. 2024 г.</td>
                        <td class="color-green font-inter">57 (98%)</td>
                        <td class="color-red font-inter">3 (2%)</td>
                        <td class="color-green">Активен</td>
                    </tr>
                    </tbody>
                </table>

            </div>


        </div>
    </GuestLayout>
</template>

<style>

</style>
