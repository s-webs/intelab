<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import Loading from "@/Components/Loading.vue";

const props = defineProps({
    module: Object,
    courseUser: Object
})

const loading = ref(false)

const moduleComplete = () => {
    loading.value = true
    router.post(route('updateProgress'), {
        course_id: props.module.course.id,
        course_user_id: props.courseUser.id,
        type: 'module',
        target_id: props.module.id,
        course_complete: false
    }, {
        onProgress: progress => {
            router.post(route('modules.complete', props.module.id))
        },
        onSuccess: progress => {
            router.post(route('modules.complete', props.module.id))
            setTimeout(() => {
                router.visit(route('learningStudentCourse', props.module.course.id))
                loading.value = false
            }, 3000);
        }
    })
}

const allLessonsCompleted = computed(() => {
    return props.module.lessons.every(lesson => lesson.lesson_users.length > 0);
});
</script>

<template>
    <AppLayout>
        <Loading v-if="loading"/>
        <div class="container mx-auto py-8">
            <div class="border-b-2 pb-2 flex items-center justify-between">
                <h1 class="text-2xl text-main-blue font-medium">Уроки модуля "{{ module.name }}"</h1>
            </div>

            <div class="mt-8">
                <div v-for="lesson in module.lessons"
                     class="flex items-center justify-between p-5 border border-blue-400 rounded-md mb-4">
                    <div>{{ lesson.name }}</div>
                    <div>
                        <!-- Проверяем, существует ли lesson_users[0] и есть ли у него completed_at -->
                        <Link v-if="lesson.lesson_users.length > 0 && lesson.lesson_users[0]?.completed_at"
                              :href="route('lessonStudentCourse', [module.course.id, lesson.id])"
                              class="bg-green-500 text-green-800 font-bold px-3 py-2 rounded-md">
                            Урок пройден
                            <i class="fa fa-check ml-1"></i>
                        </Link>

                        <!-- Если запись существует, но нет completed_at -->
                        <Link v-else-if="lesson.lesson_users.length > 0"
                              :href="route('lessonStudentCourse', [module.course.id, lesson.id])"
                              class="bg-blue-500 text-blue-800 font-bold px-3 py-2 rounded-md">
                            Продолжить урок
                        </Link>

                        <!-- Если запись существует, но нет completed_at (раньше было ещё одно условие, но оно дублировало предыдущее) -->
                        <Link v-else
                              :href="route('lessonStudentCourse', [module.course.id, lesson.id])"
                              class="bg-blue-500 text-blue-800 font-bold px-3 py-2 rounded-md">
                            Начать урок
                        </Link>
                    </div>
                </div>
            </div>
            <div class="text-end mt-8">
                <Link :href="route('learningStudentCourse', module.course.id)" v-if="allLessonsCompleted && module.module_users[0]['completed']"
                        class="bg-blue-500 text-blue-800 font-bold px-3 py-2 rounded-md">
                    Модуль завершен
                </Link>
                <button v-else-if="allLessonsCompleted" @click="moduleComplete"
                        class="bg-blue-500 text-blue-800 font-bold px-3 py-2 rounded-md">
                    Завершить модуль
                </button>
                <button v-else class="bg-gray-500 text-grgray-800 font-bold cursor-not-allowed px-3 py-2 rounded-md"
                        disabled>Завершить модуль
                </button>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
