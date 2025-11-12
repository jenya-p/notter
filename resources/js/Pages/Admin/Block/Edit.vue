

<template>
    <AdminLayout :title="item.id ? item.title : 'Новый блок'"
                 :breadcrumb="[{link: route('admin.block.index'), label: 'Блоки тестов'}, item.id ? item.title: 'Новый блок']">

        <tabs class="block">
            <tab name="info" label="Основная информация" :has-error="tabErrors.info" :selected="true">

                <form method="post" @submit.prevent="submit" v-field-container>

                    <field :errors="form.errors" for="title" label="Заголовок">
                        <input class="input" v-model="form.title"/>
                    </field>
                    <field :errors="form.errors" for="active" label="" class="field-checkboxes">
                        <checkbox v-model="form.active">Активно</checkbox>
                    </field>
                    <field :errors="form.errors" for="is_plain_text" label="Тип" class="field-radios">
                        <radio v-model="form.is_plain_text" :value="true" :disabled="!!item.id">Список вопросов</radio>
                        <radio v-model="form.is_plain_text" :value="false" :disabled="!!item.id">Билеты</radio>
                    </field>
                    <field :errors="form.errors" for="price" label="Цена">
                        <input type="number" class="input" v-model="form.price" min="0" max="100000"/>
                    </field>

                    <field :errors="form.errors" for="passing_score" label="Проходной балл билетов">
                        <input type="number" class="input" v-model="form.passing_score" min="0"/>
                        <p class="input-description">Пусто - ошибки не допускаются</p>
                    </field>

                    <field :errors="form.errors" for="description" label="Описание" class="field-vertical">
                        <ckeditor v-model="form.description" :editor="editor" :config="{width: '100%'}"/>
                    </field>

                    <div class="block-footer">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        <a @click="submit(true)" class="btn btn-default" style="width: auto;margin-left: 1em;" v-if="item.id">Опубликовать изменения</a>
                        <!--                <History type="user"/>-->



                    </div>

                </form>
            </tab>
            <tab name="questions" label="Вопросы" v-if="item.id" :has-error="tabErrors.questions">
                <Questions :item="item" v-if="item.is_plain_text == false "/>
                <QuestionsPlain :item="form" v-else @submit="submit"/>
            </tab>
        </tabs>



    </AdminLayout>
</template>
<script>
import {Link, useForm} from '@inertiajs/vue3';
import Field from "@/Components/Field.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextareaAutosize from "@/Components/TextareaAutosize.vue";
import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import Checkbox from "@/Components/Checkbox.vue";
import Radio from "@/Components/Radio.vue";
import Questions from "./Questions.vue"
import QuestionsPlain from "@/Pages/Admin/Block/QuestionsPlain.vue";
import _isObject from "lodash/isObject";
import _extend from "lodash/extend";
import tabs from "@/Components/tabs.vue";
import tab from "@/Components/tab.vue";

export default {
    components: {tabs, tab, QuestionsPlain, Link, Radio, Checkbox, TextareaAutosize, AdminLayout, Field, Questions, ckeditor: CKEditor.component},
    props: {
        item: {
            type: Object,
            default: null
        }
    },

    data() {
        if(this.item.description === null) {
            this.item.description = '';
        }
        return {
            form: useForm(_extend({
                title: '',
                active: false,
                description: '',
                price: 0,
                is_plain_text: false,
                passing_score: null
            }, this.item)),
            editor: ClassicEditor,
            tabErrors: {
                info: false, questions: false
            }
        }
    },
    watch: {
      'form.errors'(){
          this.tabErrors.info = false;
          this.tabErrors.questions = false;
          if(_isObject(this.form.errors)){
              for (const key in this.form.errors) {
                  if(key.startsWith('questions')){
                      this.tabErrors.questions = true;
                  } else {
                      this.tabErrors.info = true;
                  }
                  if(this.tabErrors.questions && this.tabErrors.info){
                      return;
                  }
              }
          }
      }
    },

    methods: {
        submit(publish = false) {
            this.form.errors = [];
            if(this.form.questions){
                for (const index in this.form.questions) {
                    this.form.questions[index].order = parseInt(index) + 1;
                }
            }
            if (this.item.id) {
                this.form.put(route('admin.block.update', {block: this.item.id, publish: publish}));
            } else {
                this.form.post(route('admin.block.store'));
            }
        }
    }

}
</script>
<style lang="scss" scoped>

</style>

