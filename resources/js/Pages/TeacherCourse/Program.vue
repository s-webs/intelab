`
<script setup>
import {ref} from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import AddModule from "@/Pages/TeacherCourse/components/addModule.vue";
import EditModule from "@/Pages/TeacherCourse/components/editModule.vue";
import {router, Link} from "@inertiajs/vue3";
import AddLesson from "@/Pages/TeacherCourse/components/addLesson.vue";
import EditLesson from "@/Pages/TeacherCourse/components/editLesson.vue";
import {useI18n} from "vue-i18n";

const {t, locale} = useI18n()

const props = defineProps({
    course: Object,
})

// Для отслеживания состояния модальных окон
const showAddModuleModal = ref(false);
const showAddLessonModal = ref(false);
const showEditModuleModal = ref(false);
const showEditLessonModal = ref(false);
const editingModule = ref(null)
const editingLesson = ref(null)
const currentModuleId = ref(null)

// Функции для управления модальными окнами
const openAddModuleModal = () => showAddModuleModal.value = true;
const openAddLessonModal = (moduleId) => {
    currentModuleId.value = moduleId
    showAddLessonModal.value = true
};
const closeAddModuleModal = () => showAddModuleModal.value = false;
const closeAddLessonModal = () => showAddLessonModal.value = false;

const openEditModuleModal = (module) => {
    editingModule.value = module;
    showEditModuleModal.value = true;
};

const openEditLessonModal = (lesson) => {
    editingLesson.value = lesson;
    showEditLessonModal.value = true;
};

const closeEditModuleModal = () => {
    editingModule.value = null;
    showEditModuleModal.value = false;
};

const closeEditLessonModal = () => {
    editingLesson.value = null;
    showEditLessonModal.value = false;
};

const addNewModule = (module) => {
    const moduleData = {
        'name': module.name,
        'description': module.description,
        'course_id': props.course.id
    }
    router.post(route('teacherCourse.module.store', props.course.id), moduleData)

};

const editModule = (event, moduleData) => {
    router.put(route('teacherCourse.module.update', moduleData.id), moduleData)
};

const deleteModule = (moduleId) => {
    if (confirm(t('pages.confirmDeleteModule'))) {
        router.delete(route('teacherCourse.module.destroy', moduleId))
    }
};

const addNewLesson = (lesson) => {
    const data = {
        'name': lesson.name,
        'description': lesson.description,
        'module_id': currentModuleId.value
    }
    router.post(route('teacherCourse.lesson.store', props.course.id), data)

};

const editLesson = (data) => {
    router.put(route('teacherCourse.lesson.update', data.id), data)
};

const deleteLesson = (lessonId) => {
    if (confirm(t('pages.confirmDeleteLesson'))) {
        router.delete(route('teacherCourse.lesson.delete', lessonId))
    }
};
</script>

<template>
    <AppLayout title="Создать бесплатный курс">
        <div class="container mx-auto mt-8">
            <div class="flex justify-between items-center border-b-2 border-main-blue pb-2">
                <h1 class="text-2xl font-medium text-main-blue">
                    {{ t('pages.courseProgram') }} - <span class="text-blue-500">{{ props.course.name }}</span>
                </h1>
                <button @click="openAddModuleModal"
                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-sm text-sm px-5 py-2.5 me-2 mb-2 flex justify-end">
                    {{ t('pages.addModule') }}
                </button>
            </div>
            <div v-if="props.course.modules.length > 0" class="mt-5">
                <div v-for="(module, index) in props.course.modules">
                    <div class="border border-gray-400 rounded-sm p-4 flex justify-between items-center">
                        <div><span class="mr-2">{{ index + 1 }}.</span><span>{{ module.name }}</span></div>
                        <div class="flex">
                            <button @click="openEditModuleModal(module)"
                                    class="font-medium text-main-blue text-sm p-1 rounded-md border border-main-blue flex items-center mr-2">
                                <span class="mr-2">{{ t('pages.edit') }}</span><i class="fa fa-pen"></i>
                            </button>
                            <button @click="deleteModule(module.id)"
                                    class="font-medium text-sm text-red-600 p-1 rounded-md border border-red-600 flex items-center">
                                <span class="mr-2">{{ t('pages.delete') }}</span><i class="fa fa-xmark"></i></button>
                        </div>
                    </div>
                    <div v-if="module.lessons.length > 0">
                        <div v-for="(lesson, index) in module.lessons" class="rounded-sm mt-1 flex justify-end">
                            <div class="border border-blue-400 rounded-sm p-4 flex justify-between items-center w-3/4">
                                <div>
                                    <span>{{ index + 1 }}. {{ lesson.name }}</span>
                                </div>
                                <div class="flex">
                                    <Link :href="route('teacherCourse.lesson.program', lesson.id)"
                                          class="font-medium text-main-blue text-sm p-1 rounded-md border border-main-blue flex items-center mr-2">
                                        <span class="mr-2">{{ t('pages.lessonProgram') }}</span> <i
                                        class="fa fa-layer-group"></i>
                                    </Link>
                                    <button @click="openEditLessonModal(lesson)"
                                            class="font-medium text-main-blue text-sm p-1 rounded-md border border-main-blue flex items-center mr-2">
                                        <span class="mr-2">{{ t('pages.edit') }}</span><i class="fa fa-pen"></i>
                                    </button>
                                    <button @click="deleteLesson(lesson.id)"
                                            class="font-medium text-sm text-red-600 p-1 rounded-md border border-red-600 flex items-center">
                                        <span class="mr-2">{{ t('pages.delete') }}</span><i class="fa fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end" v-else>
                        <span class="inline-block text-lg font-medium text-gray-400 py-2">{{
                                t('pages.lessonNotFound')
                            }}</span>
                    </div>
                    <div class="flex mb-7 justify-end">
                        <button @click="openAddLessonModal(module.id)"
                                class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-sm text-sm px-5 py-2.5 me-2 my-2 flex justify-end">
                            {{ t('pages.addLesson') }}
                        </button>
                    </div>
                </div>
            </div>

            <div v-else>
                <div class="mt-10 flex items-center justify-center rounded-lg w-3/4 mx-auto">
                    <div class="text-center">
                        <div class="border-b-2 border-main-blue py-6"><span
                            class="text-2xl">{{ t('pages.notFoundModules') }}</span>
                        </div>
                    </div>
                </div>
                <div class="my-6 flex justify-center items-center">
                    <button @click="openAddModuleModal"
                            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 flex items-center">
                        {{ t('pages.addModule') }}
                    </button>
                </div>
            </div>
            <AddModule v-if="showAddModuleModal"
                       @close="closeAddModuleModal"
                       @add="addNewModule"/>
            <EditModule
                v-if="showEditModuleModal"
                :module="editingModule"
                @update="editModule"
                @close="closeEditModuleModal"
            />
            <AddLesson
                v-if="showAddLessonModal"
                :moduleId="currentModuleId"
                @close="closeAddLessonModal"
                @add="addNewLesson"
            />
            <EditLesson
                v-if="showEditLessonModal"
                :lesson="editingLesson"
                @close="closeEditLessonModal"
                @update="editLesson"
            />
        </div>
    </AppLayout>
</template>
`
