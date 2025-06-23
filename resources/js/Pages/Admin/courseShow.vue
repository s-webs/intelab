<script setup>
import { reactive, ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Компоненты предпросмотра — создай их в src/Components/StepPreviews/
import ContentPreview from '@/Pages/Admin/Components/ContentPreview.vue';
import QuizPreview from '@/Pages/Admin/Components/QuizPreview.vue';
import MatchingPreview from '@/Pages/Admin/Components/MatchingPreview.vue';
import WrittenPreview from '@/Pages/Admin/Components/WrittenPreview.vue';

const props = defineProps({
    course: {
        type: Object,
        required: true,
    },
});

// состояния «аккордеона»
const openModules = reactive({});
const openLessons = reactive({});

// состояния модалки
const showModal = ref(false);
const selectedStep = ref(null);

/** Аккордеон */
function toggleModule(moduleId) {
    openModules[moduleId] = !openModules[moduleId];
}
function toggleLesson(lessonId) {
    openLessons[lessonId] = !openLessons[lessonId];
}

/** Открыть модалку предпросмотра */
function openPreview(step) {
    selectedStep.value = step;
    showModal.value = true;
}
/** Закрыть модалку */
function closeModal() {
    showModal.value = false;
    selectedStep.value = null;
}

/** Выбираем нужный превью-компонент по type */
const previewComponent = computed(() => {
    if (!selectedStep.value) return null;
    switch (selectedStep.value.type) {
        case 'content':  return ContentPreview;
        case 'quiz':     return QuizPreview;
        case 'matching': return MatchingPreview;
        case 'written':  return WrittenPreview;
        default:         return null;
    }
});
</script>

<template>
    <AppLayout>
        <div class="container mx-auto p-2 mt-4">
            <div class="bg-white shadow-lg rounded-[15px] p-4">
                <h1 class="text-2xl font-semibold mb-4">{{ course.name }}</h1>

                <div
                    v-for="module in course.modules"
                    :key="module.id"
                    class="mb-2 border rounded-lg"
                >
                    <!-- Модуль -->
                    <button
                        @click="toggleModule(module.id)"
                        class="w-full text-left px-4 py-2 flex justify-between items-center bg-gray-100 rounded-t-lg"
                    >
                        <span class="font-medium">{{ module.name }}</span>
                        <svg
                            :class="{ 'transform rotate-180': openModules[module.id] }"
                            class="w-5 h-5 transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Уроки -->
                    <transition name="fade">
                        <div v-show="openModules[module.id]" class="px-4 py-2">
                            <div
                                v-for="lesson in module.lessons"
                                :key="lesson.id"
                                class="mb-1 border-l-2 border-gray-300 pl-4"
                            >
                                <button
                                    @click="toggleLesson(lesson.id)"
                                    class="w-full text-left flex justify-between items-center py-1"
                                >
                                    <span>{{ lesson.name }}</span>
                                    <svg
                                        :class="{ 'transform rotate-180': openLessons[lesson.id] }"
                                        class="w-4 h-4 transition-transform"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Шаги -->
                                <transition name="fade">
                                    <ul
                                        v-show="openLessons[lesson.id]"
                                        class="mt-1 ml-4 list-disc list-inside text-gray-700"
                                    >
                                        <li
                                            v-for="step in lesson.steps"
                                            :key="step.id"
                                            class="cursor-pointer hover:underline py-1"
                                            @click="openPreview(step)"
                                        >
                                            {{ step.name }} <span class="text-sm text-gray-500">({{ step.type }})</span>
                                        </li>
                                    </ul>
                                </transition>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>

            <!-- Модалка -->
            <transition name="fade">
                <div
                    v-if="showModal"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                >
                    <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-2xl p-6 relative">
                        <button
                            @click="closeModal"
                            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800"
                        >✕</button>

                        <component
                            :is="previewComponent"
                            :step="selectedStep"
                        />
                    </div>
                </div>
            </transition>
        </div>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    max-height: 0;
}
.fade-enter-to,
.fade-leave-from {
    opacity: 1;
    max-height: 500px;
}
</style>
