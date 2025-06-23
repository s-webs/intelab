<script setup>
import { defineProps, computed } from 'vue';

const props = defineProps({
    step: {
        type: Object,
        required: true,
    },
});

// берём объект matching или дефолт
const matching = props.step.matching || {
    description: '',
    question: '[]',
    answers: '[]',
};

// парсим JSON-строки в массивы
const questions = computed(() => {
    try {
        return JSON.parse(matching.question);
    } catch {
        return [];
    }
});

const answers = computed(() => {
    try {
        return JSON.parse(matching.answers);
    } catch {
        return [];
    }
});

// строим правильные пары по id
const pairs = computed(() => {
    const map = answers.value.reduce((acc, a) => {
        acc[a.id] = a.answer;
        return acc;
    }, {});
    return questions.value.map(q => ({
        id: q.id,
        question: q.question,
        answer: map[q.id] ?? '—',
    }));
});
</script>

<template>
    <div class="p-4">
        <!-- Заголовок шага -->
        <h2 class="text-xl font-semibold mb-2">{{ props.step.name || 'Сопоставление' }}</h2>
        <!-- Описание теста -->
        <p class="mb-4 text-gray-700">{{ matching.description }}</p>

        <!-- Таблица «вопрос — ответ» -->
        <table class="w-full border-collapse">
            <thead>
            <tr>
                <th class="border-b py-1 text-left">Вопрос</th>
                <th class="border-b py-1 text-left">Ответ</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="pair in pairs" :key="pair.id">
                <td class="border-t py-1">{{ pair.question }}</td>
                <td class="border-t py-1">{{ pair.answer }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
/* Если нужен более плотный вид, можно добавить */
table {
    border-spacing: 0;
}
</style>
