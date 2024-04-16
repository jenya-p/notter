<script>

export default {
    props: {
        value: {
            default: null
        },
        modelValue: {
            type: [Array, Boolean, String],
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        proxy: {
            get() {
                return this.modelValue;
            },
            set(val) {
                this.$emit("update:modelValue", val);
            },
        }
    }
}

</script>

<template>
    <label class="checkbox">
        <input type="checkbox" :value="value" v-model="proxy" :disabled="disabled">
        <i class="mark" :class="{half:proxy === 'half'}"></i>
        <span v-if="!!$slots.default"><slot/></span>
    </label>
</template>

<style scoped lang="scss">

.checkbox {
    position: relative;
    user-select: none;
    font-weight: normal;
    padding-left: 2.2em;
    margin-bottom: 0;
    cursor: pointer;
    display: inline-block;

    input[type=checkbox] {
        position: absolute;
        cursor: pointer;
        opacity: 0;
        height: 0;
        width: 0;
    }


    i.mark {
        position: absolute;
        left: 0;
        padding: 0;
        margin: -0.1em 0 0 0;
        width: 1.2em;
        height: 1.2em;
        border: 2px solid #D4D2D8;
        border-radius: 0.25em;
        line-height: 0.6em;
        font-size: 1.2em;
        text-align: center;
        box-sizing: border-box;
    }

    &:active i.far {
        border-color: darken(#a9afbb, 10%);

    }


    input[type=checkbox]:checked + i.mark {
        border: 2px solid #0575E6;
        background: #0575E6 url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTQiIHZpZXdCb3g9IjAgMCAxOCAxNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xNi45OTM5IDAuNDM1MzM0QzE3LjMwNTYgMC43MDgwOTUgMTcuMzM3MiAxLjE4MTkyIDE3LjA2NDQgMS40OTM2NEw2LjU2NDQzIDEzLjQ5MzZDNi40Mjc3NCAxMy42NDk5IDYuMjMyNDIgMTMuNzQyNCA2LjAyNDk2IDEzLjc0OTRDNS44MTc1IDEzLjc1NjMgNS42MTY0NSAxMy42NzY5IDUuNDY5NjcgMTMuNTMwMUwwLjk2OTY3IDkuMDMwMUMwLjY3Njc3NyA4LjczNzIgMC42NzY3NzcgOC4yNjIzMyAwLjk2OTY3IDcuOTY5NDRDMS4yNjI1NiA3LjY3NjU0IDEuNzM3NDQgNy42NzY1NCAyLjAzMDMzIDcuOTY5NDRMNS45NjM0NyAxMS45MDI2TDE1LjkzNTYgMC41MDU4ODhDMTYuMjA4MyAwLjE5NDE2IDE2LjY4MjIgMC4xNjI1NzIgMTYuOTkzOSAwLjQzNTMzNFoiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=") center center no-repeat;
        background-size: 0.8em;
    }

    i.far.half {
        color: white;

        &:before {
            content: "-";
        }
    }

    input[type=checkbox]:focus + i.far {
        z-index: 50;
        outline: 1px dotted #838383;
        outline-offset: 0.8px;
    }

    input[type=checkbox]:checked:disabled + i.far,
    input[type=checkbox]:disabled + i.far {
        background: #f4f4f4;
        border-color: #a9afbb;
        color: #636466;
    }

}

</style>
