<template>
    <div class="volet-container">
        <!-- Chat Bubble Button -->
        <button
            @click="togglePanel"
            class="volet-bubble"
            :class="{ 'volet-bubble-has-tooltip': labels.bubble?.tooltip && !isPanelOpen }"
            :data-tooltip="labels.bubble?.tooltip"
        >
            <IconResolver :icon="icon" alt="Chat" class="volet-bubble-icon" />
        </button>

        <!-- Chat Panel -->
        <Transition
            enter-active-class="volet-panel-enter-active"
            leave-active-class="volet-panel-leave-active"
            enter-from-class="volet-panel-enter-from"
            leave-to-class="volet-panel-leave-to"
        >
            <VoletPanel
                v-if="isPanelOpen"
                :labels="labels.panel"
                @close="togglePanel"
            />
        </Transition>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import VoletPanel from './VoletPanel.vue'
import IconResolver from './IconResolver.vue'

// State
const isPanelOpen = ref(false)
const icon = ref('')
const labels = ref({})

// Methods
const togglePanel = () => {
    isPanelOpen.value = !isPanelOpen.value
}

const initializeIcon = () => {
    const voletElement = document.getElementById('volet')
    if (!voletElement) return

    const iconData = voletElement.dataset.icon
    if (iconData) {
        icon.value = iconData
    }
}

const initializeLabels = () => {
    const voletElement = document.getElementById('volet')
    if (!voletElement) return

    const labelsData = voletElement.dataset.labels
    if (labelsData) {
        labels.value = JSON.parse(labelsData)
    }
}

// Lifecycle
onMounted(() => {
    initializeIcon()
    initializeLabels()
})
</script>
