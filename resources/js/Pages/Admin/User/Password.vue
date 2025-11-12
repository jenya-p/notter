<script>
import {Head, Link, useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";


export default {
    components: {Field},
    props: {
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            form: useForm({
                current_password: '',
                password: '',
                password_confirmation: '',
            })
        }
    },


    methods: {
        submit() {
            let $v = this;
            this.form.put(route('password.update'), {
                preserveState: true,
                preserveScroll: true,
                onFinish: function () {
                    $v.form.reset('current_password', 'password', 'password_confirmation')
                },
                onSuccess: function () {
                    $v.form.reset('current_password', 'password', 'password_confirmation')
                    alert('Пароль изменен');
                },
            });
        }
    }

}
</script>

<template>
    <form method="post" @submit.prevent="submit"  class="block" v-field-container>
        <h2>Изменить пароль</h2>

        <field :errors="form.errors" for="current_password" label="Текущий пароль">
            <input type="password" class="input" v-model="form.current_password"/>
        </field>

        <field :errors="form.errors" for="password" label="Новый пароль">
            <input type="password" class="input" v-model="form.password"/>
        </field>

        <field :errors="form.errors" for="password_confirmation"
               label="Пароль" description="Повторите ввод пароля">
            <input type="password" class="input" v-model="form.password_confirmation"/>
        </field>

        <div class="block-footer">
            <button type="submit" class="btn btn-primary">Изменить</button>
        </div>
    </form>
</template>
