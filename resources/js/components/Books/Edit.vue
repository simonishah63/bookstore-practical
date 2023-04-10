<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Book</h4>
                </div>
                <div class="card-body">
                    <form @submit.prevent="onSubmit">
                        <div v-if="isLoading">
                            <div class="text-center">
                                <div
                                    class="spinner-border text-primary"
                                    role="status"
                                >
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <br>
                                Loading Book Details
                            </div>
                        </div>
                        <div v-if="book !== null && !isLoading">
                            <div class="row">
                                <div class="col-12" v-if="validationErrors && !validationErrors.errors">
                                    <div class="alert alert-danger">
                                        {{validationErrors.message}}
                                    </div>
                                </div>
                                <div class="col-12" v-if="validationErrors && validationErrors.errors">
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            <li v-for="(value, key) in validationErrors.errors" :key="key">{{ value[0] }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" v-model="book.title">
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label>Author</label>
                                        <input type="text" class="form-control" v-model="book.author">
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <input type="text" class="form-control" v-model="book.description">
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label>Genre</label>
                                        <input type="text" class="form-control" v-model="book.genre">
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label>Isbn</label>
                                        <input type="number" class="form-control" v-model="book.isbn">
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label>Published At</label>
                                        <datepicker
                                            v-model="book.published_at"
                                            format="yyyy-MM-dd"
                                            class="form-input form-control mb-2"
                                        />
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label>Publisher</label>
                                        <input type="text" class="form-control" v-model="book.publisher">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <router-link :to='{name:"listBook"}' class="btn btn-secondary mr-2">
                                        Cancel
                                    </router-link>
                                    <input v-if="!isUpdating"
                                        type="submit"
                                        class="btn btn-primary mx-2 my-2"
                                        value="Save">
                                    <button
                                        v-if="isUpdating"
                                        class="btn btn-primary mx-2 my-2"
                                        type="button"
                                        disabled>
                                        <span
                                            class="spinner-border spinner-border-sm"
                                            role="status"
                                            aria-hidden="true"
                                        /> Saving...
                                    </button>
                                </div>
                            </div>  
                        </div>                      
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import Datepicker from 'vue3-datepicker';
export default {

    components: {
        Datepicker
    },
    data() {
        return {
            id: null,
            validationErrors: null,
        };
    },

    computed: {
        ...mapGetters(["isUpdating", "updatedData", "book", "isLoading"]),
    },

    watch: {
        updatedData: function () {
            if (this.updatedData !== null && !this.isUpdating) {
                this.$swal.fire({
                    text: "Success, Book has been updated successfully !",
                    icon: "success",
                    position: "center",
                    timer: 1000,
                });

                this.$router.push({ name: "listBook" });
            }
        },
    },

    created: function () {
        this.id = this.$route.params.id;
        this.fetchDetailBook(this.id);
    },

    methods: {
        ...mapActions([
            "updateBook",
            "updateBookInput",
            "fetchDetailBook",
        ]),
    async onSubmit() {
        try {
            await this.updateBook(this.book);
        }
        catch (e){
            this.validationErrors = e.data;
        };
    },
    updateBookInputAction(e) {
        this.updateBookInput(e);
    },
  },
};
</script>