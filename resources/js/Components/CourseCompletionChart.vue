<script setup>
import { ref, onMounted } from 'vue';
import { Chart, registerables } from 'chart.js';

// Регистрируем необходимые модули Chart.js
Chart.register(...registerables);

const props = defineProps({
    totalStudents: Number,
    completedStudents: Number,
});

const chart = ref(null);

onMounted(() => {
    const ctx = chart.value.getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Статистика курса'],
            datasets: [
                {
                    label: 'Общее количество студентов',
                    data: [props.totalStudents],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                },
                {
                    label: 'Завершили курс',
                    data: [props.completedStudents],
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                },
            ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            responsive: true,
            maintainAspectRatio: false,
        },
    });
});
</script>

<template>
    <div>
        <canvas ref="chart"></canvas>
    </div>
</template>

<style scoped>

</style>
