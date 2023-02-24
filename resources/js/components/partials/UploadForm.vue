<template>
    <div class="card col-md-5 mb-2 ">
        <div class="card-header">
            Upload a CSV file. Maximum 100 keywords allowed.
        </div>
        <div class="card-body">
            <form @submit.prevent="submitForm" class="mb-3">
                <div class="input-group mb-3">
                    <input type="file" id="csvFile" class="form-control" accept=".csv"
                           @change="onFileChange"/>
                    <button type="submit" :disabled="!canSubmit" class="btn btn-primary">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import {ref, computed} from 'vue';
import axios from 'axios';

export default {
    name: 'UploadForm',
    emits: ['file-uploaded', 'file-upload-started'],
    setup(_, {emit}) {
        let fileInput = ref(null);
        let alert = ref('');

        function submitForm() {
            alert.value = "Please wait while we Fetch your results. You will see uploaded keywords once search for all keywords are complete";
            emit('file-upload-started', alert);

            const formData = new FormData();
            formData.append('keywords', fileInput.value);
            axios.post('/keywords', formData)
                .then(response => {
                    emit('file-uploaded');
                })
                .catch(error => {
                    alert.value = error.response.data.error;
                });
        }

        const canSubmit = computed(() => {
            return fileInput.value;
        });

        function onFileChange(event) {
            fileInput.value = event.target.files[0];
        }

        return {
            submitForm,
            canSubmit,
            onFileChange,
            fileInput,
            alert
        };
    },

}
</script>
