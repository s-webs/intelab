<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import {router, Link} from "@inertiajs/vue3";
import {onMounted, ref} from "vue";
import Loading from "@/Components/Loading.vue";

const props = defineProps({
    course: Object,
    courseUser: Object
})

const modulesComplete = ref(false)
const loading = ref(false)


const start = (module) => {
    if (module.lessons && module.lessons.length > 0) {
        const firstLessonId = module.lessons[0].id;
        router.get(route('lessonStudentCourse', [props.course.id, firstLessonId]));
    } else {
        alert('В этом модуле нет уроков.');
    }
}

const isPreviousModuleCompleted = (moduleIndex) => {
    if (moduleIndex === 0) {
        return true;
    }

    const previousModule = props.course.modules[moduleIndex - 1];
    return previousModule.module_users.length > 0;
}

const areAllModulesCompleted = () => {
    for (const module of props.course.modules) {
        if (module.module_users.length === 0) {
            return false;
        }
    }
    return modulesComplete.value = true;
}

const courseComplete = () => {
    loading.value = true
    router.post(route('courseComplete', props.course.id), {}, {
        onSuccess: progress => {
            router.visit(route('userCourses'))
        }
    })
}

onMounted(() => {
    areAllModulesCompleted()
})


</script>

<template>
    <AppLayout title="Главная">
        <Loading v-if="loading"/>
        <div class="container mx-auto mb-8">
            <div class="mt-8 border-b-2 border-main-blue pb-2">
                <span class="text-2xl text-main-blue">
                    Программа курса: {{ course.name }}
                </span>
            </div>
            <div class="mt-8">
                <div v-for="(module, index) in course.modules" class="mt-6">
                    <div>
                        <div class="border-b-2 border-gray-400 pb-1 flex items-center justify-between">
                            <h3 class="text-xl">
                                {{ index + 1 }}. Модуль: {{ module.name }}
                            </h3>
                            <Link v-if="isPreviousModuleCompleted(index)"
                                  :href="route('learningStudentLessons', [course.id, module.id])"
                                  class="bg-green-500 py-1 px-5 rounded-md text-green-800 font-bold">
                                <span
                                    v-if="module.module_users && module.module_users[0] && module.module_users[0]['completed']">
                                    Завершен <i class="fa fa-check ml-1"></i>
                                </span>
                                <span v-else>
                                    Открыть
                                </span>
                            </Link>
                            <button v-else
                                    class="bg-gray-500 py-1 px-5 rounded-md text-gray-800 font-bold cursor-not-allowed"
                                    disabled>
                                <span>Завершите предыдущий модуль</span><i class="fa fa-lock ml-2"></i>
                            </button>
                        </div>
                        <p class="mt-2"><strong>Описание модуля:</strong> {{ module.description }}</p>
                    </div>
                    <div class="">
                        <strong>Уроки модуля:</strong>
                        <div v-for="(lesson, index) in module.lessons">
                            {{ index + 1 }}. {{ lesson.name }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end mt-8">
                <button v-if="modulesComplete && !courseUser.completed_at" @click="courseComplete"
                        class="bg-blue-500 text-blue-800 font-bold px-5 py-2 rounded-md">
                    Завершить курс
                </button>
                <button v-else-if="courseUser.completed_at"
                        class="bg-blue-500 text-blue-800 font-bold px-5 py-2 rounded-md cursor-not-allowed" disabled>
                    Курс завершен
                </button>
                <button v-else class="bg-gray-500 text-gray-800 font-bold px-5 py-2 rounded-md cursor-not-allowed"
                        disabled>
                    Завершите все модули курса
                </button>
            </div>
        </div>
    </AppLayout>
</template>
