<script setup>
import {ref} from 'vue';
import {router} from "@inertiajs/vue3";
import {useI18n} from "vue-i18n";

const {t, locale} = useI18n()
const emit = defineEmits(['close', 'add', 'delete']);

const props = defineProps({
    lesson: Object
})

const question = ref({
    text: '',
    answers: [{id: Date.now(), text: ''}],
    correctAnswerId: null,
    points: 0
});

const addAnswer = () => {
    question.value.answers.push({id: Date.now(), text: ''});
};

const removeAnswer = (answerId) => {
    question.value.answers = question.value.answers.filter(answer => answer.id !== answerId);
    // Если удаляется правильный ответ, сбросьте выбор
    if (question.value.correctAnswerId === answerId) {
        question.value.correctAnswerId = null;
    }
};
const addQuiz = () => {
    // Проверяем, что текст вопроса введен и выбран правильный ответ
    if (!question.value.text.trim()) {
        alert(t('pages.warnTextQuest'));
        return;
    }

    if (question.value.correctAnswerId === null) {
        alert(t('pages.warnSelectRightAnswer'));
        return;
    }

    // Проверяем, что для всех ответов введен текст
    const hasEmptyAnswers = question.value.answers.some(answer => !answer.text.trim());
    if (hasEmptyAnswers) {
        alert(t('pages.warnTextAnswer'));
        return;
    }

    router.post(route('teacherCourse.lesson.addQuiz', props.lesson.id), question.value)
    close()
};

const deleteStep = () => {
    emit('delete', props.test)
}

const close = () => {
    emit('close');
};
</script>

<template>
    <div id="crud-modal" tabindex="-1" aria-hidden="true"
         class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] min-h-screen bg-gray-800 bg-opacity-75">
        <div class="relative p-4 w-full xl:w-1/2  max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ t('pages.addQuiz') }}
                    </h3>
                    <button @click="close"
                            type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                            data-modal-toggle="crud-modal">
                        <i class="text-2xl fa fa-xmark"></i>
                        <span class="sr-only">{{ t('pages.closeWindow') }}</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form @submit.prevent="addQuiz" class="p-4 md:p-5">
                    <div class="mb-4">
                        <label for="question-text" class="text-lg font-semibold">{{ t('pages.quest') }}:</label>
                        <input v-model="question.text" id="question-text" :placeholder="t('pages.enterQuest')"
                               type="text"
                               class="w-full p-2 border rounded mt-1">
                    </div>
                    <div class="border-b mb-4">
                        <span class="text-lg font-semibold">{{ t('pages.answers') }}</span>
                    </div>
                    <div v-for="(answer, index) in question.answers" :key="answer.id" class="flex items-center mb-2">
                        <input type="text" v-model="answer.text" :placeholder="t('pages.enterAnswer')"
                               class="w-full p-2 border rounded mr-2">
                        <input type="radio" :name="`correct-answer-${question.id}`" :value="answer.id"
                               v-model="question.correctAnswerId"
                               class="p-2">
                        <button @click="removeAnswer(answer.id)"
                                class="ml-2 p-2 text-red-500 border border-red-500 rounded hover:bg-red-500 hover:text-white">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>

                    <div class="mb-4">
                        <label for="question-points" class="text-lg font-semibold">{{ t('pages.questPoints') }}:</label>
                        <input v-model.number="question.points" id="question-points" type="number"
                               placeholder="Введите количество баллов"
                               class="w-full p-2 border rounded mt-1">
                    </div>
                    <button type="button" @click="addAnswer"
                            class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mt-4">
                        {{ t('pages.addAnswer') }} <i class="fa fa-plus ml-2"></i>
                    </button>
                    <button type="submit"
                            class="text-white inline-flex items-center bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mt-4 ml-2">
                        {{ t('pages.save') }}
                        <i class="fa fa-save ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
