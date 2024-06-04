<script setup>
const props = defineProps({
    title: String
})
</script>

<template>
    <div class="border-b-4 border-gray-300 pb-2">
        <h3 class="text-3xl text-main-blue font-bold">{{ title }}</h3>
    </div>
</template>
