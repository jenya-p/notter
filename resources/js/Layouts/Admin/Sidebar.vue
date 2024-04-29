<template>
    <aside>
        <a href="/" class="logo" target="notter-home">
            <img src="/images/logo.svg" alt="">
        </a>

        <div class="sidebar-menu">
            <ul class="nav">
                <li :class="{active: routeIs('user')}">
                    <Link :href="route('admin.user.index')">
                        <i class="fa fa-users"/>
                        <span>Пользователи</span>
                    </Link>
                </li>
                <li :class="{active: routeIs('block', 'question')}">
                    <Link :href="route('admin.block.index')">
                        <i class="fa fa-list-check"/>
                        <span>Тесты</span>
                    </Link>
                </li>
                <li>
                    <span>
                        <i class="fa-regular fa-folder-open"/>
                        <span>Результаты</span>
                    </span>
                </li>
                <li>
                    <span>
                        <i class="fa fa-money-check-dollar"></i>
                        <span>Платежи</span>
                    </span>
                </li>
                <li :class="{active: routeIs('content')}">
                    <Link :href="route('admin.content.index')">
                        <i class="fa fa-file-text"></i>
                        <span>Контент</span>
                    </Link>
                </li>
            </ul>
        </div>

        <div class="current-user">
            <span class="username">{{ $page.props.auth.user.display_name }}</span>
            <div class="actions">
                <Link :href="route('admin.user.edit', {user: $page.props.auth.user.id})" class="logout">Профиль</Link>
                <Link :href="route('logout')" class="btn-a logout" method="get">Выход</Link>
            </div>
        </div>

    </aside>

</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
    components: {Link},
    data() {
        return {
            curSection: null
        }
    },
    methods: {
        toggle(section) {
            console.log(section);
            if (section == this.curSection) {
                this.curSection = null;
            } else {
                this.curSection = section;
            }
        },
        routeIs(...args){
            for (const arg of args) {
                if(route().current().startsWith('admin.' + arg + '.')){
                    return true;
                }
            }
            return false;
        }
    }
}

</script>
