<script setup>
import {useI18n} from "vue-i18n";

const {t, locale} = useI18n()
</script>

<template>
    <ul class="rounded-lg p-5 shadow-md border bg-white">
        <li class="mb-3">
            <a href="##" class="text-[#2E4E4E] font-medium">{{ t('pages.createCourseManual') }} (Нужно сделать)</a>
        </li>
        <li class="">
            <a href="##" class="text-[#2E4E4E] font-medium">{{ t('pages.createCourseConditions') }} Условия (Нужно сделать)</a>
        </li>
    </ul>
</template>

<style scoped>

</style>
