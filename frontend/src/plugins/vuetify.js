import "@mdi/font/css/materialdesignicons.css"
import "vuetify/styles"

import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

import { createVuetify } from "vuetify";

export default createVuetify({
  components,
  directives,
  styles: {
    configFile: '@/styles/main.scss'
  },
  theme: {
    defaultTheme: "light",
  },
});