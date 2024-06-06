<script setup>
import { defineProps, computed } from 'vue';
import { useI18n } from 'vue-i18n';

const props = defineProps({
    breadcrumbs: {
        type: Array,
        required: true,
    },
});

const { t } = useI18n();

const translatedBreadcrumbs = computed(() =>
    props.breadcrumbs.map(breadcrumb => ({
        ...breadcrumb,
        label: t(breadcrumb.label),
    }))
);
</script>

<template>
    <nav class="flex py-3 px-5 text-gray-700 bg-gray-100 rounded-md shadow-sm" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li v-for="(breadcrumb, index) in translatedBreadcrumbs" :key="index" class="inline-flex items-center">
                <div v-if="index !== translatedBreadcrumbs.length - 1">
                    <a :href="breadcrumb.url" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i :class="breadcrumb.icon" v-if="breadcrumb.icon"></i>
                        <span v-if="breadcrumb.icon" class="ml-2">{{ breadcrumb.label }}</span>
                        <span v-else>{{ breadcrumb.label }}</span>
                    </a>
                    <span class="mx-2 text-gray-400">/</span>
                </div>
                <div v-else class="inline-flex items-center text-sm font-medium text-gray-500">
                    <i :class="breadcrumb.icon" v-if="breadcrumb.icon"></i>
                    <span v-if="breadcrumb.icon" class="ml-2">{{ breadcrumb.label }}</span>
                    <span v-else>{{ breadcrumb.label }}</span>
                </div>
            </li>
        </ol>
    </nav>
</template>
