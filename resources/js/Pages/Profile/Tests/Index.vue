<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import ProfileTabs from "@/Pages/Profile/Partials/ProfileTabs.vue";
import Field from "@/Components/Field.vue";
import {useForm, usePage, Link, router} from "@inertiajs/vue3";

export default {
    components: {Link, Field, ProfileTabs, GuestLayout},
    props: {
        items: {
           type: Array
        }
    },
    data: () => {
        return {

        }
    },
    methods: {
        statusClass(status){
            if(status == 'active'){
                return 'color-green';
            } else {
                return 'color-red';
            }
        },
        statusName(status){
            if(status == 'active'){
                return 'Активен';
            } else {
                return 'Завершен';
            }
        },
        goTo(item){
            router.visit(route('test', {test: item.id}));
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

            <div class="table-wrapper">

                <table class="table">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th class="date">Действует до</th>
                        <th colspan="2">Статистика решенных</th>
                        <th>Состояние</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr @click="goTo(item)" href="" v-for="item of items">
                        <td class="m-title">{{item.title}}</td>
                        <td class="font-inter date">
                            <span class="m-label">Действует до: </span><span class="nobr">{{ $filters.date(item.available_till, 'dd MMM yyyy') }}</span></td>

                        <template v-if="item.question_count">
                            <td class="color-green font-inter d-only nobr w-10">{{ item.right_count }}
                                ({{Math.round(100 * item.right_count / item.question_count)}}%)</td>
                            <td class="color-red font-inter d-only nobr">{{ item.wrong_count }}
                                ({{Math.round(100 * item.wrong_count / item.question_count)}}%)</td>
                            <td class="m-only font-inter">
                                <span class="m-label">Статистика: </span>
                                <span class="color-green">{{ item.right_count }} ({{Math.round(100 * item.right_count / item.question_count)}}%)</span> /
                                <span class="color-red">{{ item.wrong_count }} {{Math.round(100 * item.wrong_count / item.question_count)}}%)</span>
                            </td>
                        </template>
                        <template v-else>
                            <td class="color-green font-inter d-only nobr"></td>
                            <td class="color-red font-inter d-only nobr"></td>
                        </template>

                        <td><span class="m-label">Состояние: </span><span class="status-badge" :class="statusClass(item.status)">{{ statusName(item.status) }}</span></td>
                    </tr>

                    </tbody>
                </table>

            </div>


        </div>
    </GuestLayout>
</template>

<style lang="scss" scoped>
.w-10{width: 10%;}
th:not(:last-child), td:not(:last-child){
    padding-right: 10px;
}
.status-badge:before{
    content: '●';
    margin-right: 5px;
    font-size: 1.2em;
}
</style>
