<template>
    <div class="card col-md-5 mb-2 ">
        <div class="card-header">
            Filters
        </div>
        <div class="card-body">
            <div class="input-group">
                <input type="text" v-model="keyword" class="form-control"
                       placeholder="Enter a keyword"
                       aria-label="Enter a keyword" aria-describedby="button-search"
                       @keyup.enter="search"/>
                <button @click="search" class="btn btn-primary" type="button"
                        id="button-search">
                    Search
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {ref} from 'vue';

export default {
    name: 'SearchForm',
    emits: ['search'],
    setup(_, {emit}) {
        let keyword = ref('');

        const search = async () => {
            emit('search', keyword)
        };

        return {
            keyword,
            search
        };
    }
}
</script>
