<script setup>
import {ref, computed} from 'vue';
import {Head, Link, router, usePage} from '@inertiajs/vue3';
import Loading from "@/Components/Loading.vue";
import {useI18n} from 'vue-i18n';

const {props} = usePage();
const user = computed(() => props.auth.user);
const {t, locale} = useI18n();

const loading = ref(false);
const isDropdownOpen = ref(false);
const menuOpen = ref(false);

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const toggleMenu = () => {
    menuOpen.value = !menuOpen.value;
};

const logout = () => {
    loading.value = true;
    router.post(route('logout'), {}, {
        onFinish: () => {
            router.visit(window.location.href);
            loading.value = false;
        },
    });
};

const showSidebar = ref(false);

// Добавлен объект с языками и текущим языком
const languages = {
    ru: 'Русский',
    en: 'English',
    kz: 'Қазақша'
};
const currentLanguage = computed(() => locale.value);

// Функция для смены языка
const changeLanguage = (lang) => {
    locale.value = lang;
    localStorage.setItem('language', lang);
    isDropdownOpen.value = false;
};

if (localStorage.getItem('language')) {
    locale.value = localStorage.getItem('language');
}
</script>

<template>
    <div class="bg-main-halftone">
        <Head/>
        <Loading v-if="loading"/>
        <header class="py-[22px] px-[30px] bg-main-primary relative z-[5]">
            <div class="container mx-auto header__wrapper flex justify-between items-center">
                <div class="header__btn-menu text-xl flex-1 text-white">
                    <button @click="showSidebar = !showSidebar"><i class="fa fa-bars"></i></button>
                </div>
                <Link href="/" class="block">
                    <img class="w-[80px] md:w-[107px] absolute top-[12px] left-1/2 transform -translate-x-1/2"
                         src="/images/default/intelab-logo.png" alt="logo"/>

                </Link>
                <div class="flex items-center flex-1 justify-end">
                    <div class="relative">
                        <div class="border text-white px-2 cursor-pointer" @click="toggleDropdown">
                            <span class="mr-2">{{ $t('languages.' + currentLanguage) }}</span>
                            <i class="fa fa-caret-down"></i>
                        </div>
                        <div v-if="isDropdownOpen"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                            <div v-for="(lang, key) in languages" :key="key" @click="changeLanguage(key)"
                                 class="cursor-pointer block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ lang }}
                            </div>
                        </div>
                    </div>
                    <div class="ml-3 hidden lg:block">
                        <div v-if="user" class="ml-3">
                            <button @click="toggleMenu" class="block">
                                <img class="w-12 h-12 rounded-full border object-cover" :src="user.photo" alt="avatar"/>
                            </button>
                            <div v-if="menuOpen"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                                <a :href="route('profile.show')"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Профиль
                                </a>
                                <a href="#" @click.prevent="logout"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ t('auth.logout') }}
                                </a>
                            </div>
                        </div>
                        <div v-else>
                            <Link :href="route('login')" class="border mr-2 text-white py-1 px-2">
                                {{ t('auth.login') }}
                            </Link>
                            <Link :href="route('register')" class="border text-white py-1 px-2">{{ t('auth.register') }}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- drawer component -->
        <div id="drawer-navigation"
             :class="{'transform-none' : showSidebar}"
             class="fixed top-0 left-0 z-40 w-64 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-main-halftone border-r-[2px] border-main-primary"
             tabindex="-1" aria-labelledby="drawer-navigation-label">
            <div class="flex items-center justify-between">
                <img class="w-[70px]" src="/images/default/intelab-logo.png" alt="">
                <button @click="showSidebar = !showSidebar" type="button"
                        class="text-main-halftoneDark bg-transparent hover:text-main-primary rounded-lg text-sm p-1.5">
                    <i class="fa fa-xmark-circle text-2xl"></i>
                </button>
            </div>

            <div class="py-4 overflow-y-auto mt-4 border-t border-main-primary">
                <ul class="font-medium">
                    <li class="lg:hidden">
                        <div v-if="user"
                             class="flex justify-between items-center border rounded-full border-main-primary p-[6px]">
                            <button @click="toggleMenu" class="flex items-center">
                                <img class="w-8 h-8 rounded-full border object-cover" :src="user.photo" alt="avatar"/>
                                <span class="ml-2 text-main-primary">{{ user.name }}</span>
                            </button>
                            <div>
                                <a href="#" @click.prevent="logout"
                                   class="flex items-center justify-center text-[12px] text-main-halftone bg-main-primary w-[25px] h-[25px] rounded-full">
                                    <i class="fa fa-right-from-bracket text-md"></i>
                                </a>
                            </div>
                        </div>
                        <div v-else>
                            <Link :href="route('login')"
                                  class="block rounded-full border border-main-primary text-main-primary text-center py-1 px-2 mb-[10px] ">
                                {{ t('auth.login') }}
                            </Link>
                            <Link :href="route('register')"
                                  class="block rounded-full border border-main-primary text-main-primary text-center py-1 px-2">
                                {{ t('auth.register') }}
                            </Link>
                        </div>
                    </li>
                    <div v-if="user">
                        <div v-if="user.email === 'swebs.sh@gmail.com' || user.email === 'shokhnoza.kh@gmail.com'">
                            <li class="text-sm text-main-primary border-b border-main-primary mt-5">
                                <span>ADMIN</span>
                            </li>
                            <li>
                                <Link :href="route('admin.dashboard')"
                                      class="flex border border-main-primary hover:bg-main-primary hover:text-white items-center bg-main-halftone py-1 px-2 text-sm text-main-primary rounded-lg mt-4">
                                    <i class="fa-solid fa-dashboard mr-2"></i>
                                    <span class="pt-0.5">Админ панель</span>
                                </Link>
                            </li>
                        </div>
                    </div>
                    <!-- Основные -->
                    <li class="text-sm text-main-primary border-b border-main-primary mt-5">
                        <span>{{ t('menu.main') }}</span>
                    </li>
                    <li>
                        <Link :href="route('profile.show')"
                              class="flex border border-main-primary hover:bg-main-primary hover:text-white items-center bg-main-halftone py-1 px-2 text-sm text-main-primary rounded-lg mt-4">
                            <i class="fa fa-user mr-2"></i>
                            <span class="pt-0.5">Профиль</span>
                        </Link>
                        <Link href="/"
                              class="flex border border-main-primary hover:bg-main-primary hover:text-white items-center bg-main-halftone py-1 px-2 text-sm text-main-primary rounded-lg mt-4">
                            <i class="fa fa-home mr-2"></i>
                            <span class="pt-0.5">{{ t('menu.main') }}</span>
                        </Link>
                        <a href="#"
                           class="flex border border-main-primary hover:bg-main-primary hover:text-white items-center bg-main-halftone py-1 px-2 text-sm text-main-primary rounded-lg mt-4">
                            <i class="fa fa-magnifying-glass mr-2"></i>
                            <span class="pt-0.5">{{ t('menu.search') }}</span>
                        </a>
                    </li>
                    <!-- Преподавание -->
                    <li class="text-sm text-main-primary border-b border-main-primary mt-5">
                        <span>{{ t('menu.teaching') }}</span>
                    </li>
                    <li>
                        <Link :href="route('teacherCourse.index')"
                              class="flex border border-main-primary hover:bg-main-primary hover:text-white items-center bg-main-halftone py-1 px-2 text-sm text-main-primary rounded-lg mt-4">
                            <i class="fa-solid fa-lines-leaning mr-2"></i>
                            <span class="pt-0.5">{{ t('menu.myCourses') }}</span>
                        </Link>
                    </li>
                    <!-- Обучение -->
                    <li class="text-sm text-main-primary border-b border-main-primary mt-5">
                        <span>{{ t('menu.training') }}</span>
                    </li>
                    <li>
                        <Link :href="route('userCourses')"
                              class="flex border border-main-primary hover:bg-main-primary hover:text-white items-center bg-main-halftone py-1 px-2 text-sm text-main-primary rounded-lg mt-4">
                            <i class="fa-solid fa-graduation-cap mr-2"></i>
                            <span class="pt-0.5">{{ t('menu.myCourses') }}</span>
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('favorites')"
                              class="flex border border-main-primary hover:bg-main-primary hover:text-white items-center bg-main-halftone py-1 px-2 text-sm text-main-primary rounded-lg mt-4">
                            <i class="fa-solid fa-heart mr-2"></i>
                            <span class="pt-0.5">{{ t('menu.favorites') }}</span>
                        </Link>
                    </li>
                </ul>
            </div>
        </div>

        <main class="min-h-screen">
            <slot/>
        </main>

        <footer class="bg-main-primary">
            <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <Link href="/"
                          class="flex items-center justify-center md:justify-start mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
                        <img src="/images/default/intelab-logo.png" class="w-[80px]" alt="Intelab Logo"/>
                    </Link>
                    <ul class="flex flex-wrap flex-col md:flex-row items-center justify-center md:justify-start mb-6 text-sm font-medium text-white sm:mb-0">
                        <li class="md:mr-[15px]">
                            <a href="#" class="hover:underline">{{ t('menu.about') }}</a>
                        </li>
                        <li class="md:mr-[15px]">
                            <a href="#" class="hover:underline">{{ t('menu.privacyPolicy') }}</a>
                        </li>
                        <li class="md:mr-[15px]">
                            <a href="#" class="hover:underline">{{ t('menu.license') }}</a>
                        </li>
                        <li>
                            <a href="#" class="hover:underline">{{ t('menu.contacts') }}</a>
                        </li>
                    </ul>
                </div>
                <hr class="my-6 border-main-halftone sm:mx-auto lg:my-8"/>
                <span class="block text-sm text-main-halftone text-center md:text-start sm:text-center">© 2024
                    <Link href="/" class="hover:underline">Intelab</Link>. {{ t('menu.allRR') }}.</span>
            </div>
        </footer>
    </div>
</template>
