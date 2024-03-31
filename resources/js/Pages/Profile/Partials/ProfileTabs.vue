<script setup>
import {startsWith} from "lodash";
import {Link} from "@inertiajs/vue3";


</script>
<template>
        <ul class="tabs">
            <li :class="{'active': startsWith(route().current(),'profile-test.')}">
                <Link :href="route('profile-test.index')">Мои тесты</Link></li>
            <li :class="{'active': startsWith(route().current(),'profile-finance.') }">
                <Link :href="route('profile-finance.index')">Финансы</Link></li>
            <li :class="{'active': route().current() == 'profile.edit' || route().current() == 'password.edit'}">
                <Link :href="route('profile.edit')">Профиль и настройки</Link></li>
        </ul>
</template>

<style scoped lang="scss">
    ul {
        margin-top: -5px;
        margin-bottom: -3px;
    }
</style>
