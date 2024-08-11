<script setup>
import { ref, watchEffect } from "vue";
import { defineProps } from "vue";

const props = defineProps({
    title: String,
    message: String,
    type: {
        type: String,
        default: "info",
    },
    duration: {
        type: Number,
        default: 3000,
    },
});

const visible = ref(false);
const typeClass = ref("");

watchEffect(() => {
    switch (props.type) {
        case "success":
            typeClass.value = "bg-green-500";
            break;
        case "error":
            typeClass.value = "bg-red-500";
            break;
        case "warning":
            typeClass.value = "bg-yellow-500";
            break;
        default:
            typeClass.value = "bg-blue-500";
            break;
    }
});

const hide = () => {
    visible.value = false;
};

const show = () => {
    visible.value = true;
    setTimeout(hide, props.duration);
};

// Показать уведомление при монтировании
show();
</script>

<template>
    <transition name="fade">
        <div v-if="visible" class="fixed top-5 right-5 z-50 p-4 bg-white rounded-lg shadow-lg flex items-center space-x-2">
            <span :class="typeClass" class="w-5 h-5 rounded-full"></span>
            <div @click="hide">
                <div class="font-bold">{{ title }}</div>
                <div>{{ message }}</div>
            </div>
            <button @click="hide" class="ml-auto text-gray-500 hover:text-gray-700">&times;</button>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
