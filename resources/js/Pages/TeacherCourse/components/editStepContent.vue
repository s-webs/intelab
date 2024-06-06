<script setup>

import CKeditor from "@/Components/CKeditor.vue";
import {ref} from "vue";
import {useI18n} from "vue-i18n";

const {t, locale} = useI18n()
const props = defineProps({
    editingContent: Object
})

const emit = defineEmits(['close', 'update', 'delete']);
const content = ref(props.editingContent.content)

const getContent = (val) => {
    content.value = val
}

const updateStep = () => {
    emit('update', content.value)
    close()
}

const stepDelete = () => {
    emit('delete', content.value)
    close()
}

const close = () => {
    emit('close', props.editingContent.id);
};
</script>

<template>
    <div id="crud-modal" tabindex="-1" aria-hidden="true"
         class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] min-h-screen bg-gray-800 bg-opacity-75">
        <div class="relative p-4 w-full xl:w-1/2  max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ t('pages.edit') }}
                    </h3>
                    <button @click="close"
                            type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                            data-modal-toggle="crud-modal">
                        <i class="text-2xl fa fa-xmark"></i>
                        <span class="sr-only">{{ t('pages.closeWindow') }}</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form @submit.prevent="updateStep" class="p-4 md:p-5">
                    <CKeditor
                        @sendContent="getContent"
                        :initialContent="editingContent.content.content"
                    />
                    <button type="submit"
                            class="text-white inline-flex items-center bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mt-4">
                        {{ t('pages.save') }}
                        <i class="fa fa-save ml-2"></i>
                    </button>

                    <button @click="stepDelete"
                            type="submit"
                            class="text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mt-4 ml-2">
                        {{ t('pages.delete') }}
                        <i class="fa fa-trash ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
