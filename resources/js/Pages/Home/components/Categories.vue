<script setup>
import {Swiper, SwiperSlide} from "swiper/vue";
import "swiper/css";
import 'swiper/css/navigation';
import {Navigation} from 'swiper/modules';
import {computed, defineProps} from 'vue';

const props = defineProps({
    categories: Array
})

const filteredCategories = computed(() => {
    return props.categories.filter(category => category.parent_id !== null);
});
</script>

<template>
    <div class="flex gap-2 overflow-hidden">
        <swiper class="mt-4"
                :slides-per-view="3.5"
                :space-between="20"
                :loop="true"
                :navigation="true"
                :modules="[Navigation]"
        >
            <swiper-slide v-for="slide in filteredCategories" :key="slide.id">
                <a href="#" class="block border p-6 rounded-lg" :style="{ backgroundColor: slide.background_color}">
                    <div class="mb-2 font-medium text-lg h-16" :style="{ color: slide.text_color}">
                        {{ slide.name }}
                    </div>
                    <div class="mb-2" :style="{ color: slide.text_color}">-</div>
                </a>
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
