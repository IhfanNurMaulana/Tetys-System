<template>
    <main-layout>
        <Head title="Laporan" />
        <v-container>
            <div class="text-h4">Cetak Laporan</div>

            <form-filter
                @toggle-loading="(val) => loading = val"
                @change-type="(val) => reportType = val"
            />

            <table-data-one v-if="reportType==1" :loading="loading" />
            <table-data-two v-if="reportType==2" :loading="loading" />
            <table-data-three v-if="reportType==3" :loading="loading" />
            <table-data-four v-if="reportType==4" :loading="loading" />
        </v-container>
    </main-layout>
</template>

<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'

import MainLayout from '@/Layouts/MainLayout.vue'
import TableDataOne from './TableDataOne.vue'
import TableDataTwo from './TableDataTwo.vue'
import TableDataThree from './TableDataThree.vue'
import TableDataFour from './TableDataFour.vue'
import FormFilter from './FormFilter.vue'

// Menyiapkan berbagai data
const loading = ref(false)
const reportType = ref(0)
</script>
