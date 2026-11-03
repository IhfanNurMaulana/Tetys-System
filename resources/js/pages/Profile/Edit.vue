<template>
    <main-layout>
        <Head title="Edit Profil" />
        <v-container>
            <div class="text-h4">Profil User</div>
            <v-form @submit.prevent="updateUser">
                <v-card elevation="1" class="mt-3">
                    <v-card-text>
                        <v-text-field
                            variant="outlined"
                            v-model="form.name"
                            label="Nama"
                            :error-messages="form.errors.name || ''"
                        />
                        <v-text-field
                            variant="outlined"
                            v-model="form.email"
                            label="Email"
                            :error-messages="form.errors.email || ''"
                        />
                        <v-text-field
                            variant="outlined"
                            type="password"
                            v-model="form.password"
                            label="Password"
                            :error-messages="form.errors.password || ''"
                        />
                        <v-text-field
                            variant="outlined"
                            type="password"
                            v-model="form.password_confirmation"
                            label="Konfirmasi Password"
                        />
                    </v-card-text>
                    <v-card-actions>
                        <v-btn
                            color="primary"
                            variant="flat"
                            type="submit"
                        >Simpan Perubahan</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>

            <v-snackbar
                v-model="snackbar.show"
                :color="snackbar.color"
            >
                {{ snackbar.text }}
            </v-snackbar>
        </v-container>
    </main-layout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'

// Menyediakan berbagai data
const loading = ref(false)
const snackbar = ref({ show: false, text: '', color: '' })

const props = defineProps({
    user: {
        type: Object,
        default: () => ({}),
    },
})

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
})

// Method untuk menampilkan alert (snackbar)
const showSnackbar = (text, color) => {
    snackbar.value = { show: true, text: text, color: color }
}

// Menyimpan data dari form
const updateUser = () => {
    loading.value = true
    form.patch("/profile", {
        onSuccess: () => {
            showSnackbar('Data berhasil diperbarui!', 'success')
            loading.value = false
            form.password = ''
            form.password_confirmation = ''
        },
        onError: () => {
            loading.value = false
        }
    })
}
</script>
