<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import {router} from "@inertiajs/vue3";
import {ref} from "vue";
import AddStepContent from "@/Pages/TeacherCourse/components/addStepContent.vue";
import EditStepContent from "@/Pages/TeacherCourse/components/editStepContent.vue";
import AddMatching from "@/Pages/TeacherCourse/components/addMatching.vue";
import ShowMatching from "@/Pages/TeacherCourse/components/showMatching.vue";
import AddQuiz from "@/Pages/TeacherCourse/components/addQuiz.vue";
import ShowQuiz from "@/Pages/TeacherCourse/components/showQuiz.vue";
import AddWritten from "@/Pages/TeacherCourse/components/addWritten.vue";
import ShowWritten from "@/Pages/TeacherCourse/components/showWritten.vue";
import {useI18n} from "vue-i18n";

const {t, locale} = useI18n()
const isOpenStepMenu = ref(false)
const showAddStepContent = ref(false)
const showEditStepContent = ref(false)
const showAddMatching = ref(false)
const showMatching = ref(false)
const editingStep = ref(null)
const currentMatching = ref(null)
const showAddQuiz = ref(null)
const showQuiz = ref(false)
const currentShowQuiz = ref(null)
const showAddWritten = ref(false)
const showWritten = ref(false)
const currentWritten = ref(null)


const props = defineProps({
    lesson: Object
})

const breadcrumbs = [
    {label: 'menu.home', url: route('home'), icon: 'fa fa-home'},
    {label: 'pages.myCourses', url: route('teacherCourse.index'), icon: 'fa-solid fa-graduation-cap'},
    {
        label: 'pages.courseProgram',
        url: route('teacherCourse.program', props.lesson.module.course.id),
        icon: 'fa-solid fa-lines-leaning'
    },
    {
        label: 'pages.lessonProgram',
        url: route('teacherCourse.lesson.program', props.lesson.id),
        icon: 'fa-solid fa-lines-leaning'
    },
]

const openStepMenu = () => {
    isOpenStepMenu.value = true;
}

const openAddStepContent = () => {
    showAddStepContent.value = true
    isOpenStepMenu.value = false
}

const openEditStepContent = (step) => {
    showEditStepContent.value = true
    editingStep.value = step
}

const closeStepMenu = () => {
    isOpenStepMenu.value = false;
}

const closeEditStepContent = () => {
    showEditStepContent.value = false
    editingStep.value = null
}

const closeAddStepContent = () => {
    showAddStepContent.value = false
    editingStep.value = null
}

const addContent = (data) => {
    const send = {
        lessonId: props.lesson.id,
        stepType: 'content',
        content: data,
    }
    closeAddStepContent()
    router.post(route('teacherCourse.lesson.addStep', props.lesson.id), send)
    editingStep.value = null
}

const deleteStep = (stepId) => {
    router.delete(route('teacherCourse.lesson.deleteStep', stepId))
    editingStep.value = null
}

const updateContent = (data) => {
    editingStep.value.content.content = data
    router.put(route('teacherCourse.lesson.updateStep', editingStep.value.id), editingStep.value.content)
    editingStep.value = null
}

const openAddMatching = () => {
    showAddMatching.value = true
}

const openShowMatching = (step) => {
    showMatching.value = true
    currentMatching.value = step
}

const openQuiz = () => {
    showAddQuiz.value = true
}

const openShowQuiz = (step) => {
    showQuiz.value = true
    currentShowQuiz.value = step
}

const deleteMatching = (step) => {
    router.delete(route('teacherCourse.lesson.deleteStep', step.id))
    closeShowMatching()
}

const closeAddMatching = () => {
    showAddMatching.value = false
}
const closeShowMatching = () => {
    showMatching.value = false
    currentMatching.value = null
}

const closeAddQuiz = () => {
    showAddQuiz.value = false
}
const closeShowQuiz = () => {
    showQuiz.value = false
}
const deleteQuiz = (step) => {
    router.delete(route('teacherCourse.lesson.deleteStep', step.id))
}

const openAddWritten = () => {
    showAddWritten.value = true
}

const closeAddWritten = () => {
    showAddWritten.value = false
}

const closeShowWritten = () => {
    showWritten.value = false
}

const openShowWritten = (step) => {
    currentWritten.value = step
    showWritten.value = true
}

const deleteWritten = (step) => {
    router.delete(route('teacherCourse.lesson.deleteStep', step.id))
}
</script>

<template>
    <AppLayout>
        <div class="container mx-auto my-8">
            <Breadcrumbs :breadcrumbs="breadcrumbs"/>
            <div class="flex justify-between items-center border-b-2 border-main-blue pb-2 mt-8">
                <h1 class="text-2xl font-medium text-main-blue">
                    {{ t('pages.lessonProgram') }} - <span class="text-blue-500">{{ lesson.name }}</span>
                </h1>
            </div>
            <div>
                <div class="mt-4 border px-3 py-2 rounded-md">
                    <div class="text-xl mb-2 text-gray-400">
                        {{ t('pages.lessonDescription') }}
                    </div>
                    <span class="text-lg">{{ lesson.description }}</span>
                </div>
            </div>
            <div class="border-b-2 border-blue-400 mt-8 py-2">
                <span class="text-2xl">{{ t('pages.lessonSteps') }}</span>
            </div>
            <div class="flex items-center flex-wrap justify-start gap-3 mt-8">
                <div v-for="(step, index) in lesson.steps">
                    <button v-if="step.type === 'content'" @click="openEditStepContent(step)"
                            class="bg-blue-700 hover:bg-blue-800 w-20 h-20 rounded-full flex items-center justify-center">
                        <span class="text-white text-xl">{{ index + 1 }}</span>
                    </button>
                    <button v-if="step.type === 'matching'" @click="openShowMatching(step)"
                            class="bg-blue-700 hover:bg-blue-800 w-20 h-20 rounded-full flex items-center justify-center">
                        <span class="text-white text-xl"><i class="fa fa-question"></i></span>
                    </button>
                    <button v-if="step.type === 'quiz'" @click="openShowQuiz(step)"
                            class="bg-blue-700 hover:bg-blue-800 w-20 h-20 rounded-full flex items-center justify-center">
                        <span class="text-white text-xl"><i class="fa fa-question"></i></span>
                    </button>
                    <button v-if="step.type === 'written'" @click="openShowWritten(step)"
                            class="bg-blue-700 hover:bg-blue-800 w-20 h-20 rounded-full flex items-center justify-center">
                        <span class="text-white text-xl"><i class="fa fa-question"></i></span>
                    </button>
                </div>
                <div class="relative">
                    <button @click="openStepMenu"
                            class="bg-green-600 hover:bg-green-700 w-20 h-20 rounded-full flex items-center justify-center text-white">
                        <i class="fa fa-plus"></i>
                    </button>
                    <div v-if="isOpenStepMenu"
                         class="flex flex-col absolute border bg-blue-100 w-48 top-10 -right-52 rounded-lg overflow-hidden p-2">
                        <button @click="openAddStepContent" class="bg-green-400 hover:bg-green-600 py-1 rounded-sm">
                            {{ t('pages.addContent') }}
                        </button>
                        <span class="my-2 border-b-2 border-blue-400 font-medium rounded-sm">{{
                                t('pages.tests')
                            }}</span>
                        <button @click="openQuiz"
                                class="bg-blue-300 hover:bg-blue-500 mb-1 py-1 rounded-sm">{{ t('pages.testQuiz') }}
                        </button>
                        <button
                            @click="openAddMatching"
                            class="bg-blue-300 hover:bg-blue-500 mb-1 py-1 rounded-sm">{{ t('pages.testMatching') }}
                        </button>
                        <button
                            @click="openAddWritten"
                            class="bg-blue-300 hover:bg-blue-500 py-1 rounded-sm">{{ t('pages.testWritten') }}
                        </button>
                        <button @click="closeStepMenu"
                                class="bg-red-500 py-1 mt-4 text-white hover:bg-red-700 rounded-sm"><i
                            class="fa fa-xmark"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <AddStepContent
            v-if="showAddStepContent"
            @close="closeAddStepContent"
            @add="addContent"
        />
        <EditStepContent
            :editingContent="editingStep"
            v-if="showEditStepContent"
            @close="closeEditStepContent"
            @update="updateContent"
            @delete="deleteStep"
        />
        <AddMatching
            :lesson="lesson"
            v-if="showAddMatching"
            @close="closeAddMatching"
        />
        <ShowMatching
            :test="currentMatching"
            v-if="showMatching"
            @close="closeShowMatching"
            @delete="deleteMatching"
        />
        <AddQuiz
            :lesson="lesson"
            v-if="showAddQuiz"
            @close="closeAddQuiz"
        />
        <ShowQuiz
            :step="currentShowQuiz"
            v-if="showQuiz"
            @close="closeShowQuiz"
            @delete="deleteQuiz"
        />
        <AddWritten
            v-if="showAddWritten"
            :lesson="lesson"
            @close="closeAddWritten"
        />
        <ShowWritten
            :step="currentWritten"
            v-if="showWritten"
            @close="closeShowWritten"
            @delete="deleteWritten"
        />
    </AppLayout>
</template>
