<template>
    <div class="volet-panel">
        <header class="volet-panel-header">
            <template v-if="activeFeature">
                <button @click="closeFeature" class="volet-panel-header-back">
                    <svg class="volet-panel-header-back-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span>{{ labels.back }}</span>
                </button>
                <h3 class="volet-panel-header-feature-title">{{ activeFeature.label }}</h3>
            </template>
            <template v-else>
                <h2 class="volet-panel-title">{{ labels.title }}</h2>
                <button @click="$emit('close')" class="volet-panel-close">
                    <span class="sr-only">{{ labels.close }}</span>
                    <svg class="volet-panel-close-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </template>
        </header>

        <div class="volet-panel-content">
            <div class="volet-panel-content-inner">
                <div v-if="isLoading" class="volet-panel-loading">
                    <svg class="volet-loading-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="volet-loading-text">{{ labels.loading }}</span>
                </div>

                <div
                    v-else-if="!activeFeature"
                    class="volet-panel-features"
                >
                    <button
                        v-for="feature in features"
                        :key="feature.id"
                        class="volet-feature-button"
                        @click="openFeature(feature)"
                    >
                        <img
                            v-if="feature.icon"
                            :src="feature.icon"
                            :alt="feature.label"
                            class="volet-feature-icon"
                        />
                        <span class="volet-feature-label">{{ feature.label }}</span>
                    </button>
                </div>

                <!-- Feature Component Container -->
                <VoletFeedbackMessages
                    v-if="activeFeature?.component === 'VoletFeedbackMessages'"
                    v-bind="activeFeature.config"
                />
                <VoletFeatureHost
                    v-else-if="activeFeature"
                    :active-feature="activeFeature"
                    @close="closeFeature"
                />
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import VoletFeedbackMessages from "./features/VoletFeedbackMessages.vue";
import VoletFeatureHost from "./VoletFeatureHost.vue";

const props = defineProps({
    labels: {
        type: Object,
        required: true,
        default: () => ({
            title: 'Volet',
            close: 'Close panel',
            loading: 'Loading...',
            back: 'Back',
        }),
    }
})

defineEmits(['close'])

const isLoading = ref(true)
const features = ref([])
const activeFeature = ref(null)

const fetchFeatures = async () => {
    try {
        const response = await fetch('/volet/settings')
            .then(response => response.json())
        features.value = response.features
    } catch (error) {
        console.error('Failed to load Volet features:', error)
    } finally {
        isLoading.value = false
    }
}

const openFeature = (feature) => {
    activeFeature.value = feature
}

const closeFeature = () => {
    activeFeature.value = null
}

onMounted(() => {
    fetchFeatures()
})
</script>
