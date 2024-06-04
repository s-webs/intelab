<script setup>
const props = defineProps({
    stock: Object
})
</script>

<template>
    <div class="mt-8">
        <a v-if="stock.link" :href="stock.link" class="block">
            <img class="w-full rounded-md object-cover" :src="stock.image" :alt="stock.name">
        </a>
        <div v-else>
            <img class="w-full rounded-md object-cover" :src="stock.image" :alt="stock.name">
        </div>
    </div>
</template>
