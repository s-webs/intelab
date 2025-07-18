<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {router} from "@inertiajs/vue3";
import {ref, watch} from "vue";

const props = defineProps({
    users: Object,
    filters: Object,
})

const search = ref(props.filters.search || '');
const userList = ref([...props.users.data]); // Список пользователей
const nextPageUrl = ref(props.users.next_page_url); // Ссылка на следующую страницу

// Удаление
const deleteUser = (id) => {
    if (confirm("Вы уверены, что хотите удалить пользователя?")) {
        router.delete(route('admin.users.destroy', {id}), {
            onSuccess: () => {
                userList.value = userList.value.filter(u => u.id !== id);
            }
        });
    }
};

// Поиск (debounce)
let timeout = null;
watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get('/admin/users', {search: value}, {
            preserveState: true,
            replace: true,
            onSuccess: (page) => {
                userList.value = page.props.users.data;
                nextPageUrl.value = page.props.users.next_page_url;
            }
        });
    }, 400);
});

// Загрузить ещё
const loadMore = () => {
    if (!nextPageUrl.value) return;

    router.get(nextPageUrl.value, {}, {
        preserveScroll: true,
        preserveState: true,
        only: ['users'],
        onSuccess: (page) => {
            userList.value = [...userList.value, ...page.props.users.data];
            nextPageUrl.value = page.props.users.next_page_url;
        }
    });
}
</script>

<template>
    <AppLayout>
        <div class="container mx-auto p-4 mt-4">
            <h1 class="text-xl font-bold mb-6">Список пользователей</h1>

            <!-- Поиск -->
            <input
                v-model="search"
                type="text"
                placeholder="Поиск по имени или email"
                class="w-full max-w-md mb-6 px-4 py-2 border rounded"
            />

            <!-- Карточки -->
            <div class="flex flex-wrap -mx-2 ">
                <div
                    v-for="user in userList"
                    :key="user.id"
                    class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4"
                >
                    <div class="border rounded-xl p-4 shadow-sm hover:shadow-md transition bg-white">
                        <div class="text-lg font-semibold">{{ user.name }}</div>
                        <div class="text-gray-600 break-all">{{ user.email }}</div>

                        <button
                            @click="deleteUser(user.id)"
                            class="mt-4 text-sm text-red-600 hover:underline"
                        >
                            Удалить
                        </button>
                    </div>
                </div>
            </div>

            <!-- Кнопка "Загрузить ещё" -->
            <div v-if="nextPageUrl" class="mt-6 text-center">
                <button
                    @click="loadMore"
                    class="px-6 py-2 bg-main-primary text-white rounded "
                >
                    Загрузить ещё
                </button>
            </div>
        </div>
    </AppLayout>
</template>
