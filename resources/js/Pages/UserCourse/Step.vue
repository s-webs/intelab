<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import Matching from "@/Pages/UserCourse/components/Matching.vue";
import Quiz from "@/Pages/UserCourse/components/Quiz.vue";
import Written from "@/Pages/UserCourse/components/Written.vue";
import Loading from "@/Components/Loading.vue";

const props = defineProps({
    step: Object,
    steps: Array,
    currentStepIndex: Number,
    stepUser: Object,
    courseUser: Object,
});

const isAnswersChecked = ref(false);
const isLastStep = computed(() => props.currentStepIndex === props.steps.length - 1);
const loading = ref(false);

const completeAndNavigateToStep = async (currentStepId, stepIndex) => {
    try {
        loading.value = true;

        await router.post(route('steps.complete', currentStepId));
        const nextStepId = props.steps[stepIndex].id;
        await router.get(route('stepStudentCourse', [props.step.lesson.module.course.id, nextStepId]));

        loading.value = false;
    } catch (error) {
        console.error("Error completing step:", error);
        loading.value = false;
    }
};

const goToStep = async (stepId) => {
    loading.value = true;
    await router.get(route('stepStudentCourse', [props.step.lesson.module.course.id, stepId]));
    loading.value = false;
};

const goToNextStep = async () => {
    try {
        loading.value = true;

        await router.post(route('updateProgress'), {
            course_id: props.step.lesson.module.course.id,
            course_user_id: props.courseUser.id,
            type: 'step',
            target_id: props.step.id,
            course_complete: false
        });

        if (isLastStep.value) {
            await lessonComplete();
        } else {
            await completeAndNavigateToStep(props.step.id, props.currentStepIndex + 1);
        }
    } catch (error) {
        console.error("Error going to next step:", error);
        loading.value = false;
    }
};

const lessonComplete = async () => {
    try {
        loading.value = true;

        await router.post(route('updateProgress'), {
            course_id: props.step.lesson.module.course.id,
            course_user_id: props.courseUser.id,
            type: 'lesson',
            target_id: props.step.lesson.id,
            course_complete: true
        });

        await router.post(route('lessons.complete', props.step.lesson.id));

        // await router.visit(route('learningStudentLessons', [props.step.lesson.module.course.id, props.step.lesson.module.id]));

        loading.value = false;
    } catch (error) {
        console.error("Ошибка при завершении урока:", error);
        loading.value = false;
    }
};

const handleAnswersChecked = (checked) => {
    isAnswersChecked.value = checked;
};
</script>

<template>
    <AppLayout title="Главная">
        <Loading v-if="loading" />
        {{console.log(isLastStep)}}
        <div class="container mx-auto py-8">
            <div v-if="step.type === 'content'" class="min-h-72">
                <div v-html="step.content.content"></div>
            </div>
            <div v-if="step.type === 'quiz'" class="">
                <Quiz :test="step.quiz" :stepUser="stepUser" />
            </div>
            <div v-if="step.type === 'matching'" class="space-y-6">
                <Matching :test="step.matching" :step="step" :stepUser="stepUser" @checked="handleAnswersChecked" />
            </div>
            <div v-if="step.type === 'written'" class="">
                <Written :test="step.written" :step="step" :stepUser="stepUser" />
            </div>
            <div class="flex items-center justify-between mt-10">
                <div class="flex items-center">
                    <div v-for="(step, index) in steps">
                        <button v-if="index === currentStepIndex" class="bg-green-400 mx-1 w-12" disabled>
                            <span v-if="step.completed"><i class="fa fa-check"></i></span>
                            <span v-else>{{ index + 1 }}</span>
                        </button>
                        <button @click="goToStep(step.id)" v-else class="bg-gray-400 mx-1 w-12">
                            <span v-if="step.completed"><i class="fa fa-check"></i></span>
                            <span v-else>{{ index + 1 }}</span>
                        </button>
                    </div>
                </div>
                <div v-if="!isLastStep">
                    <button v-if="step.type === 'content'" @click="goToNextStep"
                            class="bg-green-500 text-green-800 font-bold px-3 py-1 rounded-md">
                        Продолжить
                    </button>
                    <div v-else @click="goToNextStep">
                        <button v-if="stepUser"
                                class="bg-green-500 text-green-800 font-bold px-3 py-1 rounded-md">
                            Продолжить
                        </button>
                        <button v-else disabled
                                class="bg-gray-500 text-gray-800 font-bold px-3 py-1 rounded-md cursor-not-allowed">
                            Отправьте ваши ответы
                        </button>
                    </div>
                </div>
                <div v-else>
                    <div @click="goToNextStep">
                        <button v-if="stepUser" @click="lessonComplete"
                                class="bg-blue-500 text-blue-800 font-bold px-3 py-1 border-2 rounded-md border-amber-700">
                            Завершить урок
                        </button>
                        <button v-else disabled
                                class="bg-gray-500 text-gray-800 font-bold px-3 py-1 rounded-md cursor-not-allowed">
                            Отправьте ваши ответы
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
