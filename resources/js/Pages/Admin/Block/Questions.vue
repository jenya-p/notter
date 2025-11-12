<template>
    <div class="simple-list-filter-wrp">
        <div class="ticket-filter">
            <VueMultiselect
                :options="tickets"
                v-model="ticket"
                placeholder="Билет"
                label="ticket"
                track-by="ticket"
                :showLabels="false"
            >
                <template v-slot:singleLabel="prop">
                    <span><b>№{{ prop.option.ticket }}</b> / {{
                            prop.option.count
                        }} {{ $filters.plural(prop.option.count, 'вопрос', 'вопроса', 'вопросов') }}</span>
                </template>
                <template v-slot:option="prop">
                    <span><b>№{{ prop.option.ticket }}</b> / {{
                            prop.option.count
                        }}  {{ $filters.plural(prop.option.count, 'вопрос', 'вопроса', 'вопросов') }}</span>
                </template>
            </VueMultiselect>
        </div>

        <div class="query-wrp">
            <input type="text" class="input" placeholder="Поиск по тексту вопроса"
                   v-model="lItems.filter">
            <a class="fa fa-eraser filter-erase" @click="lItems.filter = ''" title="Очистить"></a>
        </div>
        <Link :href="route('admin.question.create', {block_id: this.item.id})" class="btn btn-sm btn-primary ">
            <i class="fa fa-plus" style="font-size: 0.8em; margin-right: 8px"></i>Добавить
        </Link>
    </div>

    <table class="table" v-if="filtered">
        <thead class="m-hide">
        <tr>
            <th class="to-center">Билет</th>
            <th class="to-center order">№п/п</th>
            <th>Вопрос</th>
            <th class="to-center">Варианты</th>
            <th class="remove"></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="element of lItems.items" @click="itemClick(element)" class="cursor-pointer">
            <td class="d-hide m-title">
                {{ element.text }}
                <a class="fa fa-times btn-remove" @click.stop="remove(element)"></a>
            </td>
            <ttd class="to-center" label="Билет">
                {{ element.ticket }}
            </ttd>
            <ttd class="to-center order" label="Номер п/п">
                {{ element.order }}
            </ttd>
            <td class="m-hide">
                {{ element.text }}
            </td>
            <ttd class="to-center" label="Ответы">
                {{ element.options.length }}
            </ttd>
            <td class="remove m-hide">
                <a class="fa fa-times btn-remove" @click.stop="remove(element)"></a>
            </td>
        </tr>
        </tbody>
    </table>


    <template v-else v-for="ticket1 in tickets">
        <table class="table reordering" v-if="ticket == null || (ticket?.ticket ?? -1) === ticket1.ticket">
            <thead class="m-hide">
            <tr>
                <th class=""><h4>Билет {{ ticket1.ticket }}</h4></th>
                <th class="to-center order">№п/п</th>
                <th>Вопрос</th>
                <th class="to-center">Варианты</th>
                <th class="remove"></th>
            </tr>
            </thead>
            <h2 class="d-hide">Билет {{ ticket1.ticket }}</h2>
            <draggable tag="tbody"
                       handle=".handler"
                       v-model="ticket1.questions"
                       ghost-class="ghost"
                       animation="200"
                       item-key="id"
                       @end="reorder"
                       group="questions">
                <template #item="{element, index}">
                    <tr @click="itemClick(element)" class="cursor-pointer">
                        <td class="handler m-hide"><i class="fa fa-bars"></i></td>
                        <td class="to-center m-hide" v-if="element.order != index + 1">
                            <b :title="'Старое значение: ' + element.order">{{ index + 1 }}*</b>
                        </td>
                        <td class="to-center m-hide" v-else>
                            {{ index + 1 }}
                        </td>
                        <td class="m-hide">
                            {{ element.text }}
                        </td>
                        <td class="d-hide handler">
                            <i class="fa fa-bars"></i>
                            {{ element.text }}
                            <a class="fa fa-times btn-remove" @click.stop="remove(element)"></a>
                        </td>
                        <td class="to-center m-hide">
                            {{ element.options.length }}
                        </td>
                        <td class="remove m-hide">
                            <a class="fa fa-times btn-remove" @click.stop="remove(element)"></a>
                        </td>
                    </tr>
                </template>
            </draggable>
        </table>
    </template>

    <table-bottom align="left" v-if="!filtered && reordered">
        <a class="btn btn-primary btn-xs" @click="saveOrder">Сохранить порядок</a>
        <a class="btn btn-gray btn-xs" @click="restoreOrder"><i class="fa fa-undo"/> Отменить</a>
    </table-bottom>
</template>

<script>
import {Link} from "@inertiajs/vue3";
import {SimpleList} from "@/Components/SimpleList.js";
import Pagination from "@/Components/Paginator.vue";
import Field from "@/Components/Field.vue";
import VueMultiselect from 'vue-multiselect'
import draggable from "vuedraggable";
import TableBottom from "@/Components/TableBottom.vue";
import Ttd from "@/Components/table-td.vue";

export default {
    components: {Ttd, TableBottom, VueMultiselect, Field, Pagination, Link, draggable},
    props: {
        item: Object
    },
    data() {
        return {
            ticket: null,
            tickets: [],
            lItems: new SimpleList(this, {search: ['text']}),
            reordered: false,
            filtered: false,
            itemsByTickets: []
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.question.edit', {question: item.id}))
        },
        reorder(evt) {
            this.reordered = this.isReordered();
        },
        async saveOrder() {
            let data = {};
            for (const ticket of this.tickets) {
                for (const questionIndex in ticket.questions) {
                    data[ticket.questions[questionIndex].id] = {
                        ticket: ticket.ticket,
                        order: parseInt(questionIndex) + 1
                    }
                }
            }
            let result = await axios.post(route('admin.question.order'), {
                block_id: this.item.id,
                data: data
            });
            if (result.data.result == 'ok') {
                this.item.questions = result.data.items;
                this.refreshTickets();
                this.reordered = this.isReordered();
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        },
        restoreOrder() {
            this.refreshTickets();
            this.reordered = this.isReordered();
        },
        isReordered(){
            for (const ticket of this.tickets) {
                for (const index in ticket.questions) {
                    // console.log(ticket.questions[index].order, parseInt(index) + 1);
                    if( ticket.questions[index].ticket != ticket.ticket ||
                        ticket.questions[index].order != parseInt(index) + 1){
                        return true;
                    }
                }
            }
            return false;
        },
        refreshTickets() {
            this.tickets.length = 0;
            for (const question of this.item.questions) {
                let ticketIndex = this.tickets.findIndex(itm => itm.ticket == question.ticket);
                if (ticketIndex !== -1) {
                    this.tickets[ticketIndex].count++;
                    this.tickets[ticketIndex].questions.push(question);
                } else {
                    this.tickets.push({
                        ticket: question.ticket,
                        count: 1,
                        questions: [question]
                    })
                }
            }
        },
        async remove(item){
            let result = await axios.delete(route('admin.question.destroy', {question: item.id}));
            if (result.data.result == 'ok') {
                this.item.questions = result.data.items;
                this.refreshTickets();
                this.reordered = this.isReordered();
            } else {
                alert('Что-то пошло не так. Обновите страницу, пожалуйста, или обратитесь к администратору');
            }
        }
    },
    watch: {
        'lItems.filter'(value) {
            this.filtered = value != '';
        }
    },
    computed: {
        items() {
            if (this.ticket) {
                let $v = this;
                return this.item.questions.filter(itm => itm.ticket == $v.ticket.ticket);
            } else {
                return this.item.questions;
            }
        }
    },
    created() {
        this.lItems.init();
        this.refreshTickets();
    }
}
</script>

<style lang="scss" scoped>
@import "/resources/css/admin-vars";

table.table {
    margin-bottom: 15px;
}

@include mobile{
    table.table.reordering tbody{
        gap: 0;
        tr{border-radius: 0; margin-top: -1px;
            &:first-child{border-radius: 5px 5px 0px 0px; }
            &:last-child{border-radius: 0px 0px 5px 5px; }
        }
    }
}

h4 {
    color: $foreColor;
    font-weight: normal
}

@include desktop {
    td, th {
        &:first-child {
            width: 80px;
            text-align: center;
        }

        &.order {
            width: 80px;
        }

        &.remove {
            width: 40px;
            text-align: center;

        }
    }
}
@include mobile {
    td {

        &.remove {
            width: 40px;
            text-align: center;

        }
    }
}
.handler {
    @include desktop{
        color: $border-color;
        border-radius: 5px;
        width: 1.5px;
        font-size: 1.4em;
        margin-right: 0.4em;
        margin-left: -0.5em;
    }
    @include mobile{
        display: flex;
        align-items: baseline;
        justify-content: flex-start;
        gap: 15px;
        border-bottom: none;
        margin: 0;
    }
    cursor: grab;
    &:active {
        cursor: grabbing;
    }
}

.simple-list-filter-wrp {
    gap: 1em;
    @include desktop{
        div.query-wrp{
            flex-grow: 1;
        }
        .ticket-filter {
            width: 200px;
            flex-shrink: 0
        }
    }
    @include mobile{
        flex-direction: column;
        div.query-wrp{
            width: 100%;
        }
        .ticket-filter {
            width: 100%;
            flex-shrink: 0
        }
    }

}



.table-bottom-info {
    .btn {
        width: auto;
    }
}
 @include desktop{
     .to-center {
         text-align: center
     }
 }


.old-order {
    color: $red;
    font-weight: 300
}

</style>
