<template>
    <GuestLayout wrapper-class="content-page">
        <img src="/images/index-decor.svg" alt="" class="decorator">

        <div class="block">
            <h1 class="main-page-title" v-if="content.title">{{content.title}}</h1>
            <div class="content-wrapper">
                <div class="content" v-html="content.content"></div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps(['content'])

</script>

<style lang="scss">

.content-page {

    @media screen and (min-width: 500px) {
        position: relative;
        padding-top: 25px;
        .decorator{
            position: absolute;
            left: 50%;
            margin-left: -800px;
            z-index: -1;
            @media screen and (max-height: 1000px) and (min-height: 700px){
                top:50%; margin-top: -145px;
            }
            @media screen and ((min-height: 1000px) or (max-height: 700px)){
                top: 255px; margin-top: 0;
            }
        }
    }
    @media screen and (max-width: 500px) {
        .decorator {
            display: none;
        }
    }

    .block{
        width: 100%;
        max-width: 780px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 0;

        h1.main-page-title {
            font-size: 24px;
            font-weight: 500;
            text-align: center;
            flex-grow: 0;
        }

        .content-wrapper{
            background: #F9F9F9;
            height: 100%;


            border-radius: 10px;
            @media screen and (min-width: 500px) {
                margin: -23px -13px 0 -13px;
                padding: 20px;
            }
            @media screen and (max-width: 500px) {
                margin: -10px -10px 0 -10px;
                padding: 20px 10px;
            }

            .content {
                flex-grow: 1;
                @media screen and (min-width: 500px) {
                    padding-right: 20px;
                }

            }
        }
    }

    @media screen and (min-height: 700px) and (min-width: 500px){
        .content-wrapper {
            max-height: calc(100vh - 400px);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            .content{
                overflow-x: hidden;
                overflow-y: scroll;
                height: 100%;
                &::-webkit-scrollbar {
                    width: 5px;
                }
                &::-webkit-scrollbar-track {
                    background: transparent;
                }
                &::-webkit-scrollbar-thumb {
                    background-color: #D9D9D9;
                    border-radius: 3px;
                }
            }
        }
    }


}
</style>
