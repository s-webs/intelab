<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    step: {
        type: Object,
        required: true,
    },
});

// Берём объект written или пустой дефолт
const written = props.step.written || { text: '', points: null };
</script>

<template>
    <div class="p-4">
        <!-- Заголовок шага -->
        <h2 class="text-xl font-semibold mb-4">
            {{ props.step.name || 'Письменное задание' }}
        </h2>

        <!-- Текст задания -->
        <div v-if="written.text" class="mb-4">
            <strong>Вопрос / Текст задания:</strong>
            <p class="mt-1">{{ written.text }}</p>
        </div>
        <div v-else class="text-gray-500 italic mb-4">
            У этого шага нет письменного задания.
        </div>

        <!-- Баллы -->
        <div v-if="written.points" class="text-sm text-gray-500 mb-4">
            <strong>Баллы за задание:</strong> {{ written.points }}
        </div>
    </div>
</template>

<style scoped>
/* при необходимости добавьте свои стили */
</style>
