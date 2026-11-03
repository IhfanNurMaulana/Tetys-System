<template>
    <v-form @submit.prevent="importData">
        <v-card>
            <v-card-title>
                <span class="font-bold">Import Siswa</span>
            </v-card-title>
            <v-card-text>
                <v-alert border="start" variant="tonal" color="info" class="mb-6">
                    <p>Gunakan format yang disediakan untuk import data siswa.</p>
                    <v-btn color="success" prepend-icon="mdi-download" @click="downloadExcel" class="mt-2">
                        Download Format Excel
                    </v-btn>
                </v-alert>

                <v-select
                    variant="outlined"
                    v-model="form.grade_id"
                    label="Kelas"
                    :items="grades"
                    :error-messages="form.errors.grade_id || ''"
                />

                <v-file-input
                    variant="outlined"
                    prepend-icon=""
                    prepend-inner-icon="mdi-file-excel"
                    v-model="form.file"
                    label="Upload File Excel"
                    :error-messages="form.errors.file || ''"
                />
            </v-card-text>

            <v-card-actions>
                <v-spacer />
                <v-btn
                    color="warning"
                    variant="flat"
                    @click="$emit('close-modal')"
                >Batal</v-btn>
                <v-btn
                    color="primary"
                    variant="flat"
                    type="submit"
                >Import</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3'

// Mendefinisikan event dan props
const emit = defineEmits(['close-modal', 'alert', 'toggle-loading'])

// Ambil data dari props halaman (dikirim dari Controller)
const grades = usePage().props.grades.map(item => ({
    value: item.id,
    title: item.grade_name,
}))

// Menyiapkan data untuk form
const form = useForm({
    grade_id: '',
    file: [],
})

// Method untuk import data
const importData = async () => {
    emit('toggle-loading', true)
    form.post("/student/import", {
        onSuccess: () => {
            emit('close-modal')
            emit('alert', 'Data berhasil diimport!', 'success')
            emit('toggle-loading', false)
            form.reset()
            form.clearErrors()
        },
        onError: () => {
            emit('toggle-loading', false)
        }
    })
}

// Method untuk download format excel
const downloadExcel = () => {
    window.location.href = '/student/download-excel'
}
</script>
