<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row m-1 mb-1">
                    <div>
                        <div v-if="alert" class="alert alert-primary">{{ alert }}</div>
                    </div>
                    <div class="row m-0 p-0 gap-2">
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
                        <div class="card col-md-5 mb-2 ">
                            <div class="card-header">
                                Filters
                            </div>
                            <div class="card-body">
                                <div class="input-group">
                                    <input type="text" v-model="keyword" class="form-control"
                                           placeholder="Enter a keyword"
                                           aria-label="Enter a keyword" aria-describedby="button-search"
                                           @keyup.enter="search(url)"/>
                                    <button @click="search(url)" class="btn btn-primary" type="button"
                                            id="button-search">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-1">
                    <div class="card my-2">
                        <div class="card-header">Search Stats</div>
                        <div class="card-body table-responsive-sm">
                            <table class="table ">
                                <thead>
                                <tr>
                                    <th class="col-date">Searched On</th>
                                    <th class="col-keyword">Keyword</th>
                                    <th class="col text-center">Total Ads</th>
                                    <th class="col text-center">Total Links</th>
                                    <th class="col text-center">Total Search Results</th>
                                    <th class="col">Raw View</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="searchStat in searchStats.data" :key="searchStat.id">
                                    <td>{{ searchStat.created_at }}</td>
                                    <td>{{ searchStat.keyword }}</td>
                                    <td class="text-center">{{ searchStat.ads_count }}</td>
                                    <td class="text-center">{{ searchStat.links_count }}</td>
                                    <td class="text-center">{{ searchStat.total_result_count }}</td>
                                    <!--                                <td>{{ searchStat.raw_response }}</td>-->
                                    <td>
                                        <button @click=fetchResponse(searchStat.id) type="button"
                                                class="btn btn-link btn-sm">Download
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <nav aria-label="Page navigation" class="overflow-auto">
                                <ul class="pagination justify-content-start flex-wrap">
                                    <li class="page-item m-1" v-for="page in searchStats.links" :key="page.label"
                                        :class="{active: page.active}">
                                        <a class="page-link" @click.prevent="search(page.url)" href="#">
                                            <span v-html="page.label"></span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {ref, onMounted, computed} from 'vue';
import axios from 'axios';

export default {
    name: 'FleetSearch',
    setup() {
        let keyword = ref('');
        let searchStats = ref([]);
        let fileInput = ref(null);
        let alert = ref('');
        const url = '/search-stats';

        onMounted(() => {
            search(url);
        });

        const search = async (url) => {
            try {
                const response = await axios.get(url, {
                    params: {keyword: keyword.value}
                });
                searchStats.value = response.data;
                alert.value = "";
            } catch (error) {
                console.error(error);
            }
        };

        function submitForm() {
            alert.value = "Please wait while we Fetch your results. You will see uploaded keywords once search for all keywords are complete";
            const formData = new FormData();
            formData.append('keywords', fileInput.value);
            axios.post('/keywords', formData)
                .then(response => {
                    search(url);
                })
                .catch(error => {
                    // Handle any errors
                    console.error(error);
                });
        }

        const canSubmit = computed(() => {
            return fileInput.value;
        });

        function onFileChange(event) {
            fileInput.value = event.target.files[0];
        }

        const fetchResponse = async (id) => {
            try {
                const response = await axios.get(`/raw-response/${id}`, {
                    responseType: 'blob'
                });
                const data = response.data;
                const fileUrl = window.URL.createObjectURL(new Blob([data]));
                const link = document.createElement('a');
                link.href = fileUrl;
                link.setAttribute('download', 'response.html');
                document.body.appendChild(link);
                link.click();
            } catch (error) {
                console.error(error);
            }
        };


        return {
            url,
            keyword,
            searchStats,
            search,
            submitForm,
            canSubmit,
            onFileChange,
            fileInput,
            fetchResponse,
            alert
        };
    },

}
</script>
