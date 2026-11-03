<template>
  <v-app>
    <!-- Tambahkan atribut permanent dan absolute/app agar menempel rapi -->
    <side-menu :drawerOpen="drawerOpen" />

    <v-app-bar app color="primary" flat border>
      <v-app-bar-nav-icon @click.stop="toggleDrawer" />
      <v-toolbar-title>Tetys System</v-toolbar-title>
      <v-spacer />
      <toggle-theme />
      <user-menu />
    </v-app-bar>

    <v-main>
      <v-container fluid>
        <slot></slot>
      </v-container>
    </v-main>

    <v-footer app border>
      <span>&copy; 2026 Tetys System. All rights reserved.</span>
    </v-footer>
  </v-app>
</template>

<script setup>
import { ref } from 'vue';
import SideMenu from './SideMenu.vue';
import UserMenu from './UserMenu.vue';
import ToggleTheme from './ToggleTheme.vue';

// Ubah default true agar saat halaman dibuka, sidebar langsung tampil terbuka seperti di buku
const drawerOpen = ref(JSON.parse(localStorage.getItem('drawerOpen')) ?? true);

function toggleDrawer() {
  drawerOpen.value = !drawerOpen.value;
  localStorage.setItem('drawerOpen', drawerOpen.value);
}
</script>
