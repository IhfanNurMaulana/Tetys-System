<template>
  <v-dialog :model-value="showConfirm" @update:model-value="closeConfirm" max-width="400" persistent>
    <v-card>
      <v-card-title class="text-h6 font-weight-bold text-error">
        Konfirmasi Hapus
      </v-card-title>

      <v-card-text>
        Apakah Anda yakin ingin menghapus data kelas ini? Data yang dihapus tidak dapat dikembalikan.
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="grey-darken-1" variant="text" @click="closeConfirm">
          Batal
        </v-btn>
        <v-btn color="error" variant="flat" @click="confirmOk">
          Ya, Hapus
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
// Menerima data (props) yang dikirim dari TableData.vue
defineProps({
  showConfirm: Boolean,
  confirmOk: Function,
  closeConfirm: Function
})
</script>
