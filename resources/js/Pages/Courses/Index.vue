<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Heading from "@/Components/Heading.vue";
import {useI18n} from "vue-i18n";
import {Link, router} from "@inertiajs/vue3";
import {ref} from "vue";

const {t, locale} = useI18n();
const props = defineProps({
    category: Object,
    courses: Array,
    childCategories: Array
});

const selectedCategory = ref(null);

function truncate(text, length) {
    if (text.length > length) {
        return text.substring(0, length) + '...';
    }
    return text;
}

function filterCourses() {
    if (selectedCategory.value) {
        return props.courses.filter(course => course.category_id === selectedCategory.value);
    }
    return props.courses;
}
</script>

<template>
    <AppLayout>
        <div class="container mx-auto my-8 px-2">
            <Heading
                :title="locale === 'kz' ? category.name_kz :
                       locale === 'en' ? category.name_en :
                       category.name"
            ></Heading>
            <div v-if="courses.length > 0" class="flex flex-wrap -mx-4 py-4">
                <!-- Фильтры -->
                <div class="w-full md:w-1/4 xl:w-1/5 p-4 bg-white shadow-md rounded border border-blue-300">
                    <div class="">
                        <h5 class="font-bold mb-2">{{ t('pages.filterByCategory') }}</h5>
                        <ul>
                            <li>
                                <button @click="selectedCategory = null"
                                        class="w-full text-left p-2 rounded hover:bg-blue-200">
                                    - {{ t('pages.allCategories') }}
                                </button>
                            </li>
                            <li v-for="childCategory in childCategories" :key="childCategory.id">
                                <button @click="selectedCategory = childCategory.id"
                                        class="w-full text-left p-2 rounded hover:bg-blue-200">
                                    - {{ locale === 'kz' ? childCategory.name_kz :
                                    locale === 'en' ? childCategory.name_en :
                                        childCategory.name }}
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Список курсов -->
                <div class="w-full md:w-3/4 xl:w-4/5 px-4">
                    <div class="bg-white shadow-md rounded p-4 mb-4 md:p-6 lg:p-8 flex flex-wrap border border-blue-300"
                         v-for="course in filterCourses()" :key="course.id">
                        <Link :href="route('showCourse', course.id)">
                            <img :src="course.image" :alt="course.name" class="w-48 h-48 object-cover rounded mr-4">
                        </Link>
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div>
                                    <Link :href="route('showCourse', course.id)"
                                          class="text-lg font-bold mb-2 md:text-xl lg:text-2xl">{{ course.name }}
                                    </Link>
                                </div>
                                <div>
                                    <Link :href="route('home')" class="text-sm underline">{{ course.user.name }}</Link>
                                </div>
                                <div class="text-gray-600 mt-4 mb-4 md:mb-6 lg:mb-8">
                                    {{ truncate(course.description, 200) }}
                                </div>
                            </div>
                            <div class="flex flex-wrap justify-between items-center">
                                <div
                                    class="bg-green-400 text-white font-bold py-2 px-4 rounded w-full lg:w-auto text-center">
                                    0 ₸
                                </div>
                                <div>
                                    <button
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full lg:w-auto mt-2 lg:mt-0">
                                        {{ t('pages.addFavorite') }}
                                    </button>
                                    <a :href="route('showCourse', course.id)"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded lg:ml-4 w-full lg:w-auto mt-2 lg:mt-0">
                                        {{ t('pages.startLearning') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="text-center text-2xl mt-20">
                <span
                    class="border-2 border-blue-600 px-12 py-4 rounded-lg text-blue-700">{{
                        t('pages.categoryNotCourses')
                    }}
                </span>
            </div>
        </div>
    </AppLayout>
</template>
