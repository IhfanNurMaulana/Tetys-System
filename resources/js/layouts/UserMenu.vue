<template>
  <v-menu width="150px">
    <template v-slot:activator="{ props }">
      <v-btn icon v-bind="props">
        <v-avatar>
          <img :src="user.avatar" :alt="user.name" />
        </v-avatar>
      </v-btn>
    </template>

    <v-list>
      <v-list-item @click="navigate('/profile', 'get')" title="Profil" />
      <v-list-item @click="navigate('/logout', 'post')" title="Logout" />
    </v-list>
  </v-menu>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

const user = {
  avatar: 'https://api.dicebear.com/9.x/personas/svg?backgroundColor=b6e3f4&seed=JohnDoe',
  name: 'John Doe',
}

const navigate = (route, routeMethod) => {
  router.visit(route, {
    method: routeMethod,
  })
}
</script>
