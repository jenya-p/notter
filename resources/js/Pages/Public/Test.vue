<template>
    <GuestLayout wrapper-class="profile-page">
        <h1>Нотариальный тест (Блок 1)</h1>
        <div class="block block-1">
            <Paginator v-model="b" :count="50" class="paginator-gray" label="Билет: "/>
        </div>
        <div class="block block-2">
            <Paginator v-model="q" :count="20" class="paginator-white" label="Вопрос:"/>

            <div class="block-bordered">
                <div class="question">
                    Документы, оформленные частными или государственными нотариусами, одинаковую юридическую силу:
                </div>

                <div class="variants">
                    <div class="variant-wrapper" :class="{active: answer == 1}">
                        <Radio v-model="answer" value="1">имеют, но не по всем вопросам</Radio>
                    </div>
                    <div class="variant-wrapper" :class="{active: answer == 2}">
                        <Radio v-model="answer" value="2">имеют, но с последующим утверждением государственным
                            нотариусом
                        </Radio>
                    </div>
                    <div class="variant-wrapper" :class="{active: answer == 3}">
                        <Radio v-model="answer" value="3">имеют</Radio>
                    </div>
                    <div class="variant-wrapper" :class="{active: answer == 4}">
                        <Radio v-model="answer" value="4">не имеют</Radio>
                    </div>
                </div>
            </div>

            <div class="buttons">
                <a href="" class="btn btn-green btn-next">Следующий</a>
                <a href="" class="btn btn-white btn-skip">Пропустить</a>
            </div>

        </div>

    </GuestLayout>
</template>

<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Paginator from "@/Components/Paginator.vue";
import Radio from "@/Components/Radio.vue";
import {ref} from "vue";

const q = ref(1);
const b = ref(1);
const answer = ref(null);

</script>

<style lang="scss" scoped>
h1 {
    text-align: center;
    margin-bottom: 17px
}

.block-1 {
    margin-bottom: 10px
}

.block-2 {
    padding-top: 40px
}

.paginator-gray, .paginator-white {
    padding: 5px;
}

.paginator-white {
    margin-bottom: 25px
}

.block-bordered{
    padding: 17px 25px;
    min-height: 358px;
    box-sizing: border-box;
    margin-bottom: 82px;
    .question {
        font-size: 20px;
        font-weight: 600;
        line-height: 1.2em;
        margin-bottom: 10px;
    }

    .variants {
        display: flex;
        flex-direction: column;
        gap: 11px;

        .variant-wrapper {
            padding: 10px;
            border-radius: 10px;

            &.active {
                background: #F1F1F1;
            }
        }

    }
}

.buttons {
    display: flex;
    gap: 15px;
    .btn-next{
        width: unset;
        flex-grow: 1;
    }
    .btn-skip{
        width: 180px;

    }
}

</style>
