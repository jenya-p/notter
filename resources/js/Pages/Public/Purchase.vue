<template>
    <GuestLayout wrapper-class="profile-page">
        <div class="block">
            <h1>Оплата</h1>
            <div class="block-bordered">

                <p v-for="item of items">{{item.title}} - {{$filters.currency(item.price, 'auto')}}</p>

                <Field label="Сумма" >
                    <span class="input">{{$filters.currency(amount, 'auto')}}</span>
                </Field>

                <Field>

                </Field>


            </div>
        </div>
    </GuestLayout>
</template>

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import {useForm} from "@inertiajs/vue3";
import Field from "@/Components/Field.vue";

export default {
    components: {Field, GuestLayout},
    props: ['items'],
    data(){
        return {
            form: useForm({

            })
        }
    },

    computed: {
        amount(){
            let t = 0;
            for (const item of this.items) {
                t += item.price;
            }
            return t;
        }
    }

}

</script>

<style lang="scss" scoped>

</style>
