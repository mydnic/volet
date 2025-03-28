import { createApp } from 'vue'
import Volet from './components/Volet.vue'
import VoletFeedbackMessages from './components/features/VoletFeedbackMessages.vue'

// Create the Volet app
const app = createApp(Volet)

// Expose global registration method
window.Volet = app

// Register built-in components
app.component('VoletFeedbackMessages', VoletFeedbackMessages)

// Mount the app
app.mount('#volet')

