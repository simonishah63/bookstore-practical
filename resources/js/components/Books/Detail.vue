<template>
    <div class="row border-1 p-2">
        <div class="col-3">
            {{ book.title }}
        </div>
        <div class="col-3">
            {{ book.author }}
        </div>
        <div class="col-2">
            {{ book.genre }}
        </div>
        <div class="col-2">
            {{ book.isbn }}
        </div>
        <div class="col-2" v-if="user.role_id == '1'">
            <router-link
                :to="{ name: 'editBook', params: { id: book.id } }"
                class="btn btn-primary mr-2"
                title="Edit Book"
            >
                Edit
            </router-link>
            <button
                class="btn btn-danger mx-2"
                title="Delete Book"
                @click="deleteBookModal(book.id)"
            >
                Delete
            </button>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
    name: "BookDetail",
    props: {
        book: {
            type: Object,
            required: true,
        },
        user: {
            type: Object,
            required: true,
        },
        query: {
            type: Object,
            required: true,
        },
    },

    computed: { ...mapGetters(["isDeleting", "deletedData"]) },

    methods: {
        ...mapActions(["deleteBook", "fetchAllBooks"]),
        deleteBookModal(id) {
            this.$swal
                .fire({
                    text: "Are you sure to delete the book ?",
                    icon: "error",
                    cancelButtonText: "Cancel",
                    confirmButtonText: "Yes, Confirm Delete",
                    showCancelButton: true,
                })
            .then((result) => {
                if (result["isConfirmed"]) {
                    // Put delete logic
                    this.deleteBook(id);
                    this.fetchAllBooks(this.query);
                    this.$swal.fire({
                        text: "Success, Book has been deleted.",
                        icon: "success",
                        position: 'center',
                        timer: 1000,
                    });
                }
            });
        },
    },
};
</script>