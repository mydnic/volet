import { createApp } from 'vue'

import VoletFeedbackMessages from './components/features/VoletFeedbackMessages.vue'
import Volet from './components/Volet.vue'

async function bootstrap() {
    const app = createApp(Volet)

    app.component('VoletFeedbackMessages', VoletFeedbackMessages)

    window.Volet = app
    app.mount('#volet')
}

bootstrap()
