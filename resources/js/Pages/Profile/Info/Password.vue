<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import ProfileTabs from "@/Pages/Profile/Partials/ProfileTabs.vue";
import Field from "@/Components/Field.vue";
import {useForm, usePage, Link} from "@inertiajs/vue3";
import ProfileInfoTabs from "@/Pages/Profile/Info/Tabs.vue";

export default {
    components: {Link, Field, ProfileTabs, ProfileInfoTabs, GuestLayout},
    props: {
        mustVerifyEmail: {
            type: Boolean,
        },
        status: {
            type: String,
        },
    },
    data: () => {
        let user = usePage().props.auth.user;
        return {
            user: user,
            form: useForm({
                current_password: '',
                password: '',
                password_confirmation: '',
            })
        }
    }
}


</script>

<template>
    <GuestLayout wrapper-class="profile-page">

        <div class="block">
            <ProfileTabs active="profile"/>
        </div>
        <div class="block">

            <ProfileInfoTabs/>
            <div class="block-bordered">
                <form @submit.prevent="form.patch(route('profile.update'))" class="form-wrapper">
                    <Field :errors="form.errors" for="current_password">
                        <input class="input input-password" v-model="form.current_password" placeholder="Текущий пароль">
                    </Field>
                    <Field :errors="form.errors" for="password">
                        <input class="input input-password" v-model="form.password" placeholder="Новый пароль">
                    </Field>
                    <Field :errors="form.errors" for="password_confirmation">
                        <input class="input input-password" v-model="form.password_confirmation" placeholder="Подтверждение пароля">
                    </Field>

                    <button type="submit" class="btn btn-sm btn-green">Изменить пароль</button>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>

