<template>
    <v-form @submit.prevent="saveData">
        <v-card>
            <v-card-title>
                <span class="font-bold">{{ createTitle }}</span>
            </v-card-title>
            <v-card-text>

                <v-select variant="outlined"
                    v-model="form.grade_id" label="Kelas"
                    :items="grades"
                    @update:modelValue="getStudents(0)"
                    :error-messages="form.errors.grade_id || ''" />

                <v-autocomplete variant="outlined"
                    v-model="form.student_id" label="Nama Siswa"
                    :items="students"
                    :error-messages="form.errors.student_id || ''" />

                <v-text-field variant="outlined"
                    v-model="form.amount" label="Nominal"
                    @input="formatCurrency"
                    :error-messages="form.errors.amount || ''" />

                <v-textarea variant="outlined"
                    v-model="form.description" label="Deskripsi"
                    :error-messages="form.errors.description || ''" />

            </v-card-text>

            <v-card-actions>
                <v-spacer />
                <v-btn color="warning" variant="flat"
                    @click="$emit('close-modal')">Batal</v-btn>
                <v-btn color="primary" variant="flat"
                    type="submit">Simpan</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script setup>
import { ref, watch } from 'vue'
import { router, useForm, usePage } from '@inertiajs/vue3'

// Mendefinisikan event dan props
const emit = defineEmits(['close-modal', 'alert', 'toggle-loading'])
const props = defineProps({
    formData: Object,
})

// Menyiapkan data reaktif
const createTitle = ref('')
const students = ref([])

/* Ambil data kelas dari props halaman (dikirim dari Controller) */
const grades = usePage().props.grades.map(item => ({
    value: item.id,
    title: item.grade_name
}))

// Menyiapkan data untuk form
const form = useForm({
    id: "",
    grade_id: "",
    student_id: "",
    transaction_type: "",
    amount: "",
    description: "",
})

// Mendeteksi perubahan props formData
watch(() => props.formData, (val) => {
    form.reset()
    form.clearErrors()
    form.transaction_type = val.type
    if (val.type === 'in') createTitle.value = "Input Setoran"
    else createTitle.value = "Input Penarikan"
}, { deep: true, immediate: true })

// Method untuk ambil data siswa sesuai kelas yang dipilih
const getStudents = (value) => {
    form.student_id = null
    router.post('/transaction/student', {
        grade: form.grade_id
    }, {
        preserveState: true,
        only: ['students'],
        onSuccess: () => {
            students.value = usePage().props.students.map(item => ({
                value: item.id,
                title: '(' + item.nis + ') ' + item.name
            }))
            if (value !== 0) form.student_id = value
        }
    })
}

// Method untuk menyimpan Data
const saveData = async () => {
    emit('toggle-loading', true)
    form.post("/transaction", {
        onSuccess: () => {
            emit('close-modal')
            emit('alert', 'Data berhasil disimpan!', 'success')
            emit('toggle-loading', false)
            form.reset()
        },
        onError: () => {
            emit('toggle-loading', false)
        }
    })
}

// Method untuk format mata uang
const formatCurrency = () => {
    form.amount = form.amount.replace(/\D/g, '')
        .replace(/\B(?=(\d{3})+(?!\d))/g, '.')
}
</script>
