<template>
    <AdminLayout :title="'Тестирование №' + item.id"
                 :breadcrumb="[{link: route('admin.test.index'), label: 'Тестирования'}, 'Тестирование №' + item.id]">

        <div class="block-wrapper">
        <form method="post" @submit.prevent="submit" class="block" v-field-container>
            <h2>Основная информация</h2>
            <field label="Пользователь" class="field-display">
                <Link :href="route('admin.user.edit', {user: item.user_id})">{{item.user.email}} {{item.user.name ? ' (' + item.user.name + ')': '' }}</Link>
            </field>

            <field label="Блок" class="field-display">
                <Link :href="route('admin.block.edit', {block: item.block_id})">{{item.block.title}}</Link>
            </field>

            <field label="Оплата" class="field-display">
                нет
<!--                <Link :href="route('admin.user.edit', {user: item.user_id})">{{item.user_name}}</Link>-->
            </field>


            <field label="Прохождение">
                <table class="values">
                    <tr>
                        <th></th>
                        <th>Всего</th>
                        <th>Зачет</th>
                        <th>Незачет</th>
                        <th>Осталось</th>
                    </tr>
                    <tr>
                        <th>Билеты</th>
                        <td>{{currency(item.ticket_count, true)}}</td>
                        <td class="color-green">{{currency(item.ticket_passed_count, true)}}</td>
                        <td class="color-red">{{currency(item.ticket_failed_count, true)}}</td>
                        <td class="color-gray">{{currency(item.ticket_count - item.ticket_passed_count - item.ticket_failed_count, true)}}</td>
                    </tr>
                    <tr>
                        <th>Вопросы</th>
                        <td>{{currency(item.question_count, true)}}</td>
                        <td class="color-green">{{currency(item.question_right_count, true)}}</td>
                        <td class="color-red">{{currency(item.question_wrong_count, true)}}</td>
                        <td class="color-gray">{{currency(item.question_skipped_count, true)}}</td>
                    </tr>
                </table>

            </field>


            <field label="Пройдено" class="field-display">
                {{item.completed_at !== null ? date(item.completed_at): 'нет'}}
            </field>

            <field :errors="form.errors" for="available_till" label="Доступно до">
                <input class="input" v-model="available_till" v-mask="'00.00.0000'" />
            </field>

            <div class="block-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
<!--                <History type="user"/>-->
            </div>

        </form>
        </div>
    </AdminLayout>
</template>

<script>
import {Link, useForm} from '@inertiajs/vue3';
import Authenticated from '@/Layouts/AdminLayout.vue';
import Field from "@/Components/Field.vue";
import Password from "@/Pages/Admin/User/Password.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Attachments from "@/Components/Attachments.vue";
import currency from "@/Filters/currency.js";
import date from "@/Filters/date.js";
import _isEmpty from "lodash/isEmpty";

export default {
    components: {Attachments, Checkbox, TextareaAutosize, AdminLayout, Password, Field, Authenticated, Link},
    props: {
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        let $v = this;
        let dateFormat = function(fldName){
            if($v.item && !_isEmpty($v.item[fldName])){
                let v = $v.item[fldName];
                return v.substring(8,10) + '-' +
                    v.substring(5,7) + '-' +
                    v.substring(0,4);
            }
            return null;
        }

        return {
            form: useForm(this.item ? this.item : {
                available_till: null
            }),
            available_till:dateFormat('available_till'),
        }
    },


    methods: {
        submit() {
            this.form.put(route('admin.test.update', {test: this.item.id}));
        },
        currency: currency,
        date: date,
    },

    watch: {
        available_till(v){
            if(!_isEmpty(v)){
                return this.form.available_till =
                    v.substring(6,10) + '-' +
                    v.substring(3,5) + '-' +
                    v.substring(0,2);
            }
            this.form.available_till = null;
        }
    }

}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";
.values{
    width: 100%;
    max-width: 450px;
    form:not(.vertical) &{
        margin-top: 5px;
    }
    tr:first-child th{
        border-top: 1px solid $border-color;
    }
    td, th{
        text-align: center;
        padding: 5px 5px;
        border-bottom: 1px solid $border-color;
        width: 25%
    }
    th:first-child{text-align: left;padding-left: 0;width: 50px;}
    th{font-size: 0.9em;}
    td{font-weight: bold;white-space: nowrap;}
    .color-gray{color: $lightForeColor}
}


</style>

