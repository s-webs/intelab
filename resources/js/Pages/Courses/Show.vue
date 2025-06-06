<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {ref, onMounted} from "vue";
import {router, usePage} from "@inertiajs/vue3";

const props = defineProps({
    course: Object,
    lessonsTotalCount: Number,
    isUserEnrolled: Boolean
});

const page = usePage();
const user = page.props.auth.user;
const favorites = ref([]);

const isAuthenticated = () => {
    return user !== null;
};

// Функция для загрузки избранных курсов
const loadFavorites = async () => {
    try {
        const response = await axios.get('/getFavorites');
        favorites.value = response.data.favorites.map(favorite => favorite.course_id); // Сохраняем только ID курсов
    } catch (error) {
        console.log('Произошла ошибка при загрузке избранных курсов:', error.message);
    }
};

// Проверка, находится ли курс в избранном
const isFavorite = (courseId) => {
    return favorites.value.includes(courseId);
};

// Функция для добавления или удаления из избранного
const toggleFavorite = async () => {
    try {
        if (isFavorite(props.course.id)) {
            // Удалить из избранного
            const response = await fetch('/removeFromFavorite', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({course_id: props.course.id})
            });
            const data = await response.json();
            console.log(data.message); // Вывод сообщения в консоль
            favorites.value = favorites.value.filter(id => id !== props.course.id);
        } else {
            // Добавить в избранное
            const response = await fetch('/addToFavorite', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({course_id: props.course.id})
            });
            const data = await response.json();
            console.log(data.message); // Вывод сообщения в консоль
            favorites.value.push(props.course.id);
        }
    } catch (error) {
        console.log('Произошла ошибка при изменении статуса избранного.');
    }
};

// Загружаем избранные курсы при монтировании компонента
onMounted(() => {
    if (isAuthenticated()) {
        loadFavorites();
    }
});

const enroll = () => {
    router.post(route('enrollStudentCourse', props.course.id));
};
</script>

<template>
    <AppLayout>
        <div class="bg-[#1F5B4D] m-0 p-0">
            <div class="container mx-auto px-2">
                <div class="flex py-8 items-center justify-between flex-col-reverse xl:flex-row">
                    <div class="w-full xl:w-3/5">
                        <div>
                            <h1 class="text-4xl text-white">
                                {{ course.name }}
                            </h1>
                        </div>
                        <div class="mt-8 text-white">
                            {{ course.description }}
                        </div>
                        <div class="mt-8">
                            <span class="mr-4 bg-main-blue px-3 py-2 rounded-md text-white text-sm">
                                <i class="fa fa-clock mr-2"></i>{{ course.duration }}
                            </span>
                            <span class="mr-4 bg-main-blue px-3 py-2 rounded-md text-white text-sm">
                                <i class="fa fa-star text-yellow-400 mr-2"></i>{{ course.rating }}
                            </span>
                            <span class="bg-main-blue px-3 py-2 rounded-md text-white text-sm">
                                <i class="fa fa-users mr-2"></i>{{ course.users.length }} обучающихся
                            </span>
                        </div>
                    </div>
                    <div class="w-full xl:w-3/12">
                        <img :src="course.image" alt="" class="rounded-lg mx-auto mb-8 xl:mb-0">
                    </div>
                </div>
            </div>
        </div>
        <div class="container mx-auto mt-8 px-2 mb-[50px]">
            <div class="flex justify-between flex-col-reverse xl:flex-row">
                <div class="w-full xl:w-3/4 border-r pr-4">
                    <div v-html="course.content"></div>
                    <div class="font-bold text-2xl text-main-blue mt-8">
                        Преподаватель
                    </div>
                    <div class="flex items-center border w-full xl:w-3/6 p-3 rounded-lg bg-[#1F5B4D] mt-2">
                        <div class="w-[80px] h-[80px] mr-2">
                            <img :src="course.user.photo" alt="" class="w-full h-full object-cover rounded-full" />
                        </div>
                        <div>
                            <a href="##" class="block text-white font-bold">{{ course.user.name }}</a>
                            <span
                                class="text-sm text-white">Веб-разработка, HTML/CSS, Javascript, PHP, MySQL</span>
                        </div>
                    </div>
                    <div class="font-bold text-2xl text-main-blue mt-8">
                        Программа курса
                    </div>
                    <div class="">
                        <div v-for="module in course.modules" class="mt-2">
                            <strong>{{ module.name }}</strong>
                            <ul class="list-decimal pl-8">
                                <li v-for="lesson in module.lessons" class="">{{ lesson.name }}</li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <!--COMMENTS-->
                        <!--                        <div class="font-bold text-2xl text-main-blue mt-8 border-b-2 border-main-blue pb-2">-->
                        <!--                            Отзывы о курсе-->
                        <!--                        </div>-->
                        <!--                        <div class="flex items-center mt-4">-->
                        <!--                            <div class="text-3xl">-->
                        <!--                                <span class="text-main-blue font-bold">4</span>-->
                        <!--                                <i class="fa fa-star text-yellow-400"></i>-->
                        <!--                            </div>-->
                        <!--                            <div class="text-xs text-gray-300 border-l ml-3 pl-3">-->
                        <!--                                <div>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                </div>-->
                        <!--                                <div>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                </div>-->
                        <!--                                <div>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                </div>-->
                        <!--                                <div>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                </div>-->
                        <!--                                <div>-->
                        <!--                                    <i class="fa fa-star"></i>-->
                        <!--                                </div>-->
                        <!--                                <div class="mt-2"><strong>из 314 отзывов</strong></div>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <!--                        <div class="text-2xl text-center mt-8">-->
                        <!--                            <span>Отзывов пока нет, станьте первым!</span>-->
                        <!--                        </div>-->
                        <!--/COMMENTS-->
                    </div>
                </div>
                <div class="w-full xl:w-1/5 mb-4 xl:mb-0">
                    <div v-if="isAuthenticated()">
                        <button @click="enroll" v-if="course.type === 'free'"
                                class="w-full bg-green-700 hover:bg-green-800 text-center py-2 rounded-lg text-xl text-white">
                            <span v-if="isUserEnrolled">Продолжить обучение</span>
                            <span v-else>Начать обучение</span>
                        </button>
                        <button v-if="course.type === 'premium'"
                                class="w-full bg-green-700 text-center py-2 rounded-lg text-xl text-white">
                            <span>Купить</span>
                        </button>
                        <button @click="toggleFavorite"
                                class="w-full border-2 border-red-700 hover:bg-red-800 text-center py-1 rounded-lg text-xl text-red-700 hover:text-red-100 mt-4">
                            <span v-if="isFavorite(course.id)">Удалить из избранного</span>
                            <span v-else>Хочу пройти</span><i class="fa fa-heart ml-3"></i>
                        </button>
                    </div>
                    <div v-else>Авторизуйтесь, чтобы начать</div>
                    <div class="mt-8 bg-[#1F5B4D] p-4 rounded-lg">
                        <div class="text-white font-bold">В курс входят</div>
                        <div class="mt-4 text-sm font-medium text-white">{{ course.modules.length }} модулей</div>
                        <div class="text-sm font-medium text-white">{{ lessonsTotalCount }} уроков</div>
                        <div class="mt-8 text-sm italic text-white">Последнее обновление курса 19.12.2024</div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <!--COMMENTS-->
        </div>
    </AppLayout>
</template>
