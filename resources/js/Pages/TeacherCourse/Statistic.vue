<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {Link} from "@inertiajs/vue3";
import {useI18n} from 'vue-i18n';
import CourseCompletionChart from "@/Components/CourseCompletionChart.vue";
import PieChart from "@/Components/PieChart.vue";
import LineChart from "@/Components/LineChart.vue";

const {t} = useI18n()

const props = defineProps({
    course: Object,
    totalStudents: Number,
    completedStudents: Number,
    progressData: Object,
    students: Array,
})

const paginate = (url) => {
    router.visit(url, {preserveState: true});
};

// Данные для графика
const chartData = {
    labels: ['Завершили курс', 'Не завершили курс'],
    data: [props.completedStudents, props.totalStudents - props.completedStudents],
    colors: ['rgba(55,179,30,0.5)', 'rgba(209,202,202,0.5)'],
    borderColors: ['rgba(26,97,11,0.5)', 'rgba(147,143,143,0.5)'],
};
</script>

<template>
    <AppLayout title="Статистика курса">
        <div class="container mx-auto">
            <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg p-3 mt-8">
                <div class="border-b-2 pb-2 flex items-center justify-between">
                    <h1 class="text-2xl text-main-blue font-medium">{{ course.name }}</h1>
                </div>
                <div class="mt-4">
                    <h2 class="text-lg font-semibold mb-4 text-center">Графики завершения курса и прогресса</h2>
                    <div class="flex justify-between flex-wrap lg:flex-nowrap">
                        <PieChart
                            class="w-full lg:w-auto"
                            :labels="chartData.labels"
                            :data="chartData.data"
                            :colors="chartData.colors"
                            :borderColors="chartData.borderColors"
                        />
                        <LineChart
                            class="w-full"
                            :labels="progressData.labels"
                            :datasets="progressData.datasets"
                        />
                    </div>
                </div>
                <div class="mt-4 border p-4">
                    <h2 class="text-lg font-semibold mb-8 text-center border-b">Статистика</h2>
                    <div>
                        <div class="text-md font-semibold mb-4 border flex justify-between px-2">
                            <span>Общее количество на курсе:</span><span>{{ totalStudents }}</span>
                        </div>
                        <div class="text-md font-semibold border flex justify-between px-2">
                            <span>Завершили курс:</span><span>{{ completedStudents }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 border p-4 overflow-x-scroll">
                    <h2 class="text-lg font-semibold mb-8 text-center border-b">Список обучающихся</h2>

                    <table class="min-w-full bg-white border">
                        <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID</th>
                            <th class="py-2 px-4 border-b text-start">Имя</th>
                            <th class="py-2 px-4 border-b text-start">Email</th>
                            <th class="py-2 px-4 border-b text-start">Прогресс</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="student in students.data" :key="student.id">
                            <td class="py-2 px-4 border-b text-center">{{ student.id }}</td>
                            <td class="py-2 px-4 border-b">{{ student.name }}</td>
                            <td class="py-2 px-4 border-b">{{ student.email }}</td>
                            <td class="py-2 px-4 border-b">{{ student.pivot.progress }}%</td>
                        </tr>
                        </tbody>
                    </table>

                    <!-- Пагинация -->
                    <div class="flex justify-between items-center mt-4">
                        <button
                            v-if="students.prev_page_url"
                            @click="paginate(students.prev_page_url)"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md"
                        >
                            Назад
                        </button>
                        <span v-else class="px-4 py-2"></span>

                        <span class="text-gray-600">
                        Страница {{ students.current_page }} из {{ students.last_page }}
                    </span>

                        <button
                            v-if="students.next_page_url"
                            @click="paginate(students.next_page_url)"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md"
                        >
                            Вперед
                        </button>
                        <span v-else class="px-4 py-2"></span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
