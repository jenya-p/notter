<template>
    <AdminLayout title="Платежи" :breadcrumb="['Платежи']">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по пользователю"
                       v-model.lazy="filter">
            </div>

            <table class="table">
                <thead class="m-hide">
                <tr>
                    <th class="id">
                        <sort name="id" v-model="sort">№</sort>
                    </th>
                    <th class="status">
                        <sort name="status" v-model="sort">Статус</sort>
                    </th>
                    <th class="name">
                        <sort name="user" v-model="sort">Пользователь</sort>
                    </th>
                    <th class="items">
                        Блок(и)
                    </th>
                    <th class="amount">
                        <sort name="amount" v-model="sort">Сумма</sort>
                    </th>
                    <th class="created_at">
                        <sort name="created_at" v-model="sort">Дата</sort>
                    </th>
                    <th class="td-actions text-right">
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item of items.data" @click="itemClick(item)" class="cursor-pointer">
                    <ttd class="id m-title d-hide">
                        <span>{{ item.id }} от {{ $filters.date(item.created_at, 'dd MMM yyyy') }}</span>
                        <span class="badge" :class="'badge-' + item.status">{{ item.status_name }}</span>
                    </ttd>
                    <td class="id m-hide">
                        {{ item.id }}
                    </td>
                    <td class="status m-hide">
                        <span class="badge" :class="'badge-' + item.status">{{ item.status_name }}</span>
                    </td>
                    <ttd class="name d-hide" label="Email">
                        {{ item.user.email }}
                    </ttd>
                    <ttd class="name d-hide" label="Имя" v-if="item.user.display_name != ''">
                        {{ item.user.display_name }}
                    </ttd>
                    <td class="name m-hide">
                        <div class="primary">{{ item.user.email }}</div>
                        <div v-if="item.user.display_name != ''" class="secondary">{{ item.user.display_name }}</div>
                    </td>
                    <ttd class="items" label="Блоки">
                        <p v-for="(subItem, i) of item.items">{{ i + 1 }}. {{ subItem.title }}</p>
                    </ttd>
                    <ttd class="amount text-right" label="Сумма">
                        {{ $filters.currency(item.amount) }}
                    </ttd>
                    <td class="created_at m-hide">
                        {{
                            $filters.date(item.created_at, 'dd MMM yyyy')
                        }}
                        <div class="secondary">
                            {{
                                $filters.date(item.created_at, 'HH:mm')
                            }}
                        </div>
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
import Ttd from "@/Components/table-td.vue";


export default {
    components: {Ttd, TableBottom, Sort, Paginator, Link, AdminLayout},
    props: {
        items: Object
    },
    data() {
        let u = new URLSearchParams(document.location.search);
        let page = u.get('page');
        if (page == null) {
            page = 1;
        }
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
            filter: u.get('filter')
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.payment.show', {payment: item.id}))
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
                },
                onSuccess: (page) => {
                    console.log(page)
                },
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

<style lang="scss">
@import "resources/css/admin-vars";


table.table {
    td.items p {
        margin: 0 0 2px 0;
    }

    @include desktop {
        td, th {
            &.id {
                width: 50px;
            }

            &.status {
                width: 90px;
            }

            &.user {
                width: 200px;
            }

            &.items {
                width: auto;

                p {
                    margin: 0 0 2px 0;
                }
            }

            &.amount {
                width: 100px;
                text-align: right;
                padding-right: 20px;
            }

            &.created_at {
                width: 120px;
            }

            &.td-actions {
                width: 40px;
                text-align: center;
            }
        }
        td.amount {
            padding-right: 40px;
        }
    }

    .badge {
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


}
</style>

