<script setup>
import {ref} from 'vue';
import {useI18n} from "vue-i18n";

const {t, locale} = useI18n()
const emit = defineEmits(['close', 'delete']);

const props = defineProps({
    step: Object
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
    if (question.value.correctAnswerId === answerId) {
        question.value.correctAnswerId = null;
    }
};

const deleteStep = () => {
    emit('delete', props.step)
    close()
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
                        {{ t('pages.testQuiz') }}
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
                <div class="p-4 md:p-5">
                    <p>
                        {{ step.quiz.text }}
                    </p>
                    <div class="p-4 md:p-5">
                        <ul class="list-disc pl-5">
                            <li v-for="(answer, index) in JSON.parse(step.quiz.answers)" :key="answer.id" class="mb-2">
                                <span v-if="step.quiz.correctAnswerId === answer.id">{{ index + 1 }}) {{ answer.text }} <i class="fa fa-check ml-2 text-green-600 text-md"></i></span>
                                <span v-else>{{ index + 1 }}) {{ answer.text }}</span>
                            </li>
                        </ul>
                    </div>
                    <button @click="deleteStep"
                            type="submit"
                            class="text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mt-4">
                        {{ t('pages.delete') }}
                        <i class="fa fa-trash ml-2"></i>
                    </button>
                </div>

            </div>

        </div>
    </div>
</template>
