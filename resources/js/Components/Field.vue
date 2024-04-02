<script>
import {isString, isArray, isObject, isEmpty} from "lodash"
export default {
    props: ['errors', 'label', 'for', 'description'],

    computed: {
        hasErrors() {
            return this.filteredErrors.length != 0;
        },
        filteredErrors() {
            let e = [];
            let lFor = this.for;
            if(isString(lFor)) {
                lFor = lFor.split(',');
            }
            let errors = this.errors;
            if(this.errors == undefined && this.$parent && this.$parent.form  && this.$parent.form.errors){
                errors = this.$parent.form.errors;
            }
            if(isArray(lFor) && isObject(errors)){
                for (const filter of lFor) {
                    let reg ='^'+filter.replaceAll('.?', '(.\\w+)?')
                        .replaceAll('*', '\\w+')
                        .replaceAll('.', '\\.') + '$';
                    reg = new RegExp(reg);
                    for (const key in errors) {
                        if(reg.test(key) && !isEmpty(errors[key])){
                            e.push(errors[key]);
                        }
                    }
                }
            } else {
                e = errors;
                if(isString(e)){
                    e = [e];
                } else {
                    e = [];
                }
            }
            return e;
        },
        fieldClass(){
          return 'field field-' + this.for;
        }
    },


}


</script>

<template>
    <div class="field" :class="fieldClass">
        <label class="input-label" v-if="label!==undefined">{{ label }}</label>
        <div>
            <div :class="{'has-danger': hasErrors}">
                <slot></slot>
            </div>
            <p v-for="err of filteredErrors" class="input-error">{{ err }}</p>
            <small v-if="!hasErrors && description" class="input-description">{{description}}</small>
        </div>
    </div>
</template>

<style scoped>
    .error{
        margin: 0;line-height: 1.4em;
    }
    .has-danger+.error{
        margin-top: 0.2em;
    }
</style>
