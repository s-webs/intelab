<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import FeatureCard from "@/Pages/Home/components/FeatureCard.vue";
import TopHeader from "@/Pages/Home/components/TopHeader.vue";
import PrivelegeBlock from "@/Pages/Home/components/PrivelegeBlock.vue";
import TraitCard from "@/Pages/Home/components/TraitCard.vue";
import HeadingHome from "@/Pages/Home/components/HeadingHome.vue";
import FeedBackBlock from "@/Pages/Home/components/FeedBackBlock.vue";
</script>

<template>
    <AppLayout>
        <div class="">
            <TopHeader/>
        </div>
        <div class="bg-[#E9EDDC]">
            <div class="container mx-auto px-4 py-[50px]">
                <HeadingHome title="Добро пожаловать" />
                <div class="flex items-center flex-wrap lg:flex-nowrap justify-between mt-[50px]">
                    <FeatureCard
                        title="Информационная платформа"
                        description="Intelab - это современная онлайн-платформа, созданная для авторов курсов и студентов"
                    />
                    <FeatureCard
                        title="Активное сообщество"
                        description="Более 100,000 активных пользователей уже используют Intelab для обучения и создания курсов"
                    />
                    <FeatureCard
                        title="Разнообразие курсов"
                        description="Более 5,000 курсов в различных категориях, от бизнеса до искусства"
                    />
                </div>
            </div>
        </div>
        <div class="">
            <div class="container mx-auto px-4 py-[50px]">
                <HeadingHome title="Преимущества" />
                <PrivelegeBlock/>
            </div>
        </div>
        <div class="bg-[#E9EDDC]">
            <div class="container mx-auto px-4 py-[50px]">
                <HeadingHome title="Ключевые особенности" />
                <div class="mt-[30px] flex flex-wrap items-center justify-between">
                    <TraitCard icon="/images/default/camera-white.png" title="Информационная платформа" description="Intelab - это современная онлайн-платформа, созданная для авторов курсов и студентов" />
                    <TraitCard icon="/images/default/probirka-white.png" title="Встроенная система тестирования" description="Проверяйте свои знания с помощью интерактивных тестов" />
                    <TraitCard icon="/images/default/video-white.png" title="Вебинары и Live сессии" description="Общайтесь с преподавателями в режиме реального времени" />
                </div>
            </div>
        </div>
        <div>
         <FeedBackBlock />
        </div>
        <div class="mt-[50px]">
            <div class="relative flex items-center h-[500px]">
                <div class="container mx-auto px-4">
                    <div class="relative z-[5] text-4xl font-bold text-white">Начните бесплатно уже сегодня!</div>
                    <div class="relative z-[5] my-[30px]">
                        <a href="##" class="block p-[16px] bg-[#8DBDBD] hover:bg-main-primary hover:text-[#8DBDBD] max-w-[600px] text-[#476B6B] text-2xl font-bold text-center rounded-[15px] transition-all duration-300">Зарегистрируйтесь бесплатно</a>
                    </div>
                    <div class="relative z-[5]">
                        <span class="text-xl text-white">Получите доступ ко всем функциям Intelab на 14 дней</span>
                    </div>
                </div>
                <div class="absolute w-full h-full top-0 left-0 z-[3]">
                    <img src="/images/default/home-promo-01.png" alt="" class="object-cover w-full h-full"/>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
