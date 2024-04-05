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
                        <td class="font-inter"><span class="m-label">Число: </span><span class="nobr">01/03/2024 16:31</span></td>
                        <td class="m-title">Покупка (Название теста № блока)</td>
                        <td class="font-inter to-right"><span class="m-label">Сумма: </span><span class="color-red bold">40 000 ₽</span></td>
                    </tr>
                    <tr>
                        <td class="font-inter"><label class="m-label">Число: </label><span class="nobr">01/03/2024 16:31</span></td>
                        <td class="m-title">Покупка (Название теста № блока) (Название теста № блока) (Название теста № блока) (Название теста № блока) (Название теста № блока) (Название теста № блока)</td>
                        <td class="font-inter to-right"><span class="m-label">Сумма: </span><span class="color-red bold">4 000 ₽</span></td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </GuestLayout>
</template>

<style lang="scss" scoped>

</style>
