<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {Link, router} from "@inertiajs/vue3";

const props = defineProps({
    courses: Array
})
</script>

<template>
    <AppLayout>
        <div class="container mx-auto p-4 mt-4">
            <div class="mt-[50px]">
                <div class="mb-[30px]">
                    <Link :href="route('admin.courses')"
                          class="border px-4 py-4 block text-center border-main-primary text-main-primary font-semibold hover:bg-main-primary hover:text-white transition-all duration-300">
                        Список курсов
                    </Link>
                </div>
                <div>
                    <Link :href="route('admin.users')"
                          class="border px-4 py-4 block text-center border-main-primary text-main-primary font-semibold hover:bg-main-primary hover:text-white transition-all duration-300">
                        Список пользователей
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
