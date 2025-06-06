<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Stocks from "@/Pages/Home/components/stocks.vue";
import Search from "@/Pages/Home/components/Search.vue";
import Categories from "@/Pages/Home/components/Categories.vue";
import Heading from "@/Components/Heading.vue";
import {ref, watch} from "vue";
import {useI18n} from "vue-i18n";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    stock: Object,
    categories: Array,
})

const {t, locale} = useI18n();

const currentLanguage = ref(locale.value);

watch(locale, (newLang) => {
    currentLanguage.value = newLang;
});
</script>

<template>
    <AppLayout>
        <div class="container mx-auto px-4">
            <Search class="mt-8"></Search>
<!--            <stocks class="mt-8" :stock="stock"></stocks>-->
            <div class="mt-8">
                <Heading :title="t('pages.categories')"/>
                <Categories :key="currentLanguage" :categories="categories"></Categories>
            </div>
        </div>
    </AppLayout>
</template>
