<script setup>
import {computed, ref} from "vue";
import {router} from "@inertiajs/vue3";

const emit = defineEmits(['close', 'add']);
const nextId = ref(1)

const props = defineProps({
    lesson: Object
})

const items = ref([]);
const description = ref('')

const addItem = () => {
    items.value.push({
        id: nextId.value,
        question: '',
        answer: ''
    });
    nextId.value++;
};

const removeItem = (index) => {
    items.value.splice(index, 1);
};

const canSubmit = computed(() => {
    return items.value.length >= 2; // минимум две пары вопрос-ответ
});

const submitTest = () => {
    if (!canSubmit.value) {
        alert('Добавьте минимум две пары вопрос-ответ');
        return;
    }
    router.post(route('teacherCourse.lesson.addTest', props.lesson.id), {
        items: items.value,
        description: description.value
    })
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
                        Добавить тест (сопоставление)
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
                <form @submit.prevent="submitTest" class="p-4 md:p-5">
                    <div class="col-span-2">
                        <label for="description"
                               class="block mb-2 text-sm font-medium text-gray-900">
                            Описание теста
                        </label>
                        <textarea v-model="description" id="description" rows="4"
                                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 mb-4"
                                  placeholder="Введите описание теста..." required></textarea>
                    </div>
                    <div class="font-bold my-2">Добавьте минимум 2 сопоставления</div>
                    <div v-for="(item, index) in items" :key="item.id" class="mb-4 flex items-center">
                        <input v-model="item.question" type="text" placeholder="Вопрос"
                               class="rounded w-full py-2 px-3 mr-2" required>
                        <input v-model="item.answer" type="text" placeholder="Ответ" class="rounded w-full py-2 px-3" required>
                        <button @click="removeItem(index)" class="ml-2 px-4 py-2 bg-red-500 text-white rounded"><i
                            class="fa fa-trash"></i></button>
                    </div>
                    <button type="button" @click="addItem"
                            class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mt-4">
                        Добавить пару вопрос-ответ<i class="fa fa-plus ml-2"></i>
                    </button>
                    <button type="submit"
                            class="ml-2 text-white inline-flex items-center bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mt-4">
                        Создать
                        <i class="fa fa-plus ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
