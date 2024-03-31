<script>
import {Link} from '@inertiajs/vue3';

export default {
    components: {Link},
    props: ['wrapperClass'],
    mounted() {
        if(this.wrapperClass == 'index-page' || this.wrapperClass == 'content-page'){
            document.body.classList.add('green-decorator');
        } else {
            document.body.classList.remove('green-decorator');
        };

        var closer = this.$refs['sidebar-closer'];
        var opener = this.$refs['sidebar-opener'];
        var sidebar = this.$refs['sidebar'];

        opener.addEventListener('click', function(){
            sidebar.style.display = 'flex';
        });

        closer.addEventListener('click', function(){
            sidebar.style.display = 'none';
        });
    }
}
</script>

<template>
    <div class="lay-wrapper">
        <div class="header-wrapper">
            <div class="sidebar" ref="sidebar">
                <div class="sidebar-header">
                    <a href="/"><img src="/images/logo.svg" class="header-logo" alt=""></a>
                    <a href="javascript:;" class="sidebar-closer" ref="sidebar-closer"></a>
                </div>

                <Link v-if="$page.props.auth.user" :href="route('profile-test.index')" class="sidebar-user">
                    <span>{{ $page.props.auth.user.display_name }}</span>
                </Link>
                <Link v-else :href="route('login')" class="sidebar-user">
                    <span>Войти</span>
                </Link>

                <ul class="sidebar-menu">
                    <li><a href="">Тесты</a></li>
                    <li><a href="">Цены</a></li>
                    <li><a href="">О нас</a></li>
                </ul>
            </div>
            <header>
                <div class="header-side-placeholder">
                    <Link href="/"><img src="/images/logo.svg" class="header-logo" alt="" /></Link>
                </div>
                <ul class="header-menu">
                    <li><Link href="/tests">Тесты</Link></li>
                    <li><Link href="/prices">Цены</Link></li>
                    <li><Link href="/about">О нас</Link></li>
                </ul>
                <div class="header-side-placeholder">
                    <Link v-if="$page.props.auth.user" :href="route('profile-test.index')" class="header-user">
                        <span>{{ $page.props.auth.user.display_name }}</span>
                    </Link>
                    <Link v-else :href="route('login')" class="header-user" title="Войти"></Link>
                    <a href="javascript:;" class="sidebar-opener" ref="sidebar-opener"></a>
                </div>
            </header>
        </div>
        <main>
            <div :class="wrapperClass">
                <slot />
            </div>
        </main>
        <div class="lay-footer-placeholder"></div>
    </div>

    <footer>
        <ul>
            <li><Link href="/about">О сервисе</Link></li>
            <li><Link href="">Обратная связь</Link></li>
            <li><Link href="/agreement">Соглашение</Link></li>
            <li v-if="$page.props.auth.user"><Link :href="route('logout')" method="POST">Выйти</Link></li>
        </ul>
    </footer>
</template>

<style src="@/../css/guest-layout.scss" lang="scss" />
