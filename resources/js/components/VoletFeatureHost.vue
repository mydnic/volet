<script setup>
import { ref, watch, onMounted, nextTick } from 'vue'

const props = defineProps({
    activeFeature: Object
})

const featureContainer = ref(null)

async function loadFeature(feature) {
    if (!feature) return

    await nextTick()

    const container = featureContainer.value

    if (!container) return

    container.innerHTML = ''

    const el = document.createElement(feature.component)

    for (const [key, value] of Object.entries(feature.config || {})) {
        if (typeof value === 'string' || typeof value === 'number' || typeof value === 'boolean') {
            el.setAttribute(key, value)
        } else {
            // Ensuite les objets/arrays/etc. en tant que propriétés JS
            el[key] = value
        }
    }

    el.addEventListener('close', () => {
        container.innerHTML = ''
    })

    container.appendChild(el)
}

onMounted(() => {
    // Si la feature est déjà définie au premier render
    if (props.activeFeature) {
        loadFeature(props.activeFeature)
    }
})

// Regarder les changements de feature
watch(() => props.activeFeature, (feature) => {
    loadFeature(feature)
})
</script>

<template>
    <div ref="featureContainer" />
</template>
