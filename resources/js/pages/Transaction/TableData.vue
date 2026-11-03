<template>
    <v-card elevation="1" class="mt-3">

        <!-- Menyediakan slot untuk tempat tombol -->
        <v-card-title class="d-flex align-center pe-2">
            <v-row>
                <v-col cols="12" md="6">
                    <slot name="button"></slot>
                </v-col>
                <v-col cols="12" md="6">

                    <!-- Membuat input pencarian -->
                    <v-text-field flat hide-details
                        v-model="search"
                        density="compact"
                        placeholder="Search"
                        prepend-inner-icon="mdi-magnify"
                        variant="solo-filled" />

                </v-col>
            </v-row>
        </v-card-title>

        <!-- Membuat tabel data -->
        <v-data-table :headers="headers"
            :items="items"
            :loading="loading"
            :mobile="isMobile"
            v-model:items-per-page="itemsPerPage"
            v-model:sort-by="sort"
            v-model:page="page"
            :items-length="totalItems"
            :search="search"
            @update:options="fetchTransactions" />

    </v-card>
</template>

<script setup>
import { ref, onUpdated } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { useDisplay } from 'vuetify'

// Mendefinisikan props
const props = defineProps({
    loading: Boolean,
})

// Mengatur tabel responsive
const { xs } = useDisplay()
const isMobile = ref(xs.value)

// Menyiapkan data
const loading = ref(props.loading)
const items = ref(usePage().props.transactions);
onUpdated(() => {
    items.value = usePage().props.transactions;
})

// Menyiapkan data untuk tabel
const totalItems = usePage().props.total
const search = ref('')
const sort = ref([])
const page = ref(1)
const itemsPerPage = ref(10)

// Membuat data header tabel
const headers = ref([
    { title: 'Tanggal', value: 'transaction_date', sortable: true },
    { title: 'Nama', value: 'student.name', sortable: true },
    { title: 'Kelas', value: 'student.grade.grade_name', sortable: true },
    { title: 'Jenis Transaksi', value: 'transaction_type', sortable: true },
    { title: 'Nominal', value: 'amount' },
    { title: 'Saldo', value: 'balance' },
    { title: 'Keterangan', value: 'description' },
])

// Method untuk update data tabel
const fetchTransactions = () => {
    router.get('/transaction', {
        page: page.value ? page.value : undefined,
        perPage: itemsPerPage.value ? itemsPerPage.value : undefined,
        search: search.value ? search.value : undefined,
        sort_by: sort.value.length > 0 ? sort.value[0].key : undefined,
        sort_dir: sort.value.length > 0 ? sort.value[0].order : undefined,
    }, {
        preserveState: true,
        only: ['transactions', 'total'],
    })
}
</script>
