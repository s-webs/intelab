<script setup>
import {ref} from "vue";
import {router} from "@inertiajs/vue3";

const emit = defineEmits(['close', 'delete']);

const props = defineProps({
    test: Object
})

const parsedQuestions = JSON.parse(props.test.matching.question).map(q => q.question);
const parsedAnswers = JSON.parse(props.test.matching.answers).map(a => a.answer);

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
        <div class="relative p-4 w-full max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Вопросы и ответы (Сопоставления)
                    </h3>
                    <button @click="close"
                            type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                            data-modal-toggle="crud-modal">
                        <i class="text-2xl fa fa-xmark"></i>
                        <span class="sr-only">Закрыть окно</span>
                    </button>
                </div>
                <div class="p-4 md:p-5">
                    <div class="mb-8">
                        <p class="border-b mb-3">Описание</p>
                        <p>{{ test.matching.description }}</p>
                    </div>
                    <div v-for="(item, index) in parsedQuestions" :key="`question-${index}`"
                         class="mb-4 flex items-center border rounded-md p-3 border-blue-400">
                        <div class="flex items-center">
                            <span class="ml-2">{{ item }}<i class="fa fa-arrow-right text-sm mx-2"></i></span>
                        </div>
                        <div class="flex items-center">
                            <span class="ml-2">{{ parsedAnswers[index] }}</span>
                        </div>
                    </div>
                    <button @click="deleteStep"
                            type="submit"
                            class="text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mt-4">
                        Удалить
                        <i class="fa fa-trash ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
