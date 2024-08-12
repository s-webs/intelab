<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Heading from "@/Components/Heading.vue";
import {ref} from "vue";
import {useI18n} from "vue-i18n";
import {useForm} from "@inertiajs/vue3";

const {t} = useI18n();

const props = defineProps({
    profile: Object,
})

const imageSrc = ref(props.profile.photo);

const form = useForm({
    photo: props.profile.photo  ,
    name: props.profile.name,
    gender: props.profile.gender,
    email: props.profile.email,
    phone: props.profile.phone,
    country: props.profile.country,
    city: props.profile.city,
    birthday: props.profile.birthday,
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    previewImage(file);
};

const openCKFinder = () => {
    CKFinder.config({connectorPath: '/ckfinder/connector'});
    CKFinder.modal({
        chooseFiles: true,
        onInit: function (finder) {
            finder.on('files:choose', function (evt) {
                const file = evt.data.files.first();
                form.photo = file.getUrl();
                imageSrc.value = form.photo; // Обновляем ссылку на изображение для предпросмотра
            });
        }
    });
};

const previewImage = (file) => {
    if (!file.type.includes('image/')) {
        alert('Выберите изображение!');
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        imageSrc.value = e.target.result;
        form.photo = e.target.result;
    };
    reader.readAsDataURL(file);
};

function store() {
    form.post(route('profile.update'))
}
</script>

<template>
    <AppLayout>
        <div class="container mx-auto px-2">
            <form @submit.prevent="store" method="post">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                        <Heading title="Профиль" class="mt-8"/>
                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <!-- Image Upload -->
                            <div class="sm:col-span-4">
                                <div class="flex items-center justify-center w-64 mx-auto">
                                    <div
                                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100"
                                        :style="{ backgroundImage: `url(${imageSrc})`, backgroundSize: 'cover' }"
                                        @click.prevent="openCKFinder">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="fa fa-image text-gray-500 mb-4"></i>
                                            <p class="mb-2 text-sm text-gray-500 text-center">
                                                {{ t('pages.imageUploadText') }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                PNG, JPG (MAX. 2MB, 1:1)
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Name -->
                            <div class="sm:col-span-4">
                                <label for="name"
                                       class="block text-sm font-medium leading-6 text-gray-900">Полное имя</label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                        <input v-model="form.name" type="text" name="name" id="name" autocomplete="name"
                                               class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                               placeholder="Введите ваше полное имя" value="">
                                    </div>
                                </div>
                            </div>
                            <!-- Gender -->
                            <div class="sm:col-span-2 sm:col-start-1">
                                <label for="birthday"
                                       class="block text-sm font-medium leading-6 text-gray-900">Дата рождения</label>
                                <div class="mt-2">
                                    <input v-model="form.birthday" type="date" name="birthday" id="birthday"
                                           autocomplete="address-level2"
                                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <label for="gender"
                                       class="block text-sm font-medium leading-6 text-gray-900">Пол</label>
                                <div class="mt-2">
                                    <select v-model="form.gender" id="gender" name="gender" autocomplete="gender"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                        <option value="male">Мужской</option>
                                        <option value="female">Женский</option>
                                    </select>
                                </div>
                            </div>
                            <div class="sm:col-span-4">
                                <label for="email"
                                       class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                                <div class="mt-2">
                                    <input v-model="form.email" id="email" name="email" type="email"
                                           autocomplete="email"
                                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                            <!-- Phone -->
                            <div class="sm:col-span-4">
                                <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Номер
                                    мобильного телефона</label>
                                <div class="mt-2">
                                    <input v-model="form.phone" id="phone" name="phone" type="number"
                                           autocomplete="phone"
                                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                            <!-- Country -->
                            <div class="sm:col-span-3">
                                <label for="country"
                                       class="block text-sm font-medium leading-6 text-gray-900">Страна</label>
                                <div class="mt-2">
                                    <select v-model="form.country" id="country" name="country"
                                            autocomplete="country-name"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                        <option value="kazakhstan">Казахстан</option>
                                        <option value="russia">Россия</option>
                                        <option value="ukraine">Украина</option>
                                        <option value="belarus">Беларусь</option>
                                        <option value="uzbekistan">Узбекистан</option>
                                    </select>
                                </div>
                            </div>
                            <!-- City -->
                            <div class="sm:col-span-2 sm:col-start-1">
                                <label for="city"
                                       class="block text-sm font-medium leading-6 text-gray-900">Город</label>
                                <div class="mt-2">
                                    <input v-model="form.city" type="text" name="city" id="city"
                                           autocomplete="address-level2"
                                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="/" type="button" class="text-sm font-semibold leading-6 text-gray-900">Отмена</a>
                    <button type="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Сохранить
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
