<template>
    <v-card elevation="1" class="mt-3">
        <v-card-title v-if="reportType!=0">
            <v-btn color="primary" flat @click="exportPDF">
                Ekspor PDF
            </v-btn>
        </v-card-title>
        <v-data-table :headers="headers" :items="items" :loading="loading" />
    </v-card>
</template>

<script setup>
import { ref, onUpdated } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
    loading: Boolean,
    reportType: Number,
})

const loading = ref(props.loading)
const reportType = ref(props.reportType)
const items = ref(usePage().props.reportdata)
onUpdated(() => {
    items.value = usePage().props.reportdata
})

const headers = ref([
    { title: 'Tanggal', value: 'transaction_date' },
    { title: 'Nama', value: 'name' },
    { title: 'Kelas', value: 'grade_name' },
    { title: 'Setoran', value: 'deposit' },
    { title: 'Penarikan', value: 'withdrawal' },
    { title: 'Saldo', value: 'balance' },
])

const exportPDF = () => {
    window.location.href = '/report/download/3'
}
</script>
