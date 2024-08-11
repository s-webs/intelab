<script setup>

import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import Loading from "@/Components/Loading.vue";

const props = defineProps({
    test: Object,
    stepUser: Object,
})
const selectedAnswer = ref(null)
const answers = ref(JSON.parse(props.test.answers))
const loading = ref(false)

const submit = () => {
    loading.value = true
    router.post(route('quiz.complete', props.test.step_id), {
        answers: selectedAnswer.value
    }, {
        onSuccess: progress => {
            loading.value = false
        },
    })
}

const getAnswerClass = (answerId) => {
    if (answerId === props.stepUser?.answers && answerId === props.test.correctAnswerId) {
        return 'flex items-center justify-between border bg-green-100 p-5 rounded-md mb-2';
    } else if (answerId === props.stepUser?.answers) {
        return 'flex items-center justify-between border bg-red-100 p-5 rounded-md mb-2';
    } else if (answerId === props.test.correctAnswerId) {
        return 'flex items-center justify-between border bg-green-100 p-5 rounded-md mb-2';
    } else {
        return 'border p-5 rounded-md mb-2';
    }
};
</script>

<template>
    <div class="quiz-container">
        <div>
            <h2 class="text-xl font-bold">{{ test.text }}</h2>
        </div>
        <form v-if="!stepUser" @submit.prevent="submit" class="">
            <div>
                <label :for="answer.id" class="flex p-5 border mt-8 justify-between items-center"
                       v-for="answer in answers">
                    {{ answer.text }}
                    <input v-model="selectedAnswer" :id="answer.id" :value="answer.id" type="radio"
                           name="selected_answer" required>
                </label>
            </div>
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white font-bold rounded hover:bg-blue-600">
                Проверить
            </button>
        </form>
        <div v-else class="mt-8">
            <div v-for="answer in answers" :key="answer.id">
                <div :class="getAnswerClass(answer.id)">
                    <span>{{ answer.text }}</span>
                    <span v-if="answer.id === test.correctAnswerId && answer.id === stepUser?.answers?.user_answer_id"
                          class="font-bold text-green-600">Вы выбрали</span>
                    <span v-else-if="answer.id === test.correctAnswerId" class="font-bold text-green-600">Правильный ответ</span>
                </div>
            </div>
        </div>
        <Loading v-if="loading"/>
    </div>
</template>
