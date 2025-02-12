<script setup>
import {useI18n} from 'vue-i18n';
import {ref} from "vue";
import {router} from "@inertiajs/vue3";

const {t} = useI18n();

const searchQuery = ref(''); // Переменная для хранения поискового запроса

// Функция для выполнения поиска
const performSearch = () => {
    if (searchQuery.value.trim()) {
        // Переход на страницу со списком результатов
        router.visit(route('search.results', { query: searchQuery.value }), {preserveState: true})
    }
};
</script>

<template>
    <div class="container mx-auto">
        <div class="bg-main-blue py-3 px-5 rounded-md flex flex-wrap lg:flex-nowrap items-center justify-center lg:justify-between box-border">
            <div class="w-full lg:mr-4">
                <input
                    type="text"
                    v-model="searchQuery"
                    class="p-1 w-full rounded-md px-2 mb-4 lg:mb-0"
                    :placeholder="t('search.enterNameCourse')"
                    @keyup.enter="performSearch"
                />
<!--                <input type="checkbox" class="mr-2">-->
<!--                <span class="mr-2 text-white">{{ t('search.withCertificate') }}</span>-->
<!--                <input type="checkbox" class="mr-2"><span class="text-white">{{ t('search.freeCourse') }}</span>-->
            </div>
            <div class="mt-2 lg:mt-0 w-full lg:w-auto">
                <button @click="performSearch" class="bg-green-500 w-full py-2 px-10 flex items-center justify-center rounded-lg text-green-800">
                    <span class="mr-2 text-md font-medium">{{ t('search.search') }}</span>
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </div>
</template>
