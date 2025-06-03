<template>
    <div class="volet-feedback">
        <!-- Category Selection -->
        <div v-if="!selectedCategory" class="volet-feedback-categories">
            <button
                v-for="category in categories"
                :key="category.slug"
                class="volet-feedback-category"
                @click="selectCategory(category)"
            >
                <img
                    v-if="category.icon"
                    :src="category.icon"
                    :alt="category.name"
                    class="volet-feedback-category-icon"
                />
                <span class="volet-feedback-category-name">{{ category.name }}</span>
            </button>
        </div>

        <!-- Feedback Form -->
        <form v-else class="volet-feedback-form" @submit.prevent="submitFeedback">
            <div class="volet-feedback-selected-category">
                <button type="button" @click="selectedCategory = null" class="volet-feedback-change-category">
                    <img
                        v-if="selectedCategory.icon"
                        :src="selectedCategory.icon"
                        :alt="selectedCategory.name"
                        class="volet-feedback-category-icon"
                    />
                    <span>{{ selectedCategory.name }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
            </div>

            <div class="volet-feedback-input">
                <label for="message" class="sr-only">Your feedback</label>
                <textarea
                    id="message"
                    v-model="message"
                    rows="4"
                    :placeholder="props.labels?.placeholder || `What's on your mind?`"
                    required
                    class="volet-feedback-textarea"
                ></textarea>
            </div>

            <div class="volet-feedback-actions">
                <p v-if="error" class="volet-feedback-error">
                    {{ error }}
                </p>
                <button
                    type="submit"
                    class="volet-button"
                    :disabled="isSubmitting"
                >
                    <span v-if="isSubmitting">{{ props.labels?.['button-loading'] || 'Sending...' }}</span>
                    <span v-else>{{ props.labels?.button || 'Send feedback' }}</span>
                </button>
            </div>
        </form>

        <!-- Success Message -->
        <div v-if="showSuccess" class="volet-feedback-success">
            <IconResolver :icon="content['success-icon']" class="volet-feedback-success-icon" />
            <h4>{{ props.labels?.['success-title'] || 'Thank you for your feedback!' }}</h4>
            <p>{{ props.labels?.['success-subtitle'] || 'We appreciate your input and will review it shortly.' }}</p>
            <button @click="reset" class="volet-feedback-reset">{{ props.labels?.['send-another'] || 'Send another feedback' }}</button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import IconResolver from "../IconResolver.vue";

// Props from feature config
const props = defineProps({
    categories: {
        type: Array,
        required: true
    },
    routes: {
        type: Object,
        required: true
    },
    labels: {
        type: Object,
        required: true
    },
    content: {
        type: Object,
        required: true
    },
    csrfToken: {
        type: String,
        required: true
    }
})

defineEmits(['close'])

const selectedCategory = ref(null)
const message = ref('')
const isSubmitting = ref(false)
const showSuccess = ref(false)
const error = ref('')

const selectCategory = (category) => {
    selectedCategory.value = category
    error.value = '' // Clear any previous errors
}

const submitFeedback = async () => {
    if (isSubmitting.value) return

    error.value = '' // Clear any previous errors
    isSubmitting.value = true

    try {
        const response = await fetch(`${props.routes.store}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': props.csrfToken
            },
            body: JSON.stringify({
                category: selectedCategory.value.slug,
                message: message.value,
                user_info: {
                    viewportWidth: window.innerWidth,
                    viewportHeight: window.innerHeight
                }
            })
        })

        if (!response.ok) {
            const data = await response.json()
            throw new Error(data.message || props.labels?.error || 'Failed to submit feedback')
        }

        showSuccess.value = true
    } catch (err) {
        error.value = err.message || props.labels?.error || 'Failed to submit feedback. Please try again.'
        console.error('Failed to submit feedback:', err)
    } finally {
        isSubmitting.value = false
    }
}

const reset = () => {
    selectedCategory.value = null
    message.value = ''
    showSuccess.value = false
    error.value = ''
}
</script>
