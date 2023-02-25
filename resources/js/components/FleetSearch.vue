<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row m-1 mb-1">
                    <div>
                        <div v-if="alert" class="alert alert-primary">{{ alert }}</div>
                    </div>
                    <div class="row m-0 p-0 gap-2">
                        <upload-form @file-upload-started="showAlert" @file-uploaded="handleFileUpload"></upload-form>
                        <search-form @search="handleSearch"></search-form>
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
                                    <th class="col">Search Completed</th>
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

                                    <td  v-if="searchStat.scrap_status == 'completed'" class="text-center text-success">Yes</td>
                                    <td  v-if="searchStat.scrap_status == 'failed'" class="text-center text-danger">No</td>
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
import {ref, onMounted} from 'vue';
import axios from 'axios';
import UploadForm from "./partials/UploadForm.vue";
import SearchForm from "./partials/SearchForm.vue";

export default {
    name: 'FleetSearch',
    components: {
        UploadForm,
        SearchForm
    },
    setup() {
        let keyword = ref('');
        let searchStats = ref([]);
        let alertText = ref('');
        let fileContent = ref();
        let keywords = ref();
        const pollingIntervalMs = 5000;
        let pollingIntervalId = null;
        let pollingStartTime = null;

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
                alertText.value = "";
            } catch (error) {
                console.error(error);
            }
        };

        const handleSearch = async (query) => {
            try {
                keyword.value = query.value;
                await search(url);
            } catch (error) {
                console.error(error);
            }
        };

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

        const showAlert = async (alertMessage) => {
            alertText.value = alertMessage.value;
        }

        const handleFileUpload = async (fileContent) => {
            try {
                keywords.value = fileContent.value;
                await startPolling();
            } catch (error) {
                console.error(error);
            }
        };

        const startPolling = async () => {
            pollingStartTime = new Date();

            pollingIntervalId = setInterval(async () => {
                try {
                    await search(url);
                    if (ShouldStopPolling()) {
                        stopPolling();
                    }
                } catch (error) {
                    console.error(error);
                }
            }, pollingIntervalMs);
        }

        const stopPolling = () => {
            clearInterval(pollingIntervalId);
            pollingIntervalId = null;
        }

        function ShouldStopPolling() {

            let lastSubset = keywords.value.slice(-searchStats.value.per_page);
            let searchStatKeywords = searchStats.value.data.map(data => data.keyword);

            return lastSubset.every(keyword =>
                    searchStatKeywords.includes(keyword)
                ) && thirtySecondsHasPassedSincelastData(searchStats.value.data[0].updated_at, new Date())
                && thirtySecondsHasPassedSinceInitialPolling();
        }

        function thirtySecondsHasPassedSincelastData(savedDateString, currentDate) {
            let savedDate = new Date(savedDateString);
            let secondsPassed = Math.abs(currentDate.getTime() - savedDate.getTime());
            return secondsPassed > .5 * 60 * 1000;
        }

        function thirtySecondsHasPassedSinceInitialPolling() {
            let currentDate = new Date();
            let secondsPassed = Math.abs(currentDate.getTime() - pollingStartTime.getTime());
            return secondsPassed > .5 * 60 * 1000;
        }

        return {
            url,
            keyword,
            searchStats,
            search,
            fetchResponse,
            alert: alertText,
            handleSearch,
            showAlert,
            handleFileUpload
        };
    },

}
</script>
