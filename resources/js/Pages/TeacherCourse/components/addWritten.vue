<script setup>
import {ref} from 'vue';
import {router} from "@inertiajs/vue3";

const emit = defineEmits(['close', 'add']);

const props = defineProps({
    lesson: Object
})

const written = ref({
    text: '',
    points: 0,
})

const addWritten = () => {
    router.post(route('teacherCourse.lesson.addWritten', props.lesson.id), written.value)
    close()
};

const close = () => {
    emit('close');
};
</script>

<template>
    <div id="crud-modal" tabindex="-1" aria-hidden="true"
         class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] min-h-screen bg-gray-800 bg-opacity-75">
        <div class="relative p-4 w-full max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Письменный экзамен
                    </h3>
                    <button @click="close"
                            type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                            data-modal-toggle="crud-modal">
                        <i class="text-2xl fa fa-xmark"></i>
                        <span class="sr-only">Закрыть окно</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form @submit.prevent="addWritten" class="p-4 md:p-5">
                    <div class="col-span-2">
                        <label for="description"
                               class="block mb-2 text-sm font-medium text-gray-900">
                            Добавить вопрос
                        </label>
                        <textarea v-model="written.text" id="description" rows="4"
                                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500"
                                  placeholder="Введите вопрос..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="question-points" class="text-lg font-semibold">Баллы за вопрос:</label>
                        <input v-model.number="written.points" id="question-points" type="number"
                               placeholder="Введите количество баллов"
                               class="w-full p-2 border rounded mt-1">
                    </div>
                    <button type="submit"
                            class="text-white inline-flex items-center bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mt-4 ml-2">
                        Сохранить
                        <i class="fa fa-save ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
