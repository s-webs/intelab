<script setup>

import FeedBackCard from "@/Pages/Home/components/FeedBackCard.vue";
</script>

<template>
<div class="bg-main-primary py-[50px] px-4">
    <div class="container mx-auto">
        <div
            class="text-center max-w-[800px] mx-auto text-white border-b-2 border-white pb-[10px]">
            <h3 class="text-2xl font-semibold">Отзывы пользователей</h3>
        </div>
        <div class="mt-[30px] flex items-center justify-between flex-wrap">
            <FeedBackCard />
            <FeedBackCard />
        </div>
    </div>
</div>
</template>

<style scoped>

</style>
