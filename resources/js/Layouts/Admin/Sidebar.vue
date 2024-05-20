<template>
    <aside :class="minified ? 'min': 'max'">
        <a class="minify-btn" @click="toggleMinified"></a>
        <a href="/" class="logo" target="notter-home"></a>

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
                        <i class="fa-regular fa-folder-open"></i>
                        <span>Блоки вопросов</span>
                    </Link>
                </li>
                <li :class="{active: routeIs('test')}">
                    <Link :href="route('admin.test.index')">
                        <i class="fa fa-list-check"/>
                        <span>Тестирования</span>
                    </Link>
                </li>
                <li :class="{active: routeIs('payment')}">
                    <Link :href="route('admin.payment.index')">
                        <i class="fa fa-money-check-dollar"></i>
                        <span>Платежи</span>
                    </Link>
                </li>
                <li :class="{active: routeIs('content')}">
                    <Link :href="route('admin.content.index')">
                        <i class="fa fa-file-text"></i>
                        <span>Контент</span>
                    </Link>
                </li>
                <li :class="{active: routeIs('backfeed'), 'with-notification': backfeeds > 0}">
                    <Link :href="route('admin.backfeed.index')">
                        <i class="fa fa-envelope"></i>
                        <span>Обратная связь</span>
                        <span class="notification" v-if="backfeeds > 0">{{ backfeeds }}</span>
                    </Link>
                </li>
            </ul>
        </div>

        <div class="current-user">
            <span class="username">{{ $page.props.auth.user.display_name }}</span>
            <div class="actions">
                <Link :href="route('admin.user.edit', {user: $page.props.auth.user.id})" class="profile">Профиль</Link>
                <a :href="route('logout')" class="btn-a logout">Выход</a>
            </div>
        </div>

    </aside>

</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
    components: {Link},
    data() {
         let minified = false;
        if(window.innerWidth < 980){
            minified = true;
        } else if(localStorage.hasOwnProperty('sidebar-state')) {
            minified = localStorage.getItem('sidebar-state') == 'true';
            console.log(minified)
        }
        return {
            curSection: null,
            minified: minified,
        }
    },
    methods: {
        toggle(section) {
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
        },
        toggleMinified(){
            this.minified = !this.minified;
            if(window.innerWidth > 980){
                localStorage.setItem('sidebar-state', this.minified);
            }
        }

    },
    computed: {
        backfeeds(){
            return this.$page.props.notifications.backfeed;
        }
    }

}

</script>
