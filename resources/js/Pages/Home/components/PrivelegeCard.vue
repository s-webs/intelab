<script setup>
const props = defineProps({
    title: String,
    description: String,
    index: Number
})
</script>

<template>
    <div class="border-[2px] border-main-primary md:w-[48%] p-[30px] rounded-[15px] m-[5px]">
        <div class="flex items-center flex-shrink-0">
            <div class="bg-main-primary w-[60px] h-[60px] flex-shrink-0 flex items-center justify-center mr-[20px] font-semibold text-xl text-white">
                {{ index}}
            </div>
            <div class="font-semibold text-2xl text-main-primary">{{ title}}</div>
        </div>
        <div class="mt-[60px] text-xl text-main-primary font-semibold">
            {{ description}}
        </div>
    </div>
</template>

<style scoped>

</style>
