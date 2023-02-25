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
        let fileContent = ref();

        function submitForm() {
            alert.value = "Please wait while we Fetch your results. You will see uploaded keywords once search for all keywords are complete";
            emit('file-upload-started', alert);

            readCsvContent(fileInput.value);
            const formData = new FormData();
            formData.append('keywords', fileInput.value);
            axios.post('/keywords', formData)
                .then(response => {
                    emit('file-uploaded', fileContent);
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

        function readCsvContent(file) {
            const reader = new FileReader()

            reader.onload = () => {
                const rows = reader.result.split('\n');
                const headers = rows[0].split(',');
                const csvData = [];

                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i].split(',');

                    for (let j = 0; j < headers.length; j++) {
                        if (row[j]) {
                            csvData.push(row[j]);
                        }
                    }


                }

                fileContent.value = csvData;
            }
            reader.readAsText(file);
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
