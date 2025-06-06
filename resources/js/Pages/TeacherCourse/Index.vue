<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import PaginationTable from "@/Components/PaginationTable.vue";
import {useI18n} from 'vue-i18n';
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

const props = defineProps({
    courses: Object
})

const {t} = useI18n()

const breadcrumbs = [
    {label: 'menu.home', url: route('home'), icon: 'fa fa-home'},
    {label: 'pages.myCourses', url: route('teacherCourse.index'), icon: 'fa-solid fa-graduation-cap'},
]
const destroy = ($id) => {
    if (confirm(t('pages.sureDeleteCourse'))) {
        router.delete(route('teacherCourse.destroy', $id), {
            onSuccess: progress => {
                router.visit(route('teacherCourse.index'))
            }
        })
    }
}

const courses = props.courses
</script>

<template>
    <AppLayout title="Создать бесплатный курс">
        <div class="container mx-auto my-8">
            <Breadcrumbs :breadcrumbs="breadcrumbs"/>
            <div class="flex mt-4 bg-white">
                <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg p-3 mt-8">
                    <div class="border-b-2 pb-2 flex items-center justify-between">
                        <h1 class="text-2xl text-main-blue font-medium">{{ t('pages.myCourses') }}</h1>
                        <Link :href="route('teacherCourse.create')"
                              class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 flex items-center">
                            <span>{{ t('pages.createCourses') }}</span>
                            <i class="fa fa-plus ml-2"></i>
                        </Link>
                    </div>
                    <div v-if="courses.total > 0">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 mt-5">
                            <thead class="text-xs text-white uppercase bg-main-blue">
                            <tr>
                                <th scope="col" class="px-6 py-3 border-r rounded-tl-md text-center">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 border-r text-center">
                                    {{ t('pages.image') }}
                                </th>
                                <th scope="col" class="px-6 py-3 border-r">
                                    {{ t('pages.courseName') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-center border-r">
                                    {{ t('pages.price') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-center border-r">
                                    {{ t('pages.status') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-end rounded-tr-md">
                                    {{ t('pages.actions') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="course in courses.data" class="bg-white border-b hover:bg-gray-300">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 text-center whitespace-nowrap border-r">
                                    <span>{{ course.id }}</span>
                                </th>
                                <td class="px-6 py-4 border-r text-center">
                                    <img class="w-14 mx-auto object-cover" :src="course.image">
                                </td>
                                <td class="px-6 py-4 border-r">
                                    <span>{{ course.name }}</span>
                                </td>
                                <td class="px-6 py-4 text-center border-r">
                                    <span v-if="course.old_price > 0 && course.price > 0">{{ course.price }} ₸ <small
                                        class="line-through">{{ course.old_price }} ₸</small></span>
                                    <span v-else-if="course.price > 0">{{ course.price }} ₸</span>
                                    <span v-else
                                          class="bg-green-100 text-green-800 font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{
                                            t('pages.free')
                                        }}</span>
                                </td>
                                <td class="px-6 py-4 text-center border-r">
                            <span v-if="course.status === 1"
                                  class="bg-green-100 text-green-800 font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{
                                    t('pages.active')
                                }}</span>
                                    <span v-else
                                          class="bg-red-100 text-red-800 font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">{{
                                            t('pages.notActive')
                                        }}</span>
                                </td>
                                <td class="px-6 py-4 text-end">
                                    <div class="flex items-center">
                                        <Link :href="route('teacherCourse.statistic', course.id)"
                                              class="font-medium text-main-blue text-sm p-1 rounded-md border border-main-blue mr-2">
                                            <span class="hidden xl:inline xl:mr-2">{{ t('pages.statistics') }}</span>
                                            <i class="fa-solid fa-chart-line"></i>
                                        </Link>
                                        <Link :href="route('teacherCourse.program', course.id)"
                                              class="font-medium text-main-blue text-sm p-1 rounded-md border border-main-blue mr-2">
                                            <span class="hidden xl:inline xl:mr-2">{{ t('pages.courseProgram') }}</span>
                                            <i class="fa fa-layer-group"></i>
                                        </Link>
                                        <Link :href="route('teacherCourse.edit', course.id)"
                                              class="font-medium text-main-blue text-sm p-1 rounded-md border border-main-blue mr-2">
                                            <span class="hidden 2xl:inline 2xl:mr-2">{{ t('pages.edit') }}</span>
                                            <i class="fa fa-pen"></i>
                                        </Link>
                                        <button @click="destroy(course.id)"
                                                class="font-medium text-sm text-red-600 p-1 rounded-md border border-red-600">
                                            <span class="hidden 2xl:inline 2xl:mr-2">{{ t('pages.delete') }}</span>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="text-center text-2xl py-10">
                        <div>
                            <span>{{ t('pages.notCreatedCourses') }}</span>
                        </div>
                        <div class="mt-10">
                            <Link :href="route('teacherCourse.create')"
                                  class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xl px-10 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                <span>{{ t('pages.create') }}</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
