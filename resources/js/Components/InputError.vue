<script setup>
defineProps({
    message: {
        type: String,
    },
});

</script>

<template>
    <p class="input-error" v-show="message">
        {{ message }}
    </p>
</template>
