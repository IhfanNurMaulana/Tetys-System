<template>
    <v-form @submit.prevent="saveData">
        <v-card>
            <v-card-title>
                <span class="font-bold">{{ isEditing ? 'Edit Siswa' : 'Tambah Siswa' }}</span>
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col cols="12" md="6">
                        <v-text-field
                            variant="outlined"
                            v-model="form.nis"
                            label="NIS"
                            :error-messages="form.errors.nis || ''"
                        />
                        <v-text-field
                            variant="outlined"
                            v-model="form.name"
                            label="Nama"
                            :error-messages="form.errors.name || ''"
                        />
                        <v-select
                            variant="outlined"
                            v-model="form.gender"
                            label="Jenis Kelamin"
                            :items="genders"
                            :error-messages="form.errors.gender || ''"
                        />
                        <v-text-field
                            variant="outlined"
                            v-model="form.place_of_birth"
                            label="Tempat Lahir"
                            :error-messages="form.errors.place_of_birth || ''"
                        />
                        <v-date-input
                            variant="outlined"
                            prepend-icon=""
                            prepend-inner-icon="mdi-calendar"
                            v-model="form.date_of_birth"
                            label="Tanggal Lahir"
                            :error-messages="form.errors.date_of_birth || ''"
                        />
                    </v-col>
                    <v-col cols="12" md="6">
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
                            prepend-inner-icon="mdi-image"
                            v-model="form.photo"
                            label="Upload Foto"
                            @change="previewImage"
                            :error-messages="form.errors.photo || ''"
                        />
                        Preview:
                        <v-sheet border="sm" rounded="sm" height="185" class="pa-2">
                            <v-img
                                v-if="imagePreview"
                                :src="imagePreview"
                                max-width="100%"
                                max-height="100%"
                            ></v-img>
                        </v-sheet>
                    </v-col>
                </v-row>
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
                >Simpan</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import moment from 'moment'

// Mendefinisikan event dan props
const emit = defineEmits(['close-modal', 'alert', 'toggle-loading'])
const props = defineProps({ formData: Object })

// Menyiapkan data reaktif
const isEditing = ref(false)
const imagePreview = ref('')
const genders = ref([
    { value: 'L', title: 'Laki-laki' },
    { value: 'P', title: 'Perempuan' },
])

// Ambil data dari props halaman (dikirim dari Controller)
const grades = usePage().props.grades.map(item => ({
    value: item.id,
    title: item.grade_name,
}))

// Menyiapkan data untuk form
const form = useForm({
    id: '',
    nis: '',
    name: '',
    gender: '',
    place_of_birth: '',
    date_of_birth: null,
    grade_id: '',
    _method: 'post',
    photo: [],
})

// Mendeteksi perubahan props data form untuk diterapkan pada table
watch(
    () => props.formData,
    (val) => {
        if (val.edit) {
            form.id = val.data.id
            form.nis = val.data.nis
            form.name = val.data.name
            form.gender = val.data.gender
            form.place_of_birth = val.data.place_of_birth
            form.date_of_birth = new Date(val.data.date_of_birth)
            form.grade_id = val.data.grade_id
            form.photo = null
            form._method = 'PUT'
            imagePreview.value = val.data.photo
            isEditing.value = true
        } else {
            form.reset()
            form.clearErrors()
            imagePreview.value = ''
            isEditing.value = false
        }
    },
    { deep: true, immediate: true }
)

// Method untuk preview gambar yang akan diupload
const previewImage = () => {
    const file = form.photo
    if (file) {
        const reader = new FileReader()
        reader.readAsDataURL(file)
        reader.onload = (e) => {
            imagePreview.value = e.target.result
        }
    }
}

// Method untuk menyimpan data
const saveData = async () => {
    emit('toggle-loading', true)
    form.transform((data) => ({
        ...data,
        date_of_birth: moment(form.date_of_birth).format('YYYY-MM-DD'),
    }))

    if (!isEditing.value) {
        form.post('/student', {
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
    } else {
        form.post('/student/' + form.id, {
            onSuccess: () => {
                emit('close-modal')
                emit('alert', 'Data berhasil diperbarui!', 'success')
                emit('toggle-loading', false)
                form.reset()
            },
            onError: () => {
                emit('toggle-loading', false)
            }
        })
    }
}
</script>
