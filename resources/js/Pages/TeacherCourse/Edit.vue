<script setup>
import {computed, ref, watch} from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import {Link, useForm} from "@inertiajs/vue3";
import CKeditor from "@/Components/CKeditor.vue";
import {useI18n} from "vue-i18n";
import Instructions from "@/Pages/TeacherCourse/components/Instructions.vue";

const {t, locale} = useI18n();
const currentLanguage = ref(locale.value);

watch(locale, (newLang) => {
    currentLanguage.value = newLang;
});

const props = defineProps({
    course: Object,
    categories: Array,
});

const form = useForm({
    image: props.course.image,
    language: props.course.language,
    name: props.course.name,
    price: props.course.price,
    old_price: props.course.old_price,
    description: props.course.description,
    html: props.course.content,
    duration: props.course.duration,
    status: props.course.status,
    type: props.course.type,
    category: props.course.category_id,
    hidden: props.course.hidden,
});

const imageSrc = ref(props.course.image);

const getContent = (val) => {
    form.content = val;
};

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
                form.image = file.getUrl();
                imageSrc.value = form.image;
            });
        }
    });
};

const previewImage = (file) => {
    if (!file.type.includes('image/')) {
        alert(t('pages.warnChooseImage'));
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        imageSrc.value = e.target.result;
        form.image = e.target.result;
        console.log(e.target.result);
    };
    reader.readAsDataURL(file);
};

const parentCategories = computed(() => {
    const categories = props.categories;

    const groupedByParentId = categories.reduce((acc, category) => {
        if (category.parent_id !== null) {
            if (!acc[category.parent_id]) {
                acc[category.parent_id] = [];
            }
            acc[category.parent_id].push(category);
        }
        return acc;
    }, {});

    return categories
        .filter(category => category.parent_id === null)
        .map(parentCategory => ({
            ...parentCategory,
            subcategories: groupedByParentId[parentCategory.id] || [],
        }));
});

const toggleType = (event) => {
    form.type = event.target.checked ? 'premium' : 'free';
    form.price = 0;
    form.old_price = 0;
};

const toggleHidden = (event) => {
    form.hidden = event.target.checked ? 1 : 0;
};

const toggleStatus = (event) => {
    form.status = event.target.checked ? 1 : 0;
};

function store() {
    form.put(route('teacherCourse.update', props.course.id));
}
</script>

<template>
    <AppLayout>
        <div class="container mx-auto grid grid-cols-1 lg:grid-cols-3 mt-8">
            <form @submit.prevent="store" class="block shadow-md p-5 rounded-lg border col-span-1 lg:col-span-2">
                <div class="space-y-12">
                    <h1 class="border-b-2 border-main-blue pb-2 text-2xl font-medium text-main-blue">
                        {{ t('pages.editCourse') }}
                    </h1>
                    <div class="flex items-center justify-center w-64 mx-auto">
                        <div
                            class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100"
                            :style="{ backgroundImage: `url(${imageSrc})`, backgroundSize: 'cover' }"
                            @click.prevent="openCKFinder">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fa fa-image text-gray-500 mb-4"></i>
                                <p class="mb-2 text-sm text-gray-500 text-center">
                                    <span class="font-semibold">{{ t('pages.imageUploadText') }}</span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG (MAX. 2MB, 1:1)</p>
                            </div>
                        </div>
                    </div>
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-12">
                                <label for="name" class="block text-sm font-medium leading-6 text-gray-900">
                                    {{ t('pages.courseName') }}
                                </label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600">
                                        <input v-model="form.name" type="text" name="name" id="name"
                                               class=" block flex-1 border-0 bg-transparent py-1.5 pl-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                               :placeholder="t('pages.enterCourseName')"/>
                                    </div>
                                </div>
                            </div>
                            <div class="sm:col-span-12">
                                <label for="category" class="block text-sm font-medium leading-6 text-gray-900">
                                    {{ t('pages.category') }}
                                </label>
                                <div class="mt-2">
                                    <select v-model="form.category" id="category" name="category"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            required>
                                        <template v-for="category in parentCategories" :key="category.id">
                                            <optgroup v-if="currentLanguage === 'en'" :label="category.name_en">
                                                <option v-for="subCategory in category.subcategories"
                                                        :key="subCategory.id" :value="subCategory.id">
                                                    {{ subCategory.name_en }}
                                                </option>
                                            </optgroup>
                                            <optgroup v-else-if="currentLanguage === 'kz'" :label="category.name_kz">
                                                <option v-for="subCategory in category.subcategories"
                                                        :key="subCategory.id" :value="subCategory.id">
                                                    {{ subCategory.name_kz }}
                                                </option>
                                            </optgroup>
                                            <optgroup v-else :label="category.name">
                                                <option v-for="subCategory in category.subcategories"
                                                        :key="subCategory.id" :value="subCategory.id">
                                                    {{ subCategory.name }}
                                                </option>
                                            </optgroup>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            <div class="sm:col-span-12">
                                <label for="language" class="block text-sm font-medium leading-6 text-gray-900">
                                    {{ t('pages.courseLang') }}
                                </label>
                                <div class="mt-2">
                                    <select v-model="form.language" id="language" name="language"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option>Русский</option>
                                        <option>Қазақша</option>
                                        <option>English</option>
                                    </select>
                                </div>
                            </div>
                            <div class="sm:col-span-12">
                                <label for="description" class="block text-sm font-medium leading-6 text-gray-900">
                                    {{ t('pages.courseShortDescrip') }}
                                </label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600">
                                        <textarea v-model="form.description" type="text" name="description"
                                                  id="description"
                                                  class=" block h-24 flex-1 border-0 bg-transparent py-1.5 pl-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 resize-none"
                                                  :placeholder="t('pages.enterCourseShortDescrip')"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="sm:col-span-12">
                                <label for="content" class="block text-sm font-medium leading-6 text-gray-900">
                                    {{ t('pages.fullCourseDescrip') }}
                                </label>
                                <div class="mt-2">
                                    <CKeditor :initialContent="course.content" @sendContent="getContent"/>
                                </div>
                            </div>
                            <div class="sm:col-span-12">
                                <label for="duration" class="block text-sm font-medium leading-6 text-gray-900">
                                    {{ t('pages.courseDuration') }}
                                </label>
                                <div class="mt-2">
                                    <div
                                        class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600">
                                        <input v-model="form.duration" type="number" name="duration" id="duration"
                                               class=" block flex-1 border-0 bg-transparent py-1.5 pl-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                               :placeholder="t('pages.enterCourseDuration')"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 py-3 border-b">
                            <span>{{ t('pages.courseSettings') }}</span>
                        </div>
                        <div class="mt-5">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" :checked="form.status" @change="toggleStatus">
                                <div
                                    class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span
                                    class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-500">{{
                                        t('pages.startCourse')
                                    }}</span>
                            </label>
                        </div>
                        <div class="mt-5">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" :checked="form.hidden" @change="toggleHidden">
                                <div
                                    class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span
                                    class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-500">{{
                                        t('pages.courseHiddenSwitch')
                                    }}</span>
                            </label>
                        </div>
                        <div class="mt-5">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" :checked="form.type === 'premium'"
                                       @change="toggleType">
                                <div
                                    class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span
                                    class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-500">{{
                                        t('pages.paidCourseSwitch')
                                    }}</span>
                            </label>
                        </div>
                        <div v-if="form.type === 'premium'"
                             class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-2 sm:col-start-1">
                                <label for="price" class="block text-sm font-medium leading-6 text-gray-900">
                                    {{ t('pages.courseCost') }}
                                </label>
                                <div class="mt-2">
                                    <input v-model="form.price" type="number" name="price" id="price"
                                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                           min="0">
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="old_price" class="block text-sm font-medium leading-6 text-gray-900">
                                    {{ t('pages.oldCourseCost') }}
                                </label>
                                <div class="mt-2">
                                    <input v-model="form.old_price" type="number" name="old_price" id="old_price"
                                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                           min="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <Link :href="route('teacherCourse.index')" type="button"
                              class="text-sm font-semibold leading-6 text-gray-900">{{
                                t('pages.back')
                            }}
                        </Link>
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            {{ t('pages.save') }}
                        </button>
                    </div>
                </div>
            </form>
            <div class="col-start-1 lg:col-span-1 mt-5 lg:mt-0 lg:ml-3">
                <Instructions/>
            </div>
        </div>
    </AppLayout>
</template>
