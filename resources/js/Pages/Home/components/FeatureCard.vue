<script setup>
const props = defineProps({
    title: String,
    description: String,
    background: {
        type: String,
        default: '/images/default/home-pattern-02.png'
    }
})
</script>

<template>
    <div class="w-full bg-main-primary p-[30px] rounded-[15px] relative mb-[15px] lg:mx-[15px]">
        <div class="relative z-[5]">
            <div class="text-main-halftone text-lg font-semibold">{{ title }}</div>
            <div class="w-[100px] h-[3px] bg-main-halftone"></div>
        </div>
        <div class="text-main-halftone mt-[20px] relative z-[5]">
            <span>{{ description }}</span>
        </div>
        <div class="absolute z-[3] top-[0] left-[0] w-full h-full">
            <img :src="background" alt="Фон" class="object-cover w-full h-full"/>
        </div>
    </div>
</template>
