<script setup>
import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import CKeditor from "@/Components/CKeditor.vue";
import Loading from "@/Components/Loading.vue";

const props = defineProps({
    test: Object,
    stepUser: Object,
})

const userAnswer = ref(null)
const loading = ref(false)
const disabled = ref(true)
const debug = () => {
    console.log(props.stepUser)
}

const submit = () => {
    loading.value = true
    router.post(route('written.complete', props.test.step_id), {
        answers: userAnswer.value
    }, {
        onSuccess: progress => {
            loading.value = false
        },
    })
}

const getContent = (val) => {
    userAnswer.value = val
    console.log(userAnswer.value.length)
    if (userAnswer.value.length > 1) {
        disabled.value = false
    } else {
        disabled.value = true
    }
}
</script>

<template>
    <div>
        <div class="mb-8 border-b py-2">
            <h2 class="text-xl font-bold">{{ test.text }}</h2>
        </div>
        <div v-if="!stepUser">
            <CKeditor @sendContent="getContent"/>
            <div class="text-xl text-red-600 mt-2">
                <strong>*</strong>Ответ не должен быть пустым
            </div>
            <button v-if="disabled" class="mt-4 px-4 py-2 bg-gray-500 text-white font-bold rounded hover:bg-gray-600">
                Вы не заполнили форму
            </button>
            <button v-else @click="submit"
                    class="mt-4 px-4 py-2 bg-blue-500 text-white font-bold rounded hover:bg-blue-600">
                Отправить
            </button>
        </div>
        <div v-else v-html="stepUser.answers">
        </div>
        <Loading v-if="loading"/>
    </div>
</template>

<style scoped>

</style>
