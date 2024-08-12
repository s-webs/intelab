<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {router} from "@inertiajs/vue3";
import {ref} from "vue";
import Loading from "@/Components/Loading.vue";

const props = defineProps({
    lesson: Object,
    courseUser: Object
})
const uncompletedStep = props.lesson.steps.find(step => !step.completed);
const loading = ref(false)
const continueLesson = () => {
    if (uncompletedStep) {
        router.get(route('stepStudentCourse', [props.lesson.module.course.id, uncompletedStep.id]));
    } else {
        loading.value = true
        router.post(route('updateProgress'), {
            course_id: props.lesson.module.course.id,
            course_user_id: props.courseUser.id,
            type: 'lesson',
            target_id: props.lesson.id,
            course_complete: false
        }, {
            onProgress: progress => {
                router.post(route('lessons.complete', props.lesson.id))
            },
            onSuccess: progress => {
                router.post(route('lessons.complete', props.lesson.id))
                setTimeout(() => {
                    router.visit(route('learningStudentLessons', [props.lesson.module.course.id, props.lesson.module.id]))
                    loading.value = false
                }, 3000);
            }
        })
    }
}
</script>

<template>
    <AppLayout title="Главная">
        <Loading v-if="loading"/>
        <div class="container mx-auto px-2">
            <div class="mt-8 border-b-2 border-main-blue pb-2">
                <span class="text-2xl text-main-blue">
                    {{ lesson.name }}
                </span>
            </div>
            <div class="mt-4">
                <p>{{ lesson.description }}</p>
            </div>
            <div class="mt-8 flex items-center justify-between">
                <div><strong>Количество шагов урока:</strong><span class="ml-2">{{ lesson.steps.length }}</span></div>
                <button v-if="uncompletedStep" @click="continueLesson"
                        class="bg-green-500 text-green-800 font-bold px-3 py-1 rounded-md">
                    Продолжить
                </button>
                <button v-else @click="continueLesson"
                        class="bg-green-500 text-green-800 font-bold px-3 py-1 rounded-md">
                    Завершить урок
                </button>
            </div>
        </div>
    </AppLayout>
</template>
