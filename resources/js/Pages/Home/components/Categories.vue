<script setup>
import {Swiper, SwiperSlide} from "swiper/vue";
import "swiper/css";
import 'swiper/css/navigation';
import {Navigation} from 'swiper/modules';
import {computed, defineProps, ref} from 'vue';
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    categories: Array
})

const currentLanguage = ref(localStorage.getItem('language') || 'en');

const filteredCategories = computed(() => {
    return props.categories
        .filter(category => category.parent_id !== null)
        .sort((a, b) => b.courses_count - a.courses_count);
});
</script>

<template>
    <div class="flex gap-2 overflow-hidden">
        <swiper class="mt-4"
                :space-between="20"
                :loop="true"
                :navigation="true"
                :modules="[Navigation]"
                :breakpoints="{
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 10
                    },
                    480: {
                        slidesPerView: 1,
                        spaceBetween: 10
                    },
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 10
                    },
                    768: {
                        slidesPerView: 2.5,
                        spaceBetween: 20
                    },
                    1024: {
                        slidesPerView: 4.5,
                        spaceBetween: 20
                    }
                }"
        >
            <swiper-slide v-for="slide in filteredCategories" :key="slide.id">
                <Link :href="route('categoryCourses', slide.id)" class="block border p-6 rounded-lg"
                      :style="{ backgroundColor: slide.background_color}">
                    <div class="mb-2 font-medium text-lg h-16" :style="{ color: slide.text_color}">
                        <span v-if="currentLanguage === 'kz'">{{ slide.name_kz }}</span>
                        <span v-else-if="currentLanguage === 'en'">{{ slide.name_en }}</span>
                        <span v-else>{{ slide.name }}</span>
                    </div>
                    <div class="mb-2" :style="{ color: slide.text_color}">
                        Количество курсов: <span>{{ slide.courses_count }}</span>
                    </div>
                </Link>
            </swiper-slide>
        </swiper>
    </div>
</template>

<style lang="scss">
.swiper-button-prev, .swiper-button-next {
    background-color: #fff;
    border-radius: 100%;
    width: 35px;
    height: 35px;
    box-shadow: 1px 2px 5px -1px rgba(0, 0, 0, 0.75);

    &:after {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 13px;
        color: #1b1b1b;
    }
}
</style>
