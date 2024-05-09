<template>
    <AdminLayout title="Новое тестирование пользователя"
                 :breadcrumb="[{link: route('admin.test.index'), label: 'Тестирования'}, 'Новое тестирование']">

        <div class="block-wrapper">
        <form method="post" @submit.prevent="submit" class="block" v-field-container>
            <h2>Основная информация</h2>
            <field label="Пользователь" :errors="form.errors" for="user_id">
                <vue-multiselect
                    v-model="user"
                    :options="user_options"
                    :allow-empty="false"
                    :taggable="false"
                    @search-change="getUserOptions"
                    :internal-search="false"
                    :searchable="true"
                    track-by="id"
                    label="display_name"
                    placeholder="Выберите пользователя"
                    :show-labels="false">
                    <template #option="props">
                        <span class="option__title">{{ props.option.display_name }}</span>
                        <span class="option__small" v-if="props.option.display_name != props.option.email">{{ props.option.email }}</span>
                    </template>
                </vue-multiselect>
            </field>

            <field label="Блок" :errors="form.errors" for="block_id">
                <vue-multiselect
                    v-model="block"
                    :options="block_options"
                    :allow-empty="false"
                    :taggable="false"
                    track-by="id"
                    label="title"
                    placeholder="Выберите блок вопросов"
                    :show-labels="false" />

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
import {Head, Link, useForm} from '@inertiajs/vue3';
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
import VueMultiselect from "vue-multiselect";


export default {
    components: {Attachments, Checkbox, TextareaAutosize, AdminLayout, Password, Field, Authenticated, Link, VueMultiselect},
    props: {
        item: {
            type: Object,
            default: null
        },
        block_options:{
            type: Array,
            default: []
        },
    },

    data() {
        let $v = this;

        return {
            form: useForm({
                user_id: null,
                block_id: null,
                available_till: null
            }),
            user_options: [],
            user: null,
            available_till: null,
            block: null
        }
    },


    methods: {
        submit() {
            console.log(1);
            this.form.post(route('admin.test.store'));
        },

        getUserOptions(query = null) {
            var $v = this;
            axios.get(route('admin.user.suggest'), {params: {filter: query}, responseType: 'json'})
                .then((responce) => {
                    $v.user_options = responce.data;
                    if (
                        $v.user && $v.user.id != null &&
                        $v.user_options.findIndex(function (itm) {
                            return itm.id == $v.user.id;
                        }) == -1) {
                        $v.user_options.push($v.user);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        currency: currency,
        date: date,
    },

    watch: {
        user(v){
            if(!_isEmpty(v)){
                return this.form.user_id = v.id;
            } else {
                return this.form.user_id = null;
            }
        },
        block(v){
            if(!_isEmpty(v)){
                return this.form.block_id = v.id;
            } else {
                return this.form.block_id = null;
            }
        },
        available_till(v){
            if(!_isEmpty(v)){
                return this.form.available_till =
                    v.substring(6,10) + '-' +
                    v.substring(3,5) + '-' +
                    v.substring(0,2);
            }
            this.form.available_till = null;
        }
    },

    created() {
        this.getUserOptions();
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

.option__small{
    margin-left: 5px; font-size: 0.8em; float: right;
}
</style>

