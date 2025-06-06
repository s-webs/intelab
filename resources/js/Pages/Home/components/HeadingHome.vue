<script setup>
const props = defineProps({
    title: String,
})
</script>

<template>
    <div
        class="text-center max-w-[800px] mx-auto text-main-primary border-b-2 border-main-primary pb-[10px]">
        <h3 class="text-2xl font-semibold">{{ title }}</h3>
    </div>
</template>

<style scoped>

</style>
