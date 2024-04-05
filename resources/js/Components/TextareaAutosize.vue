<template>
    <textarea
        class="autosize"
        @input="handleInput"
        :value="modelValue"
    ></textarea>
</template>

<script>
export default {
    name: "TextareaAutosize",
    emits: ['update:modelValue'],
    props: {
        modelValue: {
            required: false,
            type: String
        }
    },
    setup(props, {emit}) {
        function handleInput (event) {
            const textarea = event.target
            emitInput(textarea)
            resize(textarea)
        }
        const emitInput = textarea => emit('update:modelValue', textarea.value)

        const resize = textarea => {
            textarea.style.height = 'auto'
            textarea.style.height = `${textarea.scrollHeight + 2}px`
        }
        return {
            handleInput,
        }
    },
    mounted() {
        this.$el.style.height = `${this.$el.scrollHeight + 2}px`
    }
}
</script>

