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

            <div class="tabs-fake" style="margin-bottom: 10px">
                <span>История операций</span>
            </div>

            <div class="table-wrapper">


                <table class="table">
                    <thead>
                    <tr>
                        <th>Число</th>
                        <th>Операция</th>
                        <th class="to-right">Сумма</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td class="font-inter">01/03/2024 16:31</td>
                        <td>Покупка (Название теста № блока)</td>
                        <td class="font-inter color-red bold to-right">4 000 ₽</td>
                    </tr>
                    <tr>
                        <td class="font-inter">01/03/2024 16:31</td>
                        <td>Покупка (Название теста № блока)</td>
                        <td class="font-inter color-red bold to-right">40 000 ₽</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </GuestLayout>
</template>

<style>

</style>
