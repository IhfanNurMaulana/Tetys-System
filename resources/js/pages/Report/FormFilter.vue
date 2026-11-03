<template>
    <v-form @submit.prevent="updateFilter">
        <v-card>
            <v-card-text>
                <v-row class="mt-4">
                    <v-col class="py-0">
                        <v-select variant="outlined"
                            v-model="form.report_type" label="Jenis Laporan"
                            :items="reports"
                            :error-messages="form.errors.report_type || ''">
                        </v-select>
                    </v-col>
                    <v-col class="py-0" v-if="form.report_type==2">
                        <v-select variant="outlined"
                            v-model="form.grade" label="Kelas"
                            :items="grades"
                            :error-messages="form.errors.grade || ''">
                        </v-select>

                    </v-col>
                </v-row>

                <v-row v-if="form.report_type==4">
                    <v-col class="py-0">

                        <v-select variant="outlined"
                            v-model="form.grade" label="Kelas"
                            :items="grades"
                            @update:modelValue="getStudents(0)"
                            :error-messages="form.errors.grade || ''">
                            </v-select>

                    </v-col>
                    <v-col class="py-0">

                        <v-autocomplete variant="outlined"
                            v-model="form.student" label="Nama Siswa"
                            :items="students"
                            :error-messages="form.errors.student || ''">
                        </v-autocomplete>

                    </v-col>
                </v-row>

                <v-row v-if="form.report_type==3 || form.report_type==4">
                    <v-col class="py-0">

                        <v-date-input variant="outlined" prepend-icon=""
                            prepend-inner-icon="mdi-calendar"
                            v-model="form.start_date" label="Tanggal Mulai"
                            :error-messages="form.errors.start_date || ''">
                        </v-date-input>

                    </v-col>
                    <v-col class="py-0">

                        <v-date-input variant="outlined" prepend-icon=""
                            prepend-inner-icon="mdi-calendar"
                            v-model="form.end_date" label="Tanggal Selesai"
                            :error-messages="form.errors.end_date || ''">
                        </v-date-input>

                    </v-col>
                </v-row>

            </v-card-text>
            <v-card-actions v-if="form.report_type!=0">
                <v-spacer />
                <v-btn color="primary" variant="flat"
                    type="submit">Tampilkan</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script setup>
import { ref } from 'vue'
import { router, useForm, usePage } from '@inertiajs/vue3'

const emit = defineEmits(['change-type', 'toggle-loading'])

const reports = ref([
    { value: 0, title: 'Pilih Laporan' },
    { value: 1, title: 'Laporan Saldo Kelas' },
    { value: 2, title: 'Laporan Saldo Siswa' },
    { value: 3, title: 'Laporan Transaksi' },
    { value: 4, title: 'Laporan Transaksi Siswa' },
])

const grades = usePage().props.grades.map(item => ({
    value: item.id,
    title: item.grade_name
}))

const form = useForm({
    id: "",
    report_type: 0,
    grade: null,
    student: null,
    start_date: null,
    end_date: null,
})

const students = ref([])
const getStudents = () => {
    form.student = null
    router.post('/report/student', {
        grade: form.grade
    }, {
        preserveState: true,
        only: ['students'],
        onSuccess: () => {
            students.value = usePage().props.students.map(item => ({
                value: item.id,
                title: '(' + item.nis + ') ' + item.name
            }))
        }
    })
}

// Method untuk memperbarui filter laporan
const updateFilter = async () => {
    emit('toggle-loading', true)
    form.post("/report", {
        onSuccess: () => {
            emit('change-type', form.report_type)
            emit('toggle-loading', false)
        }
    })
}
</script>
