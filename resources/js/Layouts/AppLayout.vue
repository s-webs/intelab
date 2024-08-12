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
    <div>
        <Head/>
        <Loading v-if="loading"/>
        <header class="py-3 bg-main-blue px-2.5">
            <div class="container mx-auto header__wrapper flex justify-between items-center">
                <div class="header__btn-menu text-xl flex-1 text-white">
                    <button @click="showSidebar = !showSidebar"><i class="fa fa-bars"></i></button>
                </div>
                <Link href="/" class="block">
                    <img class="w-32" src="/images/default/logo.svg" alt="logo"/>
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
                                <img class="w-12 h-12 rounded-full border" :src="user.profile_photo_path" alt="avatar"/>
                            </button>
                            <div v-if="menuOpen"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
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
             class="fixed top-0 left-0 z-40 w-64 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-blue-950 -translate-x-full"
             tabindex="-1" aria-labelledby="drawer-navigation-label">
            <div class="flex items-center justify-between">
                <img class="w-28" src="/images/default/logo.svg" alt="">
                <button @click="showSidebar = !showSidebar" type="button"
                        class="text-gray-100 bg-transparent hover:text-gray-600 rounded-lg text-sm p-1.5">
                    <i class="fa fa-xmark-circle text-2xl"></i>
                </button>
            </div>

            <div class="py-4 overflow-y-auto mt-4 border-t border-gray-600">
                <ul class="font-medium">
                    <li class="lg:hidden">
                        <div v-if="user" class="flex justify-between items-center">
                            <button @click="toggleMenu" class="flex items-center">
                                <img class="w-8 h-8 rounded-full border" :src="user.profile_photo_path" alt="avatar"/>
                                <span class="ml-2 text-white">{{ user.name }}</span>
                            </button>
                            <div>
                                <a href="#" @click.prevent="logout"
                                   class="block px-4 py-2 text-sm text-red-600">
                                    <i class="fa fa-right-from-bracket text-md"></i>
                                </a>
                            </div>
                        </div>
                        <div v-else>
                            <Link :href="route('login')" class="block border text-white py-1 px-2">
                                {{ t('auth.login') }}
                            </Link>
                            <Link :href="route('register')" class="block border text-white mt-3 py-1 px-2">
                                {{ t('auth.register') }}
                            </Link>
                        </div>
                    </li>
                    <!-- Основыные -->
                    <li class="text-sm text-gray-400 border-b border-gray-400 mt-4">
                        <span>{{ t('menu.main') }}</span>
                    </li>
                    <li>
                        <Link href="/"
                              class="flex border border-white hover:bg-main-blue hover:text-white items-center bg-white py-1 px-2 text-sm text-main-blue rounded-lg hover:bg-gray-100 mt-4">
                            <i class="fa fa-home mr-2"></i>
                            <span class="pt-0.5">{{ t('menu.main') }}</span>
                        </Link>
                        <a href="#"
                           class="flex border border-white hover:bg-main-blue hover:text-white items-center bg-white py-1 px-2 text-sm text-main-blue rounded-lg hover:bg-gray-100 mt-4">
                            <i class="fa fa-magnifying-glass mr-2"></i>
                            <span class="pt-0.5">{{ t('menu.search') }}</span>
                        </a>
                    </li>
                    <!-- Преподавание -->
                    <li class="text-sm text-gray-400 border-b border-gray-400 mt-5">
                        <span>{{ t('menu.teaching') }}</span>
                    </li>
                    <li>
                        <Link :href="route('teacherCourse.index')"
                              class="flex border border-white hover:bg-main-blue hover:text-white items-center bg-white py-1 px-2 text-sm text-main-blue rounded-lg hover:bg-gray-100 mt-4">
                            <i class="fa-solid fa-lines-leaning mr-2"></i>
                            <span class="pt-0.5">{{ t('menu.myCourses') }}</span>
                        </Link>
                    </li>
                    <!-- Обучение -->
                    <li class="text-sm text-gray-400 border-b border-gray-400 mt-5">
                        <span>{{ t('menu.training') }}</span>
                    </li>
                    <li>
                        <Link :href="route('userCourses')"
                              class="flex border border-white hover:bg-main-blue hover:text-white items-center bg-white py-1 px-2 text-sm text-main-blue rounded-lg hover:bg-gray-100 mt-4">
                            <i class="fa-solid fa-graduation-cap mr-2"></i>
                            <span class="pt-0.5">{{ t('menu.myCourses') }}</span>
                        </Link>
                    </li>
                </ul>
            </div>
        </div>

        <main class="min-h-screen">
            <slot/>
        </main>

        <footer class="bg-main-blue mt-8">
            <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <Link href="/"
                          class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
                        <img src="/images/default/logo.svg" class="h-8" alt="Intelab Logo"/>
                    </Link>
                    <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-white sm:mb-0">
                        <li>
                            <a href="#" class="hover:underline me-4 md:me-6">{{ t('menu.about') }}</a>
                        </li>
                        <li>
                            <a href="#" class="hover:underline me-4 md:me-6">{{ t('menu.privacyPolicy') }}</a>
                        </li>
                        <li>
                            <a href="#" class="hover:underline me-4 md:me-6">{{ t('menu.license') }}</a>
                        </li>
                        <li>
                            <a href="#" class="hover:underline">{{ t('menu.contacts') }}</a>
                        </li>
                    </ul>
                </div>
                <hr class="my-6 border-gray-200 sm:mx-auto lg:my-8"/>
                <span class="block text-sm text-gray-500 sm:text-center text-white">© 2024
                    <Link href="/" class="hover:underline">Intelab</Link>. {{ t('menu.allRR') }}.</span>
            </div>
        </footer>
    </div>
</template>
