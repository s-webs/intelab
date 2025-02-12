<script setup>
import { ref, onMounted, watch } from 'vue';
import { Chart, registerables } from 'chart.js';

// Регистрируем необходимые модули Chart.js
Chart.register(...registerables);

const props = defineProps({
    labels: {
        type: Array,
        required: true,
    },
    datasets: {
        type: Array,
        required: true,
    },
});

const chart = ref(null);
let chartInstance = null;

// Создаем график
const createChart = () => {
    const ctx = chart.value.getContext('2d');

    chartInstance = new Chart(ctx, {
        type: 'line', // Используем линейный график
        data: {
            labels: props.labels,
            datasets: props.datasets,
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top', // Позиция легенды
                },
                tooltip: {
                    enabled: true, // Включить подсказки
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Прогресс (%)', // Подпись оси Y
                    },
                },
                x: {
                    title: {
                        display: true,
                        text: 'Период', // Подпись оси X
                    },
                },
            },
        },
    });
};

// Уничтожаем старый график при обновлении данных
const destroyChart = () => {
    if (chartInstance) {
        chartInstance.destroy();
        chartInstance = null;
    }
};

// Создаем график при монтировании компонента
onMounted(() => {
    createChart();
});

// Обновляем график при изменении данных
watch(
    () => [props.labels, props.datasets],
    () => {
        destroyChart();
        createChart();
    },
    { deep: true }
);
// Данные для линейного графика
const lineChartData = {
    labels: ['Неделя 1', 'Неделя 2', 'Неделя 3', 'Неделя 4'], // Метки по оси X
    datasets: [
        {
            label: 'Средний прогресс',
            data: [10, 25, 50, 80], // Данные по оси Y
            borderColor: 'rgba(75, 192, 192, 1)', // Цвет линии
            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Цвет заливки под линией
            fill: true, // Заливка под линией
            tension: 0.4, // Кривизна линии
        },
    ],
};
</script>

<template>
    <div>
        <canvas ref="chart"></canvas>
    </div>
</template>

<style scoped>

</style>
