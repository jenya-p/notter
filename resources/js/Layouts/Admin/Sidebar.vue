<template>
    <aside>
        <div class="logo">
            <img src="/images/logo.svg" alt="">
        </div>

        <div class="sidebar-menu">
            <ul class="nav">
                <li class="nav-item active">
                    <a class="nav-link" href="javascript: ;">
                        <i class="fa fa-users"/>
                        <span>Пользователи</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript: ;">
                        <i class="fa fa-list-check"/>
                        <span>Тесты</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript: ;">
                        <i class="fa-regular fa-folder-open"/>
                        <span>Результаты</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript: ;">
                        <i class="fa fa-money-check-dollar"></i>
                        <span>Платежи</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript: ;">
                        <i class="fa fa-file-text"></i>
                        <span>Контент</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="current-user">
            <span class="username">{{ $page.props.auth.user.display_name }}</span>
            <div class="actions">
                <Link :href="route('admin.user.edit', {user: $page.props.auth.user.id})" class="logout">Профиль</Link>
                <Link :href="route('logout')" class="btn-a logout" method="post" as="button">Выход</Link>
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
        }
    }
}

</script>
