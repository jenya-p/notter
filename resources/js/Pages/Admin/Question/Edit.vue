<template>
    <AdminLayout :title="item.id ? 'Вопрос №' + item.order : 'Новый вопрос'"
                 :breadcrumb="[{link: route('admin.block.index'), label: 'Блоки тестов'},
                 {link: route('admin.block.edit', {block: item.block.id}), label: item.block.title},
                 item.id ? 'Вопрос №' + item.order: 'Новый вопрос']">

        <form method="post" class="block" @submit.prevent="submit" v-field-container>
            <div class="numbers">
                <field :errors="form.errors" for="ticket" label="Билет" class="field-horizontal">
                    <input type="number" class="input" v-model="form.ticket" min="1" max="1000">
                </field>
                <field :errors="form.errors" for="order" label="№ вопроса п/п" class="field-horizontal">
                    <input type="number" class="input" v-model="form.order" min="1" max="1000">
                </field>
            </div>

            <field :errors="form.errors" for="text" label="Текст вопроса">
                <textarea-autosize class="input" v-model="form.text"/>
            </field>

            <field label="Варианты">
                <draggable
                    v-if="options.length"
                    v-model="options"
                    handle=".handler"
                    group="group"
                    item-key="index"
                    ghost-class="ghost"
                    animation="200"
                >
                    <template #item="{element, index}">
                        <div class="option">
                            <i class="handler fa fa-bars" title="Упорядочить ответы"></i>
                            <radio v-model="right" :value="element.index" title="Верный ответ"/>
                            <field :errors="form.errors" :for="'options.' + element.index" class="field-option">
                                <textarea-autosize class="input" v-model="element.text" ref="optionTextInput"/>
                            </field>
                            <a class="fa fa-times btn-remove" title="Удалить вариант"
                               @click="removeOption(index)"></a>
                        </div>
                    </template>
                </draggable>
                <a class="btn btn-primary btn-xs" @click="addOption">Добавить</a>
                <input-error :errors="form.errors" for="options,right"/>
            </field>

            <field :errors="form.errors" for="description" label="Описание" class="field-vertical">
                <ckeditor v-model="form.description" :editor="editor" :config="{width: '100%'}"/>
            </field>

            <div class="block-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>


    </AdminLayout>
</template>
<script>
import {useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import Radio from "@/Components/Radio.vue";
import draggable from "vuedraggable";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import _extend from "lodash/extend";

let _counter = 0;
export default {
    components: {
        InputLabel,
        InputError, Radio, TextareaAutosize, AdminLayout, Field, ckeditor: CKEditor.component, draggable},
    props: {
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        let $v = this;
        let options = [];
        for (let i = 0; i < this.item.options.length; i++) {
            options.push({
                index: i,
                text: this.item.options[i]
            });
        }
        _counter = this.item.options.length;
        if(this.item.description === null){
            this.item.description = '';
        }
        return {
            form: useForm(_extend({
                ticket: null,
                order: null,
                text: '',
                options: [],
                right: null,
                description: '',
            }, this.item)),
            options: options,
            right: this.item.right,
            editor: ClassicEditor,
        }
    },


    methods: {
        submit() {
            this.form.errors = {};
            this.form.options = [];
            this.form.right = null;
            for (let i = 0; i < this.options.length; i++) {
                this.form.options.push(this.options[i].text);
                if (this.options[i].index == this.right) {
                    this.form.right = i;
                }
                this.options[i].index = i;
            }
            _counter = this.options.length;
            if (this.item.id) {
                this.form.put(route('admin.question.update', {question: this.item.id}));
            } else {
                this.form.post(route('admin.question.store'));
            }
        },

        removeOption(index) {
            if (this.right == this.options[index].index) {
                this.right = null;
            }
            this.options.splice(index, 1);
        },
        addOption() {
            this.options.push({
                index: _counter++,
                text: ''
            });
            this.$nextTick(function () {
                this.$refs.optionTextInput.$el.focus();
            })

        }

    },
    computed: {}

}
</script>
<style lang="scss" scoped>
@import "resources/css/admin-vars";

::v-deep(form) {
    .field-option {
        margin: 0;
    }

    .numbers{
        display: flex;
        flex-wrap: wrap;
        .field{
            width: auto;

        }

    }
    &:not(.vertical) {
        .field{
            margin-bottom: 0;
        }
        .numbers{
            gap: 50px;
            .field-order{ .input-label{width: auto; text-align: right;} }
        }
    }

    &.vertical .numbers {
        margin-top: 20px;
        gap: 20px;
        .field{
            .input-label{line-height: unset}
            flex-direction: column;
            align-items: stretch;
            margin: 0;
        }
    }
}

.field-ticket input,
.field-order input{
    width: 180px;
}

.option {
    border: 1px solid transparent;
    margin-bottom: 1em;
    display: flex;

    .handler {
        color: $border-color;
        border-radius: 5px;
        font-size: 1.4em;
        padding: 0.5em 0.5em 0.5em 0.5em;
        margin-right: 0.4em;
        margin-left: -0.5em;
        cursor: grab;

        &:active {
            cursor: grabbing;
        }
    }

    label {
        padding-top: 0.7em;
        width: 10px;
    }

}
</style>
<style lang="scss">
.ghost {
    border-color: #ccc;
    opacity: 0.5;
}
</style>

