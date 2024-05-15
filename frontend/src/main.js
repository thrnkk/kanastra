import { createApp } from 'vue'
import './style.css'
import App from './App.vue'

import 'vue3-toastify/dist/index.css'
import "@/style.css"

import vuetify from './plugins/vuetify'
import router from './plugins/router'

import { dateMixin } from './plugins/mixins/date'
import { toastMixin } from './plugins/mixins/toast'

createApp(App)
    .use(vuetify)
    .use(router)
    .mixin(dateMixin)
    .mixin(toastMixin)
    .mount('#app')
