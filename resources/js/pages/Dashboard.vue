<template>
    <main-layout>
        <Head title="Dashboard" />
        <v-container>
            <v-row>
                <!-- Menampilkan card dari cardData -->
                <v-col v-for="(data, i) in cardData"
                    :key="i" cols="6" md="3">
                    <v-card :color="data.color" class="mx-auto">
                        <v-card-item>
                            <div class="text-overline mb-1">
                                {{ data.label }}
                            </div>
                            <div class="text-h4 mb-1">
                                {{ data.data }}
                            </div>
                        </v-card-item>
                    </v-card>
                </v-col>
            </v-row>

            <v-row>
                <!-- Menampilkan chart saldo per kelas -->
                <v-col cols="12" md="6">
                    <v-card style="height: 480px">
                        <v-card-title>Saldo per Kelas</v-card-title>
                        <v-card-item>
                            <Bar
                                id="chart-balance"
                                :options="chartOptions"
                                :data="chartData"
                            />
                        </v-card-item>
                    </v-card>
                </v-col>

                <!-- Menampilkan siswa dengan saldo terbanyak -->
                <v-col cols="12" md="6">
                    <v-card style="height: 480px">
                        <v-card-title>Saldo Terbanyak</v-card-title>
                        <v-table>
                            <thead>
                                <tr>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in topBalance"
                                    :key="item.name"
                                >
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.grade.grade_name }}</td>
                                    <td>{{ item.balance }}</td>
                                </tr>
                            </tbody>
                        </v-table>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </main-layout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'
import { Bar } from 'vue-chartjs'
import {
    Chart as ChartJS, Title, Tooltip, Legend,
    BarElement, CategoryScale, LinearScale
} from 'chart.js'

// Mendaftarkan chart
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

// Mendefinisikan props
const props = defineProps({
    cardData: { type: Array, default: () => [] },
    gradeName: { type: Array, default: () => [] },
    gradeBalance: { type: Array, default: () => [] },
    topBalance: { type: Array, default: () => [] },
})

// Mendefinisikan data chart
const chartData = {
    labels: props.gradeName,
    datasets: [{
        backgroundColor: '#9c27b0',
        data: props.gradeBalance
    }]
}

// Pengaturan tampilan chart
const chartOptions = {
    responsive: true,
    plugins: {
        legend: { display: false }
    }
}
</script>
