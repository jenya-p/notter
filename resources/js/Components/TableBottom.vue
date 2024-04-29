<template>
    <div class="table-bottom-margin fixed" ref="margin"/>
    <div class="table-bottom-info fixed" :class="cls()" ref="panel">
        <slot/>
    </div>
</template>

<script>
let p = false;
export default {
    name: 'table-bottom',
    props: [
        'align',
    ],
    methods: {
        refresh() {
            if (this.$refs.margin.offsetTop > window.scrollY + window.innerHeight - this.$refs.panel.clientHeight) {
                if (p) {
                    this.$refs.margin.classList.add('fixed');
                    this.$refs.panel.classList.add('fixed');
                    p = false;
                }
            } else {
                if (!p) {
                    p = true;
                    this.$refs.margin.classList.remove('fixed');
                    this.$refs.panel.classList.remove('fixed');
                }
            }
        },
        cls(){
            if(this.align === 'left'){
                return 'table-bottom-info--left';
            } else if(this.align === 'center'){
                return 'table-bottom-info--center';
            } else {
                return 'table-bottom-info--right';
            }
        }
    },
    mounted() {
        window.addEventListener('scroll', this.refresh);
        window.addEventListener('resize', this.refresh);
        this.refresh();
    },
    unmounted() {
        window.removeEventListener('scroll', this.refresh);
        window.removeEventListener('resize', this.refresh);
    }
}

</script>

<style lang="scss" scoped>

@import "resources/css/admin-vars";

.table-bottom-margin.fixed {
    margin-bottom: 60px;
}


.table-bottom-info {
    display: flex;
    gap: 1em;
    flex-direction: row;
    padding: 20px 0;
    height: 40px;
    align-items: center;
    transition: border 200ms ease, box-shadow 200ms ease;

    /* border-top: 1px solid transparent; */
    border-left: 1px solid transparent;
    border-right: 1px solid transparent;

    &.fixed {
        position: fixed;
        bottom: 0;
        left: 330px;
        right: 20px;

        z-index: 100;
        padding: 20px 20px;

        background: white;
        /* border-top: 1px solid $border-color; */
        border-left: 1px solid $border-color;
        border-right: 1px solid $border-color;
        box-shadow: 0px -18px 16px -15px rgba(0, 0, 0, 0.0705882353);

    }
}
</style>
<style lang="scss">
.table-bottom-info--left{
    justify-content: left;
}
.table-bottom-info--right{
    justify-content: right;
}
.table-bottom-info--center{
    justify-content: center;
}
</style>
