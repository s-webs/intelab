<script setup>
const props = defineProps({
    icon: String,
    title: String,
    description: String,
})
</script>

<template>
    <div class="bg-main-primary rounded-[15px] p-[30px] w-full lg:w-[30%] mb-[20px] lg:mb-0">
        <div class="">
            <img :src="icon" :alt="title" class="mx-auto"/>
        </div>
        <div class="text-white font-semibold text-center mt-[15px]">
            <span class="">{{ title }}</span>
            <div class="w-[40%] h-[2px] bg-white mx-auto mt-[5px]"></div>
        </div>
        <div class="mt-[30px] text-white text-center">
            {{ description}}
        </div>
    </div>
</template>

<style scoped>

</style>
