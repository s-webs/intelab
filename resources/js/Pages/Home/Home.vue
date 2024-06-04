<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Stocks from "@/Pages/Home/components/stocks.vue";
import Search from "@/Pages/Home/components/Search.vue";
import Categories from "@/Pages/Home/components/Categories.vue";
import Heading from "@/Components/Heading.vue";

const props = defineProps({
    stock: Object,
    categories: Array,
})
</script>

<template>
    <AppLayout>
        <div class="container mx-auto">
            <Search class="mt-8"></Search>
            <stocks class="mt-8" :stock="stock"></stocks>
            <div class="mt-8">
                <Heading :title="'Категории'"/>
                <Categories :categories="categories"></Categories>
            </div>
        </div>
    </AppLayout>
</template>
