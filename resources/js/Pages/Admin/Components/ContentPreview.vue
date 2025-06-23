<script setup>
 const props = defineProps({
     step: Object
 })
</script>

<template>
    <div v-html="step.content.content"></div>
</template>

<style scoped>

</style>
