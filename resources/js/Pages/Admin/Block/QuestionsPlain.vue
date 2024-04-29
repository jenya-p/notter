<template>


    <table class="table">

        <draggable tag="tbody"
                   handle=".handler"
                   v-model="item.questions"
                   ghost-class="ghost"
                   animation="200"
                   item-key="id"
                   @end="reorder"
                   group="questions">
            <template #item="{element, index}">
                <tr >
                    <td class="handler"><i class="fa fa-bars"></i></td>
                    <td class="order" v-if="element.order != index + 1">
                        <b :title="'Старое значение: ' + element.order">{{ index + 1 }}*</b>
                    </td>
                    <td class="order" v-else>
                        {{ index + 1 }}
                    </td>
                    <td class="question">
                        <field :errors="item.errors" :for="'questions.' + (element.order - 1) + '.*'" style="margin: 0">
                            <textarea-autosize class="input" v-model="element.text" />
                        </field>
                    </td>
                    <td class="remove">
                        <a class="fa fa-times btn-remove" @click.stop="remove(index)"></a>
                    </td>
                </tr>
            </template>

        </draggable>
        <tr >
            <td class="handler"></td>
            <td class="order">

            </td>
            <td class="question">
                <textarea-autosize class="input" v-model="newText" @keydown.ctrl.enter="add" placeholder="Добавить вопрос"/>
            </td>
            <td class="remove">
                <a class="btn btn-primary btn-sm btn-add fa fa-plus" @click.stop="add()" title="Ctrl+Enter"></a>
            </td>
        </tr>
    </table>

    <table-bottom align="center">
        <a class="btn btn-primary" @click="$emit('submit')" style="width: 200px">Сохранить</a>
    </table-bottom>
</template>

<script>
import {Link} from "@inertiajs/vue3";
import {SimpleList} from "@/Components/SimpleList.js";
import Pagination from "@/Components/Paginator.vue";
import Field from "@/Components/Field.vue";
import Tickets from "./Tickets.vue";
import VueMultiselect from 'vue-multiselect'
import draggable from "vuedraggable";
import TableBottom from "@/Components/TableBottom.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import InputError from "@/Components/InputError.vue";

export default {
    components: {InputError, TextareaAutosize, TableBottom, VueMultiselect, Tickets, Field, Pagination, Link, draggable},
    props: {
        item: Object
    },
    data() {
        return {
            questions: null,
            lItems: new SimpleList(this, {search: ['text']}),
            reordered: false,
            filtered: false,
            itemsByTickets: [],
            newText: ''
        };
    },
    methods: {
        itemClick: function (item) {
            this.$inertia.visit(route('admin.question.edit', {question: item.id}))
        },
        reorder(evt) {

        },
        add(){
            if(this.newText != ''){
                this.item.questions.push({
                    text: this.newText
                });
                this.newText = '';
            }
        },
        async remove(index){
            this.item.questions.splice(index,1)
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
       // this.refreshTickets();
    },
    mounted(){

    }

}
</script>

<style lang="scss" scoped>
@import "/resources/css/admin-vars";

table.table {
    margin-bottom: 15px
}

h4 {
    color: $foreColor;
    font-weight: normal
}

table.table tr td{vertical-align: top;
    border-bottom: 0;
    line-height: 43px;
    &.question{
        line-height: 0;
    }
}
table.table tr td, table.table tr th{
    &:first-child {
        width: 50px;
        text-align: center;
        padding-right: 0;
    }
    &.order {
        padding-left: 0;
        width: 50px;
        text-align: center;
    }
    &.remove{
        width: 40px; text-align: center;

    }
}

.handler {
    color: $border-color;
    border-radius: 5px;
    width: 1.5px;
    font-size: 1.4em;
    margin-right: 0.4em;
    margin-left: -0.5em;
    cursor: grab;

    &:active {
        cursor: grabbing;
    }
}

.simple-list-filter-wrp {
    gap: 1em;
}

.ticket-filter {
    width: 200px;
    flex-shrink: 0
}

.table-bottom-info {
    .btn {
        width: auto;
    }
}

.to-center {
    text-align: center
}

.old-order {
    color: $red;
    font-weight: 300
}
.btn-add{
    width: 40px;
    padding: 0;
    height: 40px;
    line-height: 40px;
}
</style>
