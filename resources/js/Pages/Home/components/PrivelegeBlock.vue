<script setup>
import {ref} from 'vue'
import PrivelegeCard from "@/Pages/Home/components/PrivelegeCard.vue";

const activeTab = ref('authors')
</script>

<template>
    <!-- Кнопки-табы -->
    <div class="flex items-center justify-center mt-[20px]">
        <!-- Таб «Для авторов» -->
        <button
            @click="activeTab = 'authors'"
            :class="[
          'py-[15px] font-semibold w-[200px] text-center relative rounded-[12px] border-[2px]',
          activeTab === 'authors'
            ? 'bg-main-primary text-white border-main-primary'
            : 'bg-white text-main-primary border-main-primary'
        ]"
        >
            <!-- Иконка меняется в зависимости от активного таба -->
            <div class="absolute right-[5px] top-[5px]">
                <img
                    :src="activeTab === 'authors'
              ? '/images/default/click-white.png'
              : '/images/default/click-green.png'"
                    alt="click icon"
                />
            </div>
            <span>Для авторов</span>
        </button>

        <!-- Отступ между кнопками -->
        <div class="w-[30px]"></div>

        <!-- Таб «Для студентов» -->
        <button
            @click="activeTab = 'students'"
            :class="[
          'py-[15px] font-semibold w-[200px] text-center relative rounded-[12px] border-[2px]',
          activeTab === 'students'
            ? 'bg-main-primary text-white border-main-primary'
            : 'bg-white text-main-primary border-main-primary'
        ]"
        >
            <div class="absolute right-[5px] top-[5px]">
                <img
                    :src="activeTab === 'students'
              ? '/images/default/click-white.png'
              : '/images/default/click-green.png'"
                    alt="click icon"
                />
            </div>
            <span>Для студентов</span>
        </button>
    </div>

    <!-- Контент для табов -->
    <div class="mt-[20px]">
        <!-- Секция «Для авторов» -->
        <div v-if="activeTab === 'authors'" class="flex flex-wrap">
            <PrivelegeCard
                :index="1"
                title="Простая загрузка"
                description="Создайте свой курс за считанные минуты, загрузив видео, аудио и текст."
            />
            <PrivelegeCard
                :index="2"
                title="Конструктор уроков"
                description="Интуитивно понятный интерфейс позволяет легко создавать и редактировать."
            />
            <PrivelegeCard
                :index="3"
                title="Автоматическая сертификация"
                description="Автоматически выдавайте сертификаты своим студентам по завершению курса."
            />
            <PrivelegeCard
                :index="4"
                title="Аналитика и статистика"
                description="Отслеживайте продажи, прогресс студентов и получайте ценные аналитические данные."
            />
        </div>

        <!-- Секция «Для студентов» -->
        <div v-else-if="activeTab === 'students'" class="flex flex-wrap">
            <PrivelegeCard
                :index="1"
                title="Доступ к качественным курсам"
                description="Найдите лучшие онлайн-курсы от экспертов в различных областях."
            />
            <PrivelegeCard
                :index="2"
                title="Удобный интерфейс"
                description="Обучайтесь в комфортной и интуитивно понятной среде."
            />
            <PrivelegeCard
                :index="3"
                title="Сертификаты"
                description="Получите сертификаты, подтверждающие ваши знания после завершения курса."
            />
            <PrivelegeCard
                :index="4"
                title="Отслеживание прогресса"
                description="Следите за своим прогрессом обучение и достижениями."
            />
        </div>
    </div>
</template>
