<template>
    <img
        v-if="!isSvg"
        :src="icon"
        :alt="alt"
    >
    <div
        v-else
        v-html="icon"
    />
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    icon: {
        type: String,
        required: true
    },
    alt: {
        type: String,
        default: 'Icon'
    }
})

const isSvg = computed(() => {
    return props.icon.trim().toLowerCase().startsWith('<svg')
})
</script>
