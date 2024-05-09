<template>
    <AdminLayout title="Обратная связь" :breadcrumb="[{label: 'Обратная связь'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по пользователю и теме"
                       v-model.lazy="filter">
            </div>

            <table class="table">
                <thead class=" text-primary">
                <tr>
                    <th class="status">
                        <sort name="status" v-model="sort" style="padding-right: 0.6em">​</sort>
                    </th>
                    <th class="name">
                        <sort name="name" v-model="sort">Имя</sort>
                    </th>
                    <th class="email">
                        <sort name="email" v-model="sort">Email</sort>
                    </th>
                    <th class="attachments">
                    </th>
                    <th class="subject">
                        <sort name="subject" v-model="sort">Тема</sort>
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
                    <td class="status">
                        <i v-if="item.status == 'new'"></i>
                    </td>
                    <td class="name">
                        {{ item.name }}
                    </td>
                    <td class="email">
                        {{ item.email }}
                    </td>
                    <td class="attachments">
                        <i v-if="item.attachment_count" class="fa fa-paperclip"
                           :title="item.attachment_count + ' вложений'"/>
                    </td>
                    <td class="subject">
                        <span>{{ item.subject }}</span>
                    </td>
                    <td class="created_at">
                        {{
                            $filters.date(item.created_at)
                        }}
                    </td>
                    <td class="td-actions text-right">
                        <a class="fa fa-times btn-remove" @click.stop="remove(item)"></a>
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
            this.$inertia.visit(route('admin.backfeed.edit', {backfeed: item.id}))
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
        }),
        async remove(item) {
            let index = this.items.data.findIndex(itm => itm.id === item.id);
            let result = await axios.delete(route('admin.backfeed.destroy', {backfeed: item.id}));
            if (result.data.result == 'ok') {
                this.items.data.splice(index,1);
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        },
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

.table {
    td, th {
        &.name {
            width: 200px;
        }

        &.email {
            width: 200px;
        }

        &.subject {

        }

        &.created_at {
            width: 150px;
        }

        &.attachments {
            width: 20px;
            text-align: right;
            color: $lightForeColor
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
        &.td-actions{
            width: 40px;
            text-align: center;
        }
    }
}
</style>

