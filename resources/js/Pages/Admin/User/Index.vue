<template>
    <AdminLayout title="Пользователи" :breadcrumb="[{label: 'Пользователи'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по имени и email"
                       v-model="lItems.filter">
                <Link :href="route('admin.user.create')" class="btn btn-sm btn-primary ">
                    <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
                </Link>
            </div>

            <table class="table">
                <thead class=" text-primary">
                <tr>
                    <th>
                        Имя
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        Роли
                    </th>
                    <th>
                        Дата создания
                    </th>
                    <th class="text-right">

                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of lItems.items" @click="itemClick(item)" class="cursor-pointer">
                    <td>
                        {{ item.name }}
                    </td>
                    <td>
                        {{ item.email }}
                    </td>
                    <td class="roles">
                        <div class="role-badge" v-for="role of item.roles">{{role.name}}</div>
                    </td>
                    <td>
                        {{
                            date_format(item.created_at)
                        }}
                    </td>
                    <td class="td-actions text-right">
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
import date_format from "@/Filters/date_format";
import {SimpleList} from "@/Components/SimpleList";

export default {
    components: {Link, AdminLayout},
    props: {
        items: Array
    },
    data() {
        return {
            lItems: new SimpleList(this, {search: ['name', 'email']})
        }
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.user.edit', {user: item.id}))
        },
        date_format: date_format
    },
    created() {
        this.lItems.init();
    }
}
</script>

<style lang="scss">

</style>
