<template>
    <AdminLayout title="Результаты" :breadcrumb="[{label: 'Результаты'}]">

        <div class="block">

            <div class="simple-list-filter-wrp">
                <input type="text" class="input" placeholder="Поиск по пользователю и теме"
                       v-model.lazy="filter">
            </div>

            <table class="table">
                <thead class=" text-primary">
                <tr>

                    <th class="name">
                        <sort name="name" v-model="sort">Пользователь</sort>
                    </th>

                    <th class="subject">
                        <sort name="subject" v-model="sort">Тестирование</sort>
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
                    <td  class="user">
                        {{ item.user.email }}
                        <div v-if="item.email" class="secondary">{{item.name}}</div>
                    </td>
                    <td class="title">
                        <i v-if="item.title" class="fa fa-paperclip" :title="item.attachment_count + ' вложений'"/>
                    </td>

                    <td  class="created_at">
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
            this.$inertia.visit(route('admin.backfeed.edit', {backfeed: item.id}))
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
.table{
    td,th {
        &.name{
            width: 200px;
        }
        &.email{
            width: 200px;
        }
        &.subject{

        }
        &.created_at{
            width: 150px;
        }
        &.attachments{
            width: 20px; text-align: right;
            color: $lightForeColor
        }
        &.status{
            width: 20px;
            i{
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
    }
}
</style>

