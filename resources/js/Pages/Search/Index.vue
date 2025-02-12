<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Heading from "@/Components/Heading.vue";
import { useI18n } from "vue-i18n";
import { Link, router, usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import Search from "@/Pages/Home/components/Search.vue";

const { t, locale } = useI18n();
const props = defineProps({
    query: String, // Поисковый запрос
    results: Object, // Результаты поиска с пагинацией
});

const page = usePage();
const user = page.props.auth.user;
const favorites = ref([]); // Храним избранные курсы

const isAuthenticated = () => {
    return user !== null;
};

// Функция для загрузки избранных элементов
const loadFavorites = async () => {
    try {
        const response = await axios.get('/getFavorites');
        favorites.value = response.data.favorites.map(favorite => favorite.course_id); // Сохраняем только ID курсов
    } catch (error) {
        console.log('Произошла ошибка при загрузке избранных элементов:', error.message);
    }
};

// Проверка, находится ли курс в избранном
const isFavorite = (courseId) => {
    return favorites.value.includes(courseId);
};

// Функция для добавления или удаления из избранного
const toggleFavorite = async (course) => {
    try {
        if (isFavorite(course.id)) {
            // Удалить из избранного
            const response = await fetch('/removeFromFavorite', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ course_id: course.id })
            });
            const data = await response.json();
            console.log(data.message); // Выведем сообщение для проверки
            favorites.value = favorites.value.filter(id => id !== course.id);
        } else {
            // Добавить в избранное
            const response = await fetch('/addToFavorite', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ course_id: course.id })
            });
            const data = await response.json();
            console.log(data.message); // Выведем сообщение для проверки
            favorites.value.push(course.id);
        }
    } catch (error) {
        console.log('Произошла ошибка при изменении статуса избранного.');
    }
};

// Загрузить избранные курсы при монтировании компонента
onMounted(() => {
    loadFavorites();
});

function truncate(text, length) {
    if (text.length > length) {
        return text.substring(0, length) + '...';
    }
    return text;
}
</script>

<template>
    <AppLayout>
        <Search class="mt-8"></Search>
        <div class="container mx-auto my-8 px-2 mt-8">
            <Heading :title="`Результаты поиска: ${query}`"></Heading>

            <div v-if="results.data.length > 0" class="flex flex-wrap -mx-4 py-4">
                <!-- Список курсов -->
                <div class="w-full px-4">
                    <div class="bg-white shadow-md rounded p-4 mb-4 md:p-6 lg:p-8 flex flex-wrap flex-col lg:flex-row items-center border border-blue-300"
                         v-for="course in results.data" :key="course.id">
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
                                    <span class="text-sm underline">{{ course.user.name }}</span>
                                </div>
                                <div class="text-gray-600 mt-4 mb-4 md:mb-6 lg:mb-8">
                                    {{ truncate(course.description, 200) }}
                                </div>
                            </div>
                            <div class="flex flex-wrap justify-between items-center">
                                <div class="text-center">
                                    <button v-if="isAuthenticated()"
                                            @click="toggleFavorite(course)"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full lg:w-auto lg:mb-0 mb-4 mt-2 lg:mt-0">
                                        {{ isFavorite(course.id) ? t('pages.removeFavorite') : t('pages.addFavorite') }}
                                    </button>
                                    <a :href="route('showCourse', course.id)"
                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded lg:ml-4 w-full lg:w-auto lg:mt-0">
                                        {{ t('pages.startLearning') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Пагинация -->
                <div class="w-full flex justify-between items-center mt-4 px-4">
                    <button
                        v-if="results.prev_page_url"
                        @click="router.visit(results.prev_page_url, { preserveState: true })"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md"
                    >
                        Назад
                    </button>
                    <span v-else class="px-4 py-2"></span>

                    <span class="text-gray-600">
                        Страница {{ results.current_page }} из {{ results.last_page }}
                    </span>

                    <button
                        v-if="results.next_page_url"
                        @click="router.visit(results.next_page_url, { preserveState: true })"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md"
                    >
                        Вперед
                    </button>
                    <span v-else class="px-4 py-2"></span>
                </div>
            </div>

            <div v-else class="text-center text-2xl mt-20">
                <span class="border-2 border-blue-600 px-12 py-4 rounded-lg text-blue-700">
                    {{ t('pages.noResultsFound') }}
                </span>
            </div>
        </div>
    </AppLayout>
</template>
