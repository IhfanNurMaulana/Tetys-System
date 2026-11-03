<template>
    <main-layout>
        <Head title="Siswa" />
        <v-container>
            <div class="text-h4">Data Siswa</div>

            <!-- Menggunakan komponen table-data -->
            <table-data :loading="loading" @edit="editStudent" @alert="showSnackbar" :items="students">
                <template v-slot:button>
                    <v-btn color="primary" class="mr-2" flat @click="createStudent">Tambah</v-btn>
                    <v-btn color="success" flat @click="openImport = true">Import</v-btn>
                </template>
            </table-data>

            <!-- Menggunakan komponen form-dialog -->
            <v-dialog v-model="openModal" max-width="960">
                <form-dialog :formData="formData" @alert="showSnackbar" @toggle-loading="(val) => loading = val" @close-modal="openModal = false" />
            </v-dialog>

            <!-- Menggunakan komponen import-dialog -->
            <v-dialog v-model="openImport" max-width="500">
                <import-dialog @alert="showSnackbar" @toggle-loading="(val) => loading = val" @close-modal="openImport = false" />
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
import MainLayout from '@/Layouts/MainLayout.vue'
import TableData from './DataTable.vue'
import FormDialog from './FormDialog.vue'
import ImportDialog from './ImportDialog.vue'

// Menyiapkan data
const openModal = ref(false)
const openImport = ref(false)
const loading = ref(false)
const formData = reactive({ edit: false, data: null })
const snackbar = ref({ show: false, text: '', color: '' })

const props = defineProps({
    students: { type: Array, default: () => [] },
})

// Method untuk menampilkan alert (snackbar)
const showSnackbar = (text, color) => {
    snackbar.value = { show: true, text: text, color: color }
}

// Method untuk buka form tambah data
const createStudent = () => {
    formData.edit = false
    openModal.value = true
}

// Method untuk buka form edit data
const editStudent = (student) => {
    formData.edit = true
    formData.data = student
    openModal.value = true
}
</script>
