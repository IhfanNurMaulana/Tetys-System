<template>
  <v-card elevation="1" class="mt-3">

    <!-- Menyediakan slot untuk tempat tombol -->
    <v-card-title class="d-flex align-center pe-2">
      <slot name="button"></slot>
    </v-card-title>

    <!-- Membuat tabel data -->
    <v-data-table :headers="headers" :items="items" :loading="loading" :mobile="isMobile">
      <template v-slot:item.actions="{ item }">
        <div class="d-flex justify-end align-center">
          <v-btn size="small" variant="text" icon="mdi-pencil"
            @click="$emit('edit', item)" />
          <v-btn size="small" variant="text" icon="mdi-delete"
            @click="openConfirm(item.id)" />
        </div>
      </template>
    </v-data-table>

    <confirm :showConfirm="showConfirm" :confirmOk="deleteData" :closeConfirm="closeConfirm" />

  </v-card>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { useDisplay } from 'vuetify'

// Pastikan lokasi file Confirm.vue sesuai dengan struktur folder Anda.
import Confirm from '@/components/Confirm.vue'

// Mendefinisikan event dan props
const emit = defineEmits(['alert', 'edit'])
const props = defineProps({
  loading: Boolean,
})

// Mengatur tabel responsive
const { xs } = useDisplay()
const isMobile = ref(xs.value)

// Menyiapkan data
const showConfirm = ref(false)
const idDelete = ref(null)

// Menggunakan computed agar data langsung terbaca saat halaman direfresh
const items = computed(() => usePage().props.grades)

// Membuat data header tabel
const headers = ref([
  { title: 'Nama Kelas', value: 'grade_name', sortable: true },
  { title: 'Aksi', value: 'actions', width: 100 },
])

// Method untuk menampilkan konfirmasi hapus
const openConfirm = (id) => {
  showConfirm.value = true
  idDelete.value = id
}

// Method untuk menutup konfirmasi hapus
const closeConfirm = () => {
  showConfirm.value = false
  idDelete.value = null
}

// Method untuk proses hapus data
const deleteData = () => {
  router.delete('/grade/' + idDelete.value, {
    onSuccess: () => {
      emit('alert', 'Data berhasil dihapus!', 'success')
    }
  })
  closeConfirm()
}
</script>
