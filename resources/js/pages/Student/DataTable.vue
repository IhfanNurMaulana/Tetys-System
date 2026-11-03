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
                    <v-text-field
                        flat
                        hide-details
                        v-model="search"
                        density="compact"
                        placeholder="Search"
                        prepend-inner-icon="mdi-magnify"
                        variant="solo-filled"
                    />
                </v-col>
            </v-row>
        </v-card-title>

        <!-- Membuat tabel data -->
        <v-data-table
            :headers="headers"
            :items="items"
            :loading="loading"
            :mobile="isMobile"
            v-model:items-per-page="itemsPerPage"
            v-model:sort-by="sort"
            v-model:page="page"
            :items-length="totalItems"
            :search="search"
            @update:options="fetchStudents"
        >
            <template v-slot:item.photo="{ item }">
                <v-img :src="item.photo" contain width="50" class="ma-1"></v-img>
            </template>

            <template v-slot:item.actions="{ item }">
    <div class="d-flex justify-center align-center">
        <v-btn
            size="small"
            variant="text"
            icon="mdi-pencil"
            color="primary"
            @click="$emit('edit', item)"
        />
        <v-btn
            size="small"
            variant="text"
            icon="mdi-delete"
            color="error"
            @click="openConfirm(item.id)"
        />
    </div>
</template>
        </v-data-table>

        <confirm
            :showConfirm="showConfirm"
            :confirmOk="deleteData"
            :closeConfirm="closeConfirm"
        />
    </v-card>
</template>

<script setup>
import { ref, onUpdated } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { useDisplay } from 'vuetify'

import Confirm from '@/Components/Confirm.vue'

// Mendefinisikan event dan props
const emit = defineEmits(['alert', 'edit'])
const props = defineProps({
    loading: Boolean,
})

// Mengatur tabel responsive
const { xs } = useDisplay()
const isMobile = ref(xs.value)

// Menyiapkan data
const showConfirm = ref(false)
const idDelete = ref(null)
const loading = ref(props.loading)
const items = ref([])
onUpdated(() => {
    items.value = usePage().props.students;
})

// Menyiapkan data untuk tabel
const totalItems = usePage().props.total
const search = ref('')
const sort = ref([])
const page = ref(1)
const itemsPerPage = ref(10)

// Membuat data header tabel
const headers = ref([
    { title: 'NIS', value: 'nis', sortable: true },
    { title: 'Nama', value: 'name', sortable: true },
    { title: 'Jenis Kelamin', value: 'gender', sortable: true },
    { title: 'Tempat Lahir', value: 'place_of_birth', sortable: true },
    { title: 'Tanggal Lahir', value: 'date_of_birth', sortable: true },
    { title: 'Kelas', value: 'grade.grade_name' },
    { title: 'Foto', value: 'photo' },
    { title: 'Aksi', value: 'actions', align: 'center', sortable: false },
])

// Method untuk update data tabel
const fetchStudents = () => {
    router.get('/student', {
        page: page.value ? page.value : undefined,
        perPage: itemsPerPage.value ? itemsPerPage.value : undefined,
        search: search.value ? search.value : undefined,
        sort_by: sort.value.length > 0 ? sort.value[0].key : undefined,
        sort_dir: sort.value.length > 0 ? sort.value[0].order : undefined,
    }, {
        preserveState: true,
        only: ['students', 'total'],
    })
}

// Method untuk menampilkan konfirmasi hapus
const openConfirm = (id) => {
    showConfirm.value = true
    idDelete.value = id
}

// Method untuk menutup konfirmasi hapus
const closeConfirm = () => {
    showConfirm.value = false
    idDelete.value = null
}

// Method untuk menghapus data
const deleteData = () => {
    loading.value = true
    router.delete('/student/' + idDelete.value, {
        onSuccess: () => {
            emit('alert', 'Data berhasil dihapus!', 'success')
            loading.value = false
        }
    })
    closeConfirm()
}
</script>
