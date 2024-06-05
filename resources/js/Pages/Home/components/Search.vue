<script setup>
import {useI18n} from 'vue-i18n';

const {t} = useI18n();
</script>

<template>
    <div class="container mx-auto">
        <div class="bg-main-blue py-3 px-5 rounded-md flex flex-wrap items-center justify-center lg:justify-between">
            <div class="flex items-center flex-wrap justify-center lg:justify-start">
                <input type="text" class="p-1 w-full lg:w-96 mr-2 rounded-md px-2 mb-4 lg:mb-0"
                       :placeholder="t('search.enterNameCourse')">
                <input type="checkbox" class="mr-2">
                <span class="mr-2 text-white">{{ t('search.withCertificate') }}</span>
                <input type="checkbox" class="mr-2"><span class="text-white">{{ t('search.freeCourse') }}</span>
            </div>
            <div class="mt-4 lg:mt-0">
                <button class="bg-green-500 py-2 px-10 flex items-center rounded-lg text-green-800">
                    <span class="mr-2 text-md font-medium">{{ t('search.search') }}</span>
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </div>
</template>
