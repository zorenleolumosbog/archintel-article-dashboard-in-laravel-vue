import { createApp } from 'vue'
import { createPinia } from 'pinia'
import JoditVue from 'jodit-vue'

import App from './App.vue'
import router from './router'

import '@mdi/font/css/materialdesignicons.css'
import 'jodit/build/jodit.min.css'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(JoditVue)

app.mount('#app')
