<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import Checkbox from '@/Components/Checkbox.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    confirm: false
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout wrapper-class="auth-page">

        <form @submit.prevent="submit" class="block">

            <h1>Регистрация</h1>

            <div class="input-group" :class="{'has-error': form.errors.email}">
                <input type="text"
                       placeholder="E-mail"
                       class="input input-email"
                       v-model="form.email"
                       required
                       autocomplete="username"/>
                <input-error :message="form.errors.email"/>
            </div>

            <div class="input-group" :class="{'has-error': form.errors.password}">
                <input type="password"
                       placeholder="Пароль"
                       class="input input-password"
                       v-model="form.password"
                       required
                       autocomplete="new-password"/>
                <input-error :message="form.errors.password"/>
            </div>

            <div class="input-group" :class="{'has-error': form.errors.password_confirmation}">
                <input type="password"
                       placeholder="Пароль"
                       class="input input-password"
                       v-model="form.password_confirmation"
                       required
                       autocomplete="new-password"/>
                <input-error :message="form.errors.password_confirmation"/>
            </div>

            <div class="input-group" :class="{'has-error': form.errors.confirm}">
                <Checkbox v-model="form.confirm" class="confirm-checkbox">Я принимаю условия <a href="javascript:;">пользовательского соглашения</a></Checkbox>
                <input-error :message="form.errors.confirm"/>
            </div>

            <button type="submit" class="btn btn-green">Зарегистрироваться</button>

        </form>
        <div class="block">
            Уже зарегистрированы? <Link :href="route('login')">Войдите</Link>
        </div>
    </GuestLayout>
</template>

<style>
.confirm-checkbox{
    display: flex;
    align-items: center;
}
</style>
