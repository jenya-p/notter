<template>
    <AdminLayout title="Результаты" :breadcrumb="[{label: 'Результаты'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по пользователю и теме"
                       v-model.lazy="filter">
                <Link :href="route('admin.test.create')" class="btn btn-sm btn-primary ">
                    <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
                </Link>
            </div>

            <table class="table">
                <thead class=" text-primary">
                <tr>
                    <th class="id">
                        <sort name="id" v-model="sort">№</sort>
                    </th>
                    <th class="name">
                        <sort name="user" v-model="sort">Пользователь</sort>
                    </th>
                    <th class="title">
                        <sort name="title" v-model="sort">Блок</sort>
                    </th>
                    <th class="status">Статус</th>
                    <th class="process">Пройдено</th>
                    <th class="created_at">
                        <sort name="created_at" v-model="sort">Создано</sort>
                    </th>
                    <th class="available_till">
                        <sort name="available_till" v-model="sort">Доступно до</sort>
                    </th>

                </tr>
                </thead>
                <tbody>
                <tr v-for="item of items.data" @click="itemClick(item)" class="cursor-pointer">
                    <td>
                        {{item.id}}
                    </td>
                    <td class="user">
                        <div class="primary">{{ item.user.email }}</div>
                        <div v-if="item.user.display_name != ''" class="secondary">{{ item.user.name }}</div>
                    </td>
                    <td class="title">
                        {{ item.title }}
                    </td>
                    <td class="status">
                        <span class="badge" :class="'badge-' + item.status">{{ item.status_name }}</span>
                    </td>
                    <td class="process">
                        {{item.ticket_count !== 0 ? Math.round(100 * (item.ticket_passed_count + item.ticket_failed_count) / item.ticket_count) + ' %' : ''}}
                    </td>
                    <td class="created_at">
                        <span class="primary">{{
                            $filters.date(item.created_at, 'dd MMM yyyy HH:mm')
                        }}</span>
                    </td>
                    <td class="created_at">
                        {{
                            item.available_till ? $filters.date(item.available_till, 'dd MMM yyyy') : ''
                        }}
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
        if (page == null) {
            page = 1;
        }
        let filter = u.get('filter');
        let sort = u.get('sort');

        if (sort != null) {
            sort = sort.split(':');
            if (_isArray(sort) && sort.length == 2) {
                sort = {name: sort[0], dir: sort[1]};
            } else {
                sort = null;
            }
        }

        return {
            page: 1,
            sort: sort,
            filter: filter
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.test.edit', {test: item.id}))
        },
        refreshPage: _debounce(function () {
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
        page() {
            this.refreshPage();
        },
        filter() {
            this.page = 1;
            this.refreshPage();
        },
        sort() {
            this.page = 1;
            this.refreshPage();
        },
    },


}
</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

.table {
    td, th {
        .secondary {
            color: $lightForeColor;
            font-size: 0.8em;
        }

        &.user {
            width: 200px;
        }


        &.title {

        }

        &.created_at, &.available_till {
            width: 150px;
        }

        &.status {
            width: 20px;
            i {
                display: inline-block;
                color: white;
                font-family: Inter;
                font-size: 12px;
                width: 10px;
                height: 10px;
                line-height: 10px;
                text-align: center;
                border-radius: 50%;
                background: $red;
            }
        }
        &.process {
            text-align: center;
        }
    }


    .badge {

        font-size: 0.8em;
        background: #f0f0f0;
        padding: 2px 5px;
        border-radius: 3px;
        &-active {
            color: white;
            background: $green;
        }
        &-finished {
            color: #8b8b8b;
            background: #fffccf;
        }
        &-done {
            color: $green;
        }
        &-unavailable {
            background: #505050;
            color: #e0e0e0;
        }
    }




}
</style>

