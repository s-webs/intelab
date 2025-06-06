<script setup>
 import {Link} from "@inertiajs/vue3";
</script>

<template>
    <div class="overflow-hidden relative">
        <div class="container mx-auto px-4 py-[50px] xl:py-[120px] flex items-center justify-between">
            <div class="z-[5] w-full md:w-auto">
                <div class="md:max-w-[940px]">
                    <h1 class="text-[45px] xl:text-[60px] text-main-primary text-shadow-outline" style="">
                        Создавай, продавай и обучай с Intelab!</h1>
                </div>
                <div class="max-w-[736px]">
                    <h2 class="text-[18px] xl:text-[30px] text-main-primary text-shadow-outline">
                        Онлайн-платформа для авторов курсов и студентов</h2>
                </div>
                <div class="mt-[50px]">
                    <div>
                        <Link :href="route('teacherCourse.index')"
                           class=" block px-[30px] py-[22px] border-2 border-main-primary rounded-[15px] bg-main-halftone text-main-primary text-xl font-bold w-full md:w-[570px] hover:bg-main-primary hover:text-main-halftone duration-300">Создать
                            курс</Link>
                    </div>
                    <div class="mt-[30px]">
                        <Link :href="route('catalog.index')"
                           class=" block px-[30px] py-[22px] border-2 border-main-primary rounded-[15px] bg-main-halftone text-main-primary text-xl font-bold w-full md:w-[570px] hover:bg-main-primary hover:text-main-halftone duration-300">Перейти
                            к обучению</Link>
                    </div>
                </div>
            </div>
            <div class="z-[5]">
                <div class="hidden md:block">
                    <img src="/images/default/home-macbook.png" alt="" class="w-[320px] xl:w-[780px]">
                </div>
            </div>
            <div class="absolute z-[2] left-[0] top-[0]">
                <img src="/images/default/home-pattern-01.png" class="" alt="">
            </div>
        </div>
    </div>
</template>
