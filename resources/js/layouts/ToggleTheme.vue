<template>
  <v-btn icon @click="toggleTheme">
    <v-icon icon="mdi-brightness-7" />
  </v-btn>
</template>

<script setup>
import { useTheme } from 'vuetify'

const theme = useTheme()
const toggleTheme = () => {
  theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark'
  localStorage.setItem('theme', theme.global.name.value)
}
</script>
