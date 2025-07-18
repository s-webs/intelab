<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";

const props = defineProps({
    courses: Array
});

const deleteCourse = (id) => {
    if (confirm("Вы уверены, что хотите удалить курс?")) {
        router.delete(route('admin.course.destroy', id), {
            preserveState: true,
            onSuccess: () => {
                // можно всплывающее сообщение или обновление страницы
            }
        });
    }
};
</script>

<template>
    <AppLayout>
        <div class="container mx-auto p-4 mt-4">
            <div class="flex flex-wrap justify-between">
                <div v-for="course in courses"
                     :key="course.id"
                     class="w-full m-4 flex items-center border border-main-primary p-4">
                    <div class="w-[100px] lg:w-[150px] h-[100px] lg:h-[150px] shrink-0 mr-2">
                        <img :src="course.image" alt="" class="w-full h-full object-cover" />
                    </div>
                    <div class="w-full">
                        <div class="text-main-primary font-semibold">
                            <Link :href="route('showCourse', course.id)">
                                {{ course.name }}
                            </Link>
                        </div>
                        <div class="text-main-primary text-sm mt-1">
                            <span>
                                Автор: {{ course.user.name }}
                            </span>
                        </div>
                        <div class="mt-4 w-full">
                            <Link :href="route('admin.courseShow', course.id)"
                                  class="block w-full text-start p-1 border border-main-primary hover:bg-main-primary transition-all duration-300 text-main-primary hover:text-white">
                                Структура курса
                            </Link>
                        </div>
                        <div>
                            <Link :href="route('teacherCourse.statistic', course.id)"
                                  class="mt-2 block w-full text-start p-1 border border-main-primary hover:bg-main-primary transition-all duration-300 text-main-primary hover:text-white">
                                Статистика
                            </Link>
                        </div>
                        <div>
                            <button
                                @click="deleteCourse(course.id)"
                                class="mt-2 block w-full text-start p-1 border border-red-500 hover:bg-red-500 transition-all duration-300 text-red-600 hover:text-white"
                            >
                                Удалить курс
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
