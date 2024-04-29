<template>
    <AdminLayout title="Пользователи" :breadcrumb="[{label: 'Пользователи'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по имени и email"
                       v-model.lazy="filter">
                <Link :href="route('admin.user.create')" class="btn btn-sm btn-primary ">
                    <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
                </Link>
            </div>

            <table class="table">
                <thead class=" text-primary">
                <tr>
                    <th width="30%">
                        <sort name="name" v-model="sort">Имя</sort>
                    </th>
                    <th width="30%">
                        <sort name="email" v-model="sort">Email</sort>
                    </th>
                    <th width="10%">
                        <sort name="is_admin" v-model="sort">Роль</sort>
                    </th>
                    <th width="15%">
                        <sort name="created_at" v-model="sort">Дата создания</sort>
                    </th>
                    <th class="text-right">

                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of items.data" @click="itemClick(item)" class="cursor-pointer">
                    <td>
                        {{ item.name }}
                    </td>
                    <td>
                        {{ item.email }}
                    </td>
                    <td class="roles">
                        <div class="role-badge" v-if="item.is_admin">Админ</div>
                    </td>
                    <td>
                        {{
                            $filters.date(item.created_at)
                        }}
                    </td>
                    <td class="td-actions text-right">
                    </td>
                </tr>
                </tbody>
            </table>

            <table-bottom>
                <paginator :item-count="items.total" v-model="page" :ipp="items.per_page"></paginator>
            </table-bottom>

        </div>



    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link} from "@inertiajs/vue3";
import Paginator from "@/Components/Paginator.vue";
import Sort from "@/Components/Sort.vue";
import _debounce from "lodash/debounce";
import _isArray from "lodash/isArray";
import TableBottom from "@/Components/TableBottom.vue";



export default {
    components: {TableBottom, Sort, Paginator, Link, AdminLayout},
    props: {
        items: Object
    },
    data() {
        let u = new URLSearchParams(document.location.search);
        let page = u.get('page');
        if(page == null){
            page = 1;
        }
        let filter = u.get('filter');
        let sort = u.get('sort');

        if(sort != null){
            sort = sort.split(':');
            if(_isArray(sort) && sort.length == 2){
                sort = {name: sort[0], dir: sort[1]};
            } else {
                sort = null;
            }
        }

        return{
            page: 1,
            sort: sort,
            filter: filter
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.user.edit', {user: item.id}))
        },
        refreshPage: _debounce(function(){
            var $v = this;
            let sort = this.sort ? (this.sort.name + ':' + this.sort.dir) : '';
            this.$inertia.reload({
                method: 'get',
                replace: true,
                only: ['items'],
                data: {
                    page: this.page,
                    filter: this.filter,
                    sort: sort
                }
            });
        })
    },
    watch: {
        page(){
            this.refreshPage();
        },
        filter(){
            this.page = 1;
            this.refreshPage();
        },
        sort(){
            this.page = 1;
            this.refreshPage();
        },
    },




}
</script>

<style lang="scss">
@import "resources/css/admin-vars";
.role-badge{
    display: inline-block;
    padding: 0 0.6em;
    border-radius: 4px;
    background-color: $green;
    color: white;
    line-height: 1.6em;
    font-size: 0.9em;
}
</style>

