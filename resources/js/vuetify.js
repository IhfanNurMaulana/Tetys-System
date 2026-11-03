// Styles
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

// Composables
import { createVuetify } from 'vuetify'
import { VDateInput } from 'vuetify/components/VDateInput'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

export default createVuetify({
  directives,
  theme: {
    defaultTheme: localStorage.getItem('theme') || 'light',
  },
  components: {
    ...components,
    VDateInput,
  }
})
