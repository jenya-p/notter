<script>
import {Link} from '@inertiajs/vue3';
import {Head} from "@inertiajs/vue3";
export default {
    components: {Link, Head},
    props: ['wrapperClass', 'title'],
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
    },
    methods: {
        pressEsc (evt) {
            console.log('pressEsc');
            if (evt.key === "Escape") {
                this.closePopupMenu();
            }
        },
        openPopupMenu(){
            this.$refs.headerPopupMenu.style.display = null;
            let $v = this;
            setTimeout(function(){
                $v.$refs.headerPopupMenu.classList.add('opened');
            }, 0);
            document.addEventListener('keydown', this.pressEsc);
        },
        closePopupMenu(){
            if(this.$refs.headerPopupMenu.style.display == 'none'){
                return;
            }
            document.removeEventListener('keydown', this.pressEsc);
            this.$refs.headerPopupMenu.classList.remove('opened');
            let $v = this;
            setTimeout(function(){
                $v.$refs.headerPopupMenu.style.display = 'none';
            }, 300);
        }

    }
}
</script>

<template>
    <div class="lay-wrapper">
        <Head :title="title" />
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
                    <li>
                        <Link  v-if="$page.props.auth.user" href="/profile-test">Тесты</Link>
                        <Link  v-else href="/prices">Тесты</Link>
                    </li>
                    <li><Link href="/prices">Цены</Link></li>
                    <li><Link href="/about">О нас</Link></li>
                </ul>
            </div>
            <header>
                <div class="header-side-placeholder">
                    <Link href="/"><img src="/images/logo.svg" class="header-logo" alt="" /></Link>
                </div>
                <ul class="header-menu">
                    <li>
                        <Link  v-if="$page.props.auth.user" href="/profile-test">Тесты</Link>
                        <Link  v-else href="/prices">Тесты</Link>
                    </li>
                    <li><Link href="/prices">Цены</Link></li>
                    <li><Link href="/about">О нас</Link></li>
                </ul>
                <div class="header-side-placeholder">
                    <template v-if="$page.props.auth.user">
                        <a @click.stop="openPopupMenu" href="javascript:;" class="header-user">
                            <span>{{ $page.props.auth.user.display_name }}</span>
                        </a>
                        <ul class="header-popup-menu" ref="headerPopupMenu" v-click-outside="closePopupMenu" style="display: none">
                            <li v-if="$page.props.auth.user && $page.props.auth.user.is_admin" class="admin"><a href="/admin" target="_blank">Админка</a></li>
                            <li><Link :href="route('profile-test.index')">Мои тесты</Link></li>
                            <li><Link :href="route('profile-payment.index')">Финансы</Link></li>
                            <li><Link :href="route('profile.edit')">Профиль</Link></li>
                            <li><Link :href="route('logout')" method="get">Выйти</Link></li>
                        </ul>
                    </template>
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
            <li><Link href="/backfeed">Обратная связь</Link></li>
            <li><Link href="/agreement">Соглашение</Link></li>
            <li><Link href="/contacts">Контакты</Link></li>
            <li v-if="$page.props.auth.user && $page.props.auth.user.is_admin"><a href="/admin" target="_blank">Админка</a></li>
            <li v-if="$page.props.auth.user"><Link :href="route('logout')" method="get">Выйти</Link></li>
        </ul>
    </footer>
</template>

<style src="@/../css/guest-layout.scss" lang="scss" />
