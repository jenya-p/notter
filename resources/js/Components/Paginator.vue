<script>



export default {
    props: {
        label: {
            type: String,
            default: null,
        },
        modelValue: {
            default: 1
        },
        count: {
            type: Number
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
    },
    methods: {
      resize(){
        console.log(this.$refs.container.clientWidth);

      }
    },
    mounted() {
        window.addEventListener('resize', this.resize);
    },
    unmounted() {
        window.removeEventListener('resize', this.resize);
    }

}

</script>

<template>
    <div ref="container" class="paginator">
        <label v-if="label">{{label}}</label>
        <template  v-for="index in count">
            <a v-if="index != modelValue" @click="proxy = index">{{index}}</a>
            <span v-else>{{index}}</span>
        </template>
    </div>
</template>

<style scoped lang="scss">

.paginator{
    display: flex;
    gap: 5px;
    overflow: hidden;

    label{font-size: 16px;font-weight: 600;width: 65px;flex-shrink: 0;flex-grow: 0;}
    a,
    span{
        box-sizing: border-box;
        line-height: 25px;
        width: 25px;
        height: 25px;
        text-align: center;
        flex-shrink: 0;
        flex-grow: 0;
        padding: 0;
        margin: 0;
        border-radius: 8px;
        font-family: 'Jura', sans-serif, serif;
    }
    a{cursor: pointer; transition: filter 200ms ease}
    a:hover{text-decoration: none; filter: brightness(95%);}
    .spacer{}


    &-gray{
        label{line-height: 24px}
        a,
        span{
            font-size: 10px;
            line-height: 22px;
            width: 24px;
            height: 24px;

            font-weight: 400;
        }
        a{

            color: #999;
            background: #F1F1F1;
            border: 2px solid #F1F1F1;
        }
        span{
            color: white;
            background: #1AB69D;
            border: 2px solid #1AB69D;
        }
    }
    &-white{
        label{line-height: 27px}
        a,
        span{
            font-size: 12px;
            line-height: 24px;
            width: 27px;
            height: 27px;
        }
        a{color: #333; border: 1px solid #DADADA; padding: 1px}
        span{border: 2px solid #1AB69D;}
    }

}





</style>
