<template>
  <main-layout>
    <Head title="Kelas" />
    <v-container>
      <div class="text-h4 mb-4">Data Kelas</div>

      <!-- Menggunakan komponen table-data -->
      <table-data :loading="loading" @edit="editGrade" @alert="showSnackbar">
        <template v-slot:button>
          <v-btn color="primary" class="mr-2" flat @click="createGrade">
            Tambah
          </v-btn>
        </template>
      </table-data>

      <!-- Menggunakan komponen form-dialog -->
      <v-dialog v-model="openModal" max-width="500">
        <form-dialog
          :formData="formData"
          @alert="showSnackbar"
          @toggle-loading="(val) => loading = val"
          @close-modal="openModal = false"
        />
      </v-dialog>

      <!-- Menggunakan komponen snackbar -->
      <v-snackbar v-model="snackbar.show" :color="snackbar.color">
        {{ snackbar.text }}
      </v-snackbar>
    </v-container>
  </main-layout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Head } from '@inertiajs/vue3'

// Impor Komponen
import MainLayout from '@/layouts/MainLayout.vue'
import TableData from './TableData.vue'
import FormDialog from './FormDialog.vue'

// Menyiapkan data
const openModal = ref(false)
const loading = ref(false)
const formData = reactive({ edit: false, data: null })
const snackbar = ref({ show: false, text: '', color: '' })

// Method untuk menampilkan alert (snackbar)
const showSnackbar = (text, color) => {
  snackbar.value = { show: true, text: text, color: color }
}

// Method untuk buka form tambah data
const createGrade = () => {
  formData.edit = false
  openModal.value = true
}

// Method untuk buka form edit data
const editGrade = (grade) => {
  formData.edit = true
  formData.data = grade
  openModal.value = true
}
</script>
