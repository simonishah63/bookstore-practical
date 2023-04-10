<template>
    <div class="container">
        <div class="row justify-content-center mt-2 mb-2">
            <div class="col-6">
                <h4 class="text-left mb-2">All books</h4>
            </div>
            <div class="col-4">
                <input
                type="text"
                class="form-control"
                placeholder="Search Books..."
                @input="searchBooks"
                v-model="query.search"
                />
            </div>
            <div class="col-2" v-if="user.role_id == '1'">
                <router-link
                    :to="{ name: 'addBook'}"
                   class="btn btn-primary mr-2"
                    title="Add Book"

                >
                    Add Book
          </router-link>
            </div>
        </div>
        
        <div class="">
            <div class="" v-if="!isLoading">
                <div class="row border-bottom border-top p-2 bg-light">
                    <div class="col-3">Book Title</div>
                    <div class="col-3">Author</div>
                    <div class="col-2">Genre</div>
                    <div class="col-2">isbn</div>
                    <div class="col-2" v-if="user.role_id == '1'">Actions</div>
                </div>

                <div v-for="(item, index) in booksPaginatedData.data" :key="item.id">
                    <detail :index="index" :book="item" :user="user" :query="query"/>
                </div>
            </div>
            <div v-if="isLoading" class="text-center mt-5 mb-5">
                Loading Books...
                <div class="spinner-grow" role="status">
                </div>
            </div>
        </div>

        <div v-if="booksPaginatedData !== null" class="vertical-center mt-2 mb-5">
            <v-pagination
                v-model="query.page"
                :pages="booksPaginatedData.pagination.total_pages"
                :range-size="2"
                active-color="#DCEDFF"
                @update:modelValue="getResults"
            />
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import VPagination from "@hennge/vue3-pagination";

import Detail from "./Detail.vue";
export default {
    data() {
        return {
            query: {
                page: 1,
                search: "",
            },
            user:this.$store.state.auth.user
        };
    },
    components: {
        Detail,
        VPagination,
    },
    computed: { ...mapGetters(["bookList", "booksPaginatedData", "isLoading"]) },
    methods: {
        ...mapActions(["fetchAllBooks"]),
        getResults() {
            this.fetchAllBooks(this.query);
        },
        searchBooks() {
            this.fetchAllBooks(this.query);
        },
    },
    created() {
        this.fetchAllBooks(this.query);
    },
};
</script>