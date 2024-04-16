<template>
    <AdminLayout title="Контент" :breadcrumb="[{label: 'Контент'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по названию"
                       v-model="lItems.filter">
            </div>

            <table class="table">
                <thead class=" text-primary">
                <tr>
                    <th>id</th>
                    <th>Заголовок</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of lItems.items" @click="itemClick(item)" class="cursor-pointer">
                    <td>
                        {{ item.id }}
                    </td>
                    <td>
                        {{ item.title }}
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
            lItems: new SimpleList(this, {search: ['name', 'title']}),
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.content.edit', {content: item.id}))
        }
    },
    created() {
        this.lItems.init();
    }

}
</script>

<style lang="scss">
@import "resources/css/admin-vars";
.title {display: flex; gap: 10px; align-items: center;}
.snippet{color: #b7b7b7; white-space: nowrap; overflow: hidden; text-overflow: ellipsis}
</style>

