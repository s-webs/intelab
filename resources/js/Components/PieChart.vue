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
    data: {
        type: Array,
        required: true,
    },
    colors: {
        type: Array,
        required: true,
    },
    borderColors: {
        type: Array,
        default: () => [],
    },
});

const chart = ref(null);
let chartInstance = null;

// Создаем график
const createChart = () => {
    const ctx = chart.value.getContext('2d');

    chartInstance = new Chart(ctx, {
        type: 'pie', // Используем круговую диаграмму
        data: {
            labels: props.labels,
            datasets: [
                {
                    label: 'Данные',
                    data: props.data,
                    backgroundColor: props.colors,
                    borderColor: props.borderColors.length ? props.borderColors : props.colors,
                    borderWidth: 1,
                },
            ],
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
    () => [props.labels, props.data, props.colors, props.borderColors],
    () => {
        destroyChart();
        createChart();
    },
    { deep: true }
);
</script>

<template>
    <div>
        <canvas ref="chart"></canvas>
    </div>
</template>

<style scoped>

</style>
