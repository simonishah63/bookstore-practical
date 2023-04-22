<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Add Book</h4>
                </div>
                <div class="card-body">
                    <form @submit.prevent="onSubmit">
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
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Image</label>
                                    <input type="file" class="form-control"  @change="attachImage">
                                </div>
                            </div>
                            <div class="col-12 mb-2" v-if="imagePreview">
                                <div class="form-group">
                                    <img v-bind:src="imagePreview" width="100"/>
                                </div>
                            </div>
                            <div class="col-12">
                                <router-link :to='{name:"listBook"}' class="btn btn-secondary mr-2">
                                    Cancel
                                </router-link>
                                <input v-if="!isCreating"
                                    type="submit"
                                    class="btn btn-primary mx-2 my-2"
                                    value="Add Book">
                                <button
                                    v-if="isCreating"
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
    name: "add-book",
    data(){
        return {
            book: {
                title: '',
                description: '',
                author: '',
                genre: '',
                isbn: '',
                publisher: '',
                published_at: new Date(),
                uploadImage: '',
            },
            validationErrors: null,
            imagePreview: null,
        }
    },
    components: {
        Datepicker
    },
    computed: { ...mapGetters(["isCreating", "createdData"]) },

    watch: {
        createdData: function () {
            if (this.createdData !== null && !this.isCreating) {
                this.$swal.fire({
                    text: "Success, Book has been added.",
                    icon: "success",
                    position: "center",
                    timer: 1000,
                });
                this.$router.push({ name: "listBook" });
            }
        },
    },

    methods: {
        ...mapActions(["storeBook"]),
        async onSubmit() {
            try {
                await this.storeBook(this.book);
            }
            catch (e){
                this.validationErrors = e.data;
            };
        },
        attachImage(e) {
            this.book.uploadImage = e.target.files[0];
            this.imagePreview = null;
            let reader = new FileReader();
            reader.addEventListener('load', function() {
                this.imagePreview = reader.result;
            }.bind(this), false);
            if (this.book.uploadImage) {
                if ( /\.(jpe?g|png|gif)$/i.test(this.book.uploadImage.name) ) {
                    reader.readAsDataURL(this.book.uploadImage);
                } else {
                    // this.validationErrors = {"errors":
                    //     { "uploadImage":
                    //         ["The upload image must be a file of type: png, jpg, jpeg, gif."]
                    //     }
                    // };
                }
            }
        }
    }
}
</script>