<script setup>
import { defineProps, computed } from 'vue';

const props = defineProps({
    step: {
        type: Object,
        required: true,
    },
});

const quiz = props.step.quiz || { text: '', answers: '[]', correctAnswerId: null };

const answers = computed(() => {
    try {
        return JSON.parse(quiz.answers);
    } catch {
        return [];
    }
});
</script>

<template>
    <div class="p-4">
        <!-- Заголовок шага -->
        <h2 class="text-xl font-semibold mb-4">{{ props.step.name || 'Вопрос' }}</h2>

        <!-- Текст вопроса -->
        <p class="mb-4">
            <strong>Вопрос:</strong> {{ quiz.text }}
        </p>

        <!-- Список ответов -->
        <div class="mb-4">
            <strong>Ответы:</strong>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li v-for="ans in answers" :key="ans.id">
          <span
              :class="{
              'font-bold text-green-600': ans.id === quiz.correctAnswerId,
              'text-gray-800': ans.id !== quiz.correctAnswerId
            }"
          >
            {{ ans.text }}
          </span>
                    <span
                        v-if="ans.id === quiz.correctAnswerId"
                        class="ml-2 text-sm text-green-600"
                    >
            (правильный)
          </span>
                </li>
            </ul>
        </div>

        <!-- Баллы за вопрос -->
        <p v-if="quiz.points" class="text-sm text-gray-500">
            Баллов: {{ quiz.points }}
        </p>
    </div>
</template>

<style scoped>
/* при необходимости добавьте свои стили */
</style>
