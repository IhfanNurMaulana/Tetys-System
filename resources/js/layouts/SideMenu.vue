<template>
  <v-navigation-drawer v-model="onDrawerOpen" permanent elevation="1">
    <v-list>
      <v-list-item
        v-for="(item, index) in items"
        :key="index"
        @click="navigate(item.route)"
      >
        <template v-slot:prepend>
          <v-icon :icon="item.icon" />
        </template>
        <v-list-item-title v-text="item.text" />
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  drawerOpen: Boolean,
})

const items = [
  { text: 'Dashboard', icon: 'mdi-view-dashboard', route: '/' },
  { text: 'Kelas', icon: 'mdi-star', route: '/grade' },
  { text: 'Siswa', icon: 'mdi-account-school', route: '/student' },
  { text: 'Transaksi', icon: 'mdi-file-document-edit', route: '/transaction' },
  { text: 'Laporan', icon: 'mdi-printer', route: '/report' },
]

const navigate = (route) => {
  router.visit(route)
}

const onDrawerOpen = ref(props.drawerOpen)

watch(() => props.drawerOpen, (newValue) => {
  onDrawerOpen.value = newValue
})
</script>
