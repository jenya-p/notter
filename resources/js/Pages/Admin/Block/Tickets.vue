<template>
    <div class="ticket-filter" v-if="count != 0">
        <label>Билет</label>
        <input type="number" v-model="current" min="1" :max="count" class="input" placeholder="№">
        <template v-for="i in pages">
            <span v-if="i=='sep'" class="separator">...</span>
            <span v-else-if="i == current" class="active">{{ i }}</span>
            <a v-else @click="go(i)">{{ i }}</a>
        </template>
    </div>
</template>

<script>
export default {
    data() {
        return {
            current: this.modelValue,
        }
    },
    props: {
        count: {
            type: Number,
            required: false,
        },
        modelValue: ''
    },
    methods: {
        go(page) {
            this.current = page;
            this.$emit('update:modelValue', page);
        }
    },
    computed: {
        pages() {
            var pages = [];
            for (var i = 1; i <= this.count; i++) {
                if (i === 1 || i === this.count || (i > Math.min(this.count - 7, this.current - 3) && i < Math.max(8, this.current + 3))) {
                    pages.push(i);
                } else if (i === 2 || (i === this.count - 1 && this.current !== 'all')) {
                    pages.push('sep');
                }
            }
            return pages;
        }
    },
    watch: {
        modelValue() {
            this.current = this.modelValue;
        }
    }
}
</script>

<style lang="scss" scoped>
.ticket-filter {
    @import "resources/css/admin-vars";

    display: flex;
    width: auto;
    margin: 0.5em 5px 1.7em auto;
    gap: 15px;
    align-items: center;


    label{

    }
    input {
        width: 70px;
    }

    .separator {
        font-size: 14px;
        padding: 0;
        width: 30px;
        text-align: center;
        display: inline-block;
        color: gray;
        line-height: 25px;
        margin-left: 7px;
    }


    .active,a {
        font-size: 14px;
        padding: 0;
        width: 30px;
        margin-left: 7px;
        text-align: center;
        display: inline-block;
        box-sizing: border-box;
        line-height: 30px;
        border-radius: 7px;
        height: 30px;
    }

    .active {
        color: white;
        background: $red;
        border: 1px solid transparent;
    }

    a {
        text-decoration: none;
        color: inherit;
        cursor: pointer;
        border: 1px solid $border-color;
    }
}
</style>
