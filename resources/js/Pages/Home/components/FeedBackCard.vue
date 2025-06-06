<script setup lang="ts">
import {ref, computed} from 'vue'

const reviews = [
    {
        name: 'Асель Алипова',
        avatar: '/images/default/no_avatar.webp',
        text: 'Учусь третий год — отличные преподаватели и современные программы. Университет даёт реальный опыт и готовит к будущей карьере!'
    },
    {
        name: 'Нурсултан Байкенов',
        avatar: '/images/default/no_avatar.webp',
        text: 'Очень доволен учебным процессом и атмосферой. Лекции интересные, а практика — на высоком уровне.'
    },
    {
        name: 'Жанет Абдуллаева',
        avatar: '/images/default/no_avatar.webp',
        text: 'Преподаватели всегда готовы помочь, а техническая база университета позволяет усваивать материал быстро и эффективно.'
    },
    {
        name: 'Данияр Турсунов',
        avatar: '/images/default/no_avatar.webp',
        text: 'Университет открыл мне много возможностей для стажировок и проектов. Очень рад, что выбрал именно его!'
    }
]

// Текущий индекс активного отзыва:
const currentIndex = ref(0)

// Вычисляемый объект — текущий отзыв:
const currentReview = computed(() => reviews[currentIndex.value])

// Перейти к предыдущему отзыву (если дошли до начала — переходим на последний):
function prevSlide() {
    if (currentIndex.value > 0) {
        currentIndex.value--
    } else {
        currentIndex.value = reviews.length - 1
    }
}

// Перейти к следующему отзыву (если дошли до конца — переходим на первый):
function nextSlide() {
    if (currentIndex.value < reviews.length - 1) {
        currentIndex.value++
    } else {
        currentIndex.value = 0
    }
}

// Перейти на конкретный слайд по точке:
function goToSlide(idx: number) {
    currentIndex.value = idx
}
</script>

<template>
    <div
        class="bg-white w-full lg:w-[48%] p-[30px] rounded-[15px] mb-[20px] lg:mb-0 last:mb-0 relative overflow-hidden"
    >
        <!-- Основной контейнер отзыва -->
        <div class="flex items-center">
            <div class="mr-[10px]">
                <img
                    :src="currentReview.avatar"
                    alt="avatar"
                    class="object-contain w-[50px] h-[50px] rounded-full"
                />
            </div>
            <div class="text-main-primary font-semibold text-lg">
                {{ currentReview.name }}
            </div>
        </div>

        <div class="mt-[30px] text-main-primary text-lg">
            {{ currentReview.text }}
        </div>

        <!-- Нумерация (точки) и стрелки -->
        <div class="w-full mt-[30px] flex items-center justify-between">
            <!-- Точки-пагинация -->
            <div class="flex items-center">
                <template v-for="(review, idx) in reviews" :key="idx">
                    <div
                        class="cursor-pointer mr-[5px] transition-all"
                        :class="{
              'w-[15px] h-[7px] rounded-full bg-main-primary': idx === currentIndex,
              'w-[7px] h-[7px] rounded-full bg-main-primary opacity-50': idx !== currentIndex
            }"
                        @click="goToSlide(idx)"
                    ></div>
                </template>
            </div>

            <!-- Стрелки -->
            <div class="flex items-center">
                <button
                    class="w-[50px] h-[50px] bg-main-primary flex items-center justify-center rounded-full mr-[15px] hover:opacity-90 transition-opacity"
                    @click="prevSlide"
                >
                    <img src="/images/default/caret-left.png" alt="left"/>
                </button>
                <button
                    class="w-[50px] h-[50px] bg-main-primary flex items-center justify-center rounded-full hover:opacity-90 transition-opacity"
                    @click="nextSlide"
                >
                    <img src="/images/default/caret-right.png" alt="right"/>
                </button>
            </div>
        </div>
    </div>
</template>
