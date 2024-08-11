<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {ref} from "vue";
import {router, Link} from "@inertiajs/vue3";

const props = defineProps({
    courses: Array
})

const confirmationVisible = ref(false);
const selectedCourse = ref(null)

const showConfirmation = (selected) => {
    confirmationVisible.value = true;
    selectedCourse.value = selected
};

const confirmAction = () => {
    router.delete(route('userCourse.delete', selectedCourse.value.id))

    confirmationVisible.value = false;
    selectedCourse.value = null
};

const cancelAction = () => {
    confirmationVisible.value = false;
    selectedCourse.value = null
};


</script>

<template>
    <AppLayout title="Создать бесплатный курс">
        <div class="container mx-auto flex mt-8">
            <div v-if="confirmationVisible" id="crud-modal" tabindex="-1" aria-hidden="true"
                 class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] min-h-screen bg-gray-800 bg-opacity-75">
                <div class="relative p-4 w-full max-w-xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Завершение обучения
                            </h3>
                            <button @click="cancelAction"
                                    type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                    data-modal-toggle="crud-modal">
                                <i class="text-2xl fa fa-xmark"></i>
                                <span class="sr-only">Закрыть окно</span>
                            </button>
                        </div>
                        <div class="p-4 md:p-5">
                            <div class="">
                                <span class="text-lg font-bold">
                                    Вы уверены, что хотите завершить обучение курса
                                    <span class="underline">{{ selectedCourse.course.name }}</span>?<br><br>
                                    Весь прогресс будет сохранен!
                                </span>
                            </div>
                            <div class="mt-8 text-end">
                                <button @click="confirmAction" type="submit"
                                        class="text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-4">
                                    Да, завершить
                                    <i class="fa fa-trash ml-2"></i>
                                </button>
                                <button @click="cancelAction" type="submit"
                                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                    Отменить
                                    <i class="fa fa-cancel ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg p-3">
                <div class="border-b-2 pb-2 flex items-center justify-between">
                    <h1 class="text-2xl text-main-blue font-medium">Мои курсы</h1>
                </div>
                <div v-if="courses.length > 0">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 mt-5">
                        <thead class="text-xs text-white uppercase bg-main-blue">
                        <tr>
                            <th scope="col" class="px-6 py-3 border-r rounded-tl-md text-center">
                                ID курса
                            </th>
                            <th scope="col" class="px-6 py-3 border-r text-center">
                                Изображение
                            </th>
                            <th scope="col" class="px-6 py-3 border-r">
                                Название курса
                            </th>
                            <th scope="col" class="px-6 py-3 border-r text-end">
                                Действия
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="course in courses" class="bg-white border-b hover:bg-gray-300">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 text-center whitespace-nowrap border-r">
                                <span>{{ course.course.id }}</span>
                            </th>
                            <td class="px-6 py-4 border-r text-center">
                                <img class="w-14 mx-auto object-cover" :src="course.course.image">
                            </td>
                            <td class="px-6 py-4 border-r">
                                <span>{{ course.course.name }}</span>
                            </td>
                            <td class="px-6 py-4 border-r text-end">
                                <div class="flex items-center justify-end">
                                    <div v-if="course.completed_at">
                                        <Link :href="route('learningStudentCourse', course.course.id)"
                                              class="font-medium text-sm text-green-600 py-1 px-3 rounded-md border border-green-600 hover:bg-green-600 hover:text-white mr-4">
                                            Курс завершен
                                        </Link>
                                        {{ console.log() }}
                                        <button @click="showConfirmation(course)"
                                                class="font-medium text-sm text-red-600 py-1 px-3 rounded-md border border-red-600 hover:bg-red-600 hover:text-white">
                                            Завершить обучение
                                        </button>
                                    </div>
                                    <div v-else>
                                        <div v-if="course.user_progress[0]">
                                            <Link v-if="course.user_progress[0].type === 'module'"
                                                  :href="route('learningStudentLessons', [course.course.id, course.user_progress[0].target_id])"
                                                  class="font-medium text-sm text-green-600 py-1 px-3 rounded-md border border-green-600 hover:bg-green-600 hover:text-white mr-4">
                                                Продолжить обучение
                                            </Link>
                                            <Link v-else-if="course.user_progress[0].type === 'lesson'"
                                                  :href="route('lessonStudentCourse', [course.course.id, course.user_progress[0].target_id])"
                                                  class="font-medium text-sm text-green-600 py-1 px-3 rounded-md border border-green-600 hover:bg-green-600 hover:text-white mr-4">
                                                Продолжить обучение
                                            </Link>
                                            <Link v-else-if="course.user_progress[0].type === 'step'"
                                                  :href="route('stepStudentCourse', [course.course.id, course.user_progress[0].target_id])"
                                                  class="font-medium text-sm text-green-600 py-1 px-3 rounded-md border border-green-600 hover:bg-green-600 hover:text-white mr-4">
                                                Продолжить обучение
                                            </Link>
                                        </div>
                                        <div v-else>
                                            <Link :href="route('learningStudentCourse', course.course.id)"
                                                  class="font-medium text-sm text-green-600 py-1 px-3 rounded-md border border-green-600 hover:bg-green-600 hover:text-white mr-4">
                                                Продолжить обучение
                                            </Link>
                                        </div>
                                        <button @click="showConfirmation(course)"
                                                class="font-medium text-sm text-red-600 py-1 px-3 rounded-md border border-red-600 hover:bg-red-600 hover:text-white">
                                            Завершить обучение
                                        </button>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="text-center text-xl border border-blue-400 p-5 mt-8 rounded-md">
                    <p>Не упустите возможность стать лучше каждый день. Давайте вместе сделаем шаг к вашему успеху!</p>
                    <p class="mt-8">
                        <Link href="/" class="text-blue-600">🚀 Начните свое обучение прямо сейчас! 🚀</Link>
                    </p>
                </div>
            </div>


        </div>
    </AppLayout>
</template>

<style scoped>

</style>
