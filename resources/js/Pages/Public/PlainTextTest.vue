<template>
    <GuestLayout wrapper-class="profile-page" :title="test.title">
        <h1 class="d-only">{{ test.title }}</h1>

        <div class="block block-2">
            <h1 class="m-only">{{ test.title }}</h1>
            <div class="block-bordered content-wrapper">
                <div class="content">
                    <ul class="questionare">
                        <li v-for="question of test.block.tickets[0].questions">
                            {{ question.text }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import {router} from "@inertiajs/vue3";

export default {
    components: {GuestLayout},
    props: [
        'test'
    ],
    data() {
        return {}
    },
    methods: {},
    watch: {},
    computed: {}
}


</script>

<style lang="scss" scoped>
@import "resources/css/admin-vars";

h1 {
    text-align: center;
    margin-bottom: 17px;
    @media screen and (max-width: 500px) {
        &.d-only {
            display: none;
        }
    }
    @media screen and (min-width: 500px) {
        &.m-only {
            display: none;
        }
        margin-top: -20px;
    }
}

.content {
    line-height: 1.2em;

    .questionare {
        list-style: auto;
        margin-left: 30px;

        li {
            margin-bottom: 1.2em;

            &:before {
                content: none;
            }
        }
    }
}


.block {
    display: flex;
    flex-direction: column;
    gap: 0;
    @media screen and (min-width: 700px) {
        padding: 30px;
    }
    @media screen and (max-width: 700px) and (min-width: 500px) {
        padding: 20px;
    }

    .content-wrapper {
        height: 100%;

        @media screen and (min-width: 700px) {
            padding: 30px 20px;
        }
        @media screen and (max-width: 700px) and (min-width: 500px) {
            padding: 30px 10px;
        }
        @media screen and (max-width: 500px) {
            padding: 20px 20px 20px 0 ;
        }

        .content {
            flex-grow: 1;
            @media screen and (min-width: 500px) {
                padding-right: 20px;
            }

        }
    }
}

@media screen and (min-height: 700px) and (min-width: 500px) {
    .content-wrapper {
        max-height: calc(100vh - 430px);
        overflow: hidden;
        display: flex;
        flex-direction: column;

        .content {
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


</style>
