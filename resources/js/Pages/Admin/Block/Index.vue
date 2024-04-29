<template>
    <AdminLayout title="Блоки тестов" :breadcrumb="[{label: 'Блоки тестов'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по названию"
                       v-model="lItems.filter">
                <Link :href="route('admin.block.create')" class="btn btn-sm btn-primary ">
                    <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
                </Link>
            </div>

            <table class="table">
                <thead class=" text-primary">
                <tr>
                    <th>Название</th>
                    <th>Билетов</th>
                    <th>Вопросов</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of lItems.items" @click="itemClick(item)" class="cursor-pointer">
                    <td>
                        {{ item.title }}
                    </td>
                    <td>
                        {{ item.ticket_count }}
                    </td>
                    <td>
                        {{ item.question_count }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";
import {SimpleList} from "@/Components/SimpleList";



export default {
    components: {Link, AdminLayout},
    props: {
        items: Array
    },
    data() {
        return{
            lItems: new SimpleList(this, {search: ['title']}),
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.block.edit', {block: item.id}))
        }
    },
    created() {
        this.lItems.init();
    }

}
</script>

<style lang="scss">
@import "resources/css/admin-vars";
</style>

