<script setup>
import {ref, onMounted, computed} from 'vue';
import draggable from 'vuedraggable';
import {defineProps} from 'vue';
import {router} from "@inertiajs/vue3";
import Loading from "@/Components/Loading.vue";

// Define props from parent
const props = defineProps({
    test: Object,
    stepUser: Object,
    step: Object
});

const emit = defineEmits(['checked']);

const questions = ref(JSON.parse(props.test.question));
const answers = ref(JSON.parse(props.test.answers));
const draggableAnswers = ref([...answers.value]);
const userAnswers = ref([])
const isChecked = ref(false);
const stepUserAnswers = ref(props.stepUser?.answers);
const isTestCompleted = computed(() => stepUserAnswers.value && stepUserAnswers.value.length > 0);
const loading = ref(false)

onMounted(() => {
    if (isTestCompleted.value) {
        userAnswers.value = stepUserAnswers.value;
        isChecked.value = true;
    } else {
        draggableAnswers.value = [...answers.value];
    }
});

const check = () => {
    const isIdentical = draggableAnswers.value.every((value, index) => value === answers.value[index]);
    console.log('Массивы идентичны (every):', isIdentical);
}

const checkAnswers = () => {
    const isIdentical = draggableAnswers.value.every((value, index) => value === answers.value[index]);
    if (isIdentical) {
        router.post(route('addPoint'), {
            points: 10,
            course_id: props.step.lesson.module.course.id
        }, {
            onSuccess: progress => {
                router.post(route('steps.complete', props.test.step_id), {
                    answers: draggableAnswers.value
                }, {
                    onSuccess: progress => {
                        router.visit(window.location.href)
                        loading.value = false
                    }
                });
            }
        })
    } else {
        router.post(route('steps.complete', props.test.step_id), {
            answers: draggableAnswers.value
        }, {
            onSuccess: progress => {
                router.visit(window.location.href)
                loading.value = false
            }
        });
    }
    isChecked.value = true;
};

</script>

<template>
    <div class="container mx-auto p-4">
        <button @click="debug">DEBUG</button>
        <Loading v-if="loading"/>
        <div class="text-2xl font-medium mb-4">
            {{ test.description }}
        </div>
        <div v-if="isTestCompleted">
            <div v-for="(answer, index) in stepUserAnswers" :key="index" class="mb-2">
                <div>Вопрос: {{ questions[index].question }}</div>
                <div>Ваш ответ: {{ answer.answer }}</div>
                <div v-if="answer.id === answers[index].id" class="text-green-500">
                    Правильно!
                </div>
                <div v-else class="text-red-500">
                    Неправильно!
                </div>
            </div>
        </div>
        <div v-else>
            <div class="flex gap-10 mt-8">
                <div class="w-1/2">
                    <h3 class="text-lg mb-4">Вопросы</h3>
                    <ul>
                        <li v-for="(question, index) in questions" :key="`q-${index}`"
                            class="mb-2 p-2 bg-blue-200 rounded">
                            {{ question.question }}
                        </li>
                    </ul>
                </div>
                <div class="w-1/2">
                    <h3 class="text-lg mb-4">Сопоставьте ответы</h3>
                    <draggable class="answers flex flex-col" v-model="draggableAnswers" group="answers">
                        <template #item="{ element }">
                            <div :key="element.id" class="p-2 mb-2 bg-green-200 rounded cursor-pointer">
                                {{ element.answer }}
                            </div>
                        </template>
                    </draggable>
                </div>
            </div>
            <button @click="checkAnswers" class="mt-4 px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded">
                Проверить ответы
            </button>
        </div>
    </div>
</template>

<style scoped>

</style>
