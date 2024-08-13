<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    favorites: Array
});

// Создаем реактивный список избранного
const favoritesList = ref([...props.favorites]);

// Удаление курса из избранного
const removeFavorite = async (courseId) => {
    try {
        const response = await fetch('/removeFromFavorite', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ course_id: courseId })
        });
        const data = await response.json();
        console.log(data.message); // Вывод сообщения в консоль

        // Удаляем курс из списка избранного на клиенте
        favoritesList.value = favoritesList.value.filter(favorite => favorite.course.id !== courseId);
    } catch (error) {
        alert('Произошла ошибка при удалении курса из избранного.');
    }
};
</script>

<template>
    <AppLayout>
        <div class="container mx-auto py-8 px-2">
            <h1 class="text-2xl font-bold mb-4">Избранные курсы</h1>

            <div v-if="favoritesList.length > 0">
                <ul>
                    <li v-for="item in favoritesList" :key="item.id"
                        class="mb-4 p-4 border rounded-lg flex justify-between items-center">
                        <div class="flex flex-col lg:flex-row items-center justify-between w-full">
                            <div class="flex flex-col lg:flex-row items-center text-center lg:text-start">
                                <Link :href="route('showCourse', item.course.id)" class="mr-0 lg:mr-4">
                                    <img :src="item.course.image" :alt="item.course.name"
                                         class="w-24 h-24 object-cover rounded mr-4">
                                </Link>
                                <Link :href="route('showCourse', item.course.id)" class="mr-4">
                                    <h2 class="text-xl font-semibold">{{ item.course.name }}</h2>
                                </Link>
                            </div>
                            <div class="mt-8 lg:mt-0">
                                <button @click="removeFavorite(item.course.id)"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Удалить из избранного
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div v-else>
                <p class="text-center text-gray-600">У вас нет избранных курсов.</p>
            </div>
        </div>
    </AppLayout>
</template>
