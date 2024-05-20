<template>
    <AdminLayout :title="'Платеж №' + item.id + ' от ' + $filters.date(item.created_at, 'dd MMM yyyy')"
                 :breadcrumb="[{link: route('admin.payment.index'), label: 'Платежи'}, '№' + item.id + ' от ' + $filters.date(item.created_at, 'dd MMM yyyy')]">

        <div class="block-wrapper">
        <form method="post" @submit.prevent="submit" class="block" v-field-container>
            <h2>Основная информация</h2>
            <field label="Пользователь" class="field-display">
                <Link :href="route('admin.user.edit', {user: item.user_id})">
                    {{ item.user.email }}
                    <template v-if="item.user.display_name != ''" >({{ item.user.name }})</template>
                </Link>
            </field>

            <field label="Статус" class="field-display">
                <span class="badge" :class="'badge-' + item.status">{{item.status_name}}</span>
            </field>

            <field label="Yoomoney ID" class="field-display">
                {{item.external_id}}
            </field>

            <field label="Оплачено" class="field-display" v-if="item.payed_at">
                {{$filters.date(item.payed_at)}}
            </field>
            <field label="Оплачено" class="field-display" v-else>&nbsp;</field>

            <field class="field-display">
                <table class="table">
                    <thead>
                    <tr>
                        <th colspan="2"><h2>Блоки</h2></th>
                        <th class="amount">Сумма</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(subItem, i) of item.items">
                        <td>{{i + 1}}</td>
                        <td>{{subItem.title}}</td>
                        <td class="amount">{{$filters.currency(subItem.amount, 2)}} ₽</td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: right; padding-right: 20px">Итого</td>
                        <td class="amount" style="font-size: 1.2em; font-weight: bold">{{$filters.currency(item.amount, 2)}} ₽</td>
                    </tr>
                    </tfoot>
                </table>
            </field>

            <div class="block-footer">
                <a @click="back" class="btn btn-primary">Назад</a>
<!--                <History type="user"/>-->
            </div>

        </form>

        </div>
    </AdminLayout>
</template>

<script>
import {Link} from '@inertiajs/vue3';
import Authenticated from '@/Layouts/AdminLayout.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Field from "@/Components/Field.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";


export default {
    components: {TextareaAutosize, AdminLayout,  Field, Authenticated, Link},
    props: {
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        return {

        }
    },


    methods: {
        back(){
            window.history.back();
        }
    },

    computed: {

    }

}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";
.badge{
    font-size: 1em;
    margin-left: -5px;
    &-done {
        color: $green;
    }
    &-canceled {
        color: #8b8b8b;
        background: #fffccf;
    }
    &-new {
        color: white;
        background: $green;
    }
}
.subitem{
    margin: 0;
    &+&{
        margin-top: 5px;
    }
}

table.table{
    width: 100%;
    .amount{
        text-align: right;
    }
    h2{margin: 0; border: 0}
}

</style>

