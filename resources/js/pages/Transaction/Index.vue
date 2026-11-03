<template>
    <main-layout>
        <Head title="Transaksi" />
        <v-container>
            <div class="text-h4">Data Transaksi</div>

            <!-- Menggunakan komponen table-data -->
            <table-data :loading="loading" @alert="showSnackbar">
                <template v-slot:button>
                    <v-btn color="primary" flat @click="newTransaction('in')" class="mr-2">Setoran</v-btn>
                    <v-btn color="primary" flat @click="newTransaction('out')">Penarikan</v-btn>
                </template>
            </table-data>

            <!-- Menggunakan komponen form-dialog -->
            <v-dialog v-model="openModal" max-width="960">
                <form-dialog :formData="formData"
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
import MainLayout from '@/Layouts/MainLayout.vue'
import TableData from './TableData.vue'
import FormDialog from './FormDialog.vue'

// Menyiapkan data
const openModal = ref(false)
const loading = ref(false)
const formData = reactive({ type: null })
const snackbar = ref({ show: false, text: '', color: '' })

// Method untuk menampilkan alert (snackbar)
const showSnackbar = (text, color) => {
    snackbar.value = { show: true, text: text, color: color }
}

// Method untuk buka form transaksi baru
const newTransaction = (type) => {
    formData.type = type
    openModal.value = true
}
</script>
