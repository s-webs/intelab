<script setup>
import {onMounted, ref, watch} from 'vue';

const content = ref('');
let ckEditor;

const props = defineProps({
    initialContent: String
});

const emit = defineEmits(['sendContent']);

onMounted(() => {
    ckEditor = window.CKEDITOR.replace('editor', {
        toolbarGroups: [
            {name: 'document', groups: ['mode', 'find']},
            {name: 'styles', groups: ['styles']},
            {name: 'clipboard', groups: ['undo', 'align', 'clipboard']},
            {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
            {name: 'editing', groups: ['selection', 'editing']},
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'paragraph']},
            {name: 'links', groups: ['links']},
            {name: 'insert', groups: ['insert', 'video', 'youtube']},
            {name: 'colors', groups: ['colors']},
            {name: 'tools', groups: ['tools']},
            {name: 'others', groups: ['others']},
            {name: 'about', groups: ['about']}
        ],
        clipboard_handleImages: false,
        filebrowserBrowseUrl: '/ckfinder/browser',
        filebrowserImageBrowseUrl: '/ckfinder/browser?type=Images',
        filebrowserUploadUrl: '/ckfinder/connector?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: '/ckfinder/connector?command=QuickUpload&type=Images',
        extraPlugins: [
            'video',
            'youtube',
        ],
        removePlugins: [
            'exportpdf'
        ],
        removeButtons: 'Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,ExportPdf,NewPage,Save,Templates,Scayt,Language,BidiLtr,BidiRtl'
    })
    ckEditor.setData(props.initialContent)
    ckEditor.on('change', () => {
        emit('sendContent', ckEditor.getData())
    })
    watch(() => props.initialContent, (newValue) => {
        if (ckEditor && newValue !== ckEditor.getData()) {
            ckEditor.setData(newValue);
        }
    }, { immediate: true });

})
</script>

<template>
    <textarea id="name" name="editor"></textarea>
</template>

<style>
.ck-content {
    height: 500px !important;
}
</style>
