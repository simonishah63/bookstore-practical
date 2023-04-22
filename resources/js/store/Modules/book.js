// import { GET_ALL_BookS } from "./Types";
import axios from 'axios';

// initial state
const state = () => ({
    sendData: null,
    books: [],
    booksPaginatedData: null,
    book: null,
    isLoading: false,
    isCreating: false,
    createdData: null,
    isUpdating: false,
    updatedData: null,
    isDeleting: false,
    deletedData: null
})

// getters
const getters = {
    bookList: state => state.books,
    booksPaginatedData: state => state.booksPaginatedData,
    book: state => state.book,
    isLoading: state => state.isLoading,
    isCreating: state => state.isCreating,
    isUpdating: state => state.isUpdating,
    createdData: state => state.createdData,
    updatedData: state => state.updatedData,

    isDeleting: state => state.isDeleting,
    deletedData: state => state.deletedData
};

// actions
const actions = {
    async fetchAllBooks({ commit }, query = null) {
        let page = 1;
        let search = '';
        if(query !== null){
            page = query?.page || 1;
            search = query?.search || '';
        }

        commit('setBookIsLoading', true);
        let url = `/api/books?page=${page}`;
        if (search === null) {
            url = `${url}?page=${page}`;
        } else {
            url = `/api/books?search=${search}&page=${page}`
        }
        await axios.get(url)
            .then(res => {
                const books = res.data.data.data;
                commit('setBooks', books);
                const pagination = {
                    total: res.data.data.total,  // total number of elements or items
                    per_page: res.data.data.per_page, // items per page
                    current_page: res.data.data.current_page, // current page (it will be automatically updated when users clicks on some page number).
                    total_pages: res.data.data.last_page // total pages in record
                }
                res.data.data.pagination = pagination;
                commit('setBooksPaginated', res.data.data);
                commit('setBookIsLoading', false);
            }).catch(err => {
                console.log('error', err);
                commit('setBookIsLoading', false);
            });
    },

    async fetchDetailBook({ commit }, id) {
        commit('setBookIsLoading', true);
        commit('setBookIsUpdating', false);
        await axios.get(`/api/books/show/${id}`)
        .then(res => {
            commit('setBookDetail', res.data.data);
            commit('setBookIsLoading', false);
        }).catch(err => {
            console.log('error', err);
            commit('setBookIsLoading', false);
        });
    },

    async storeBook({ commit }, book) {
        commit('setBookIsCreating', true);
        const config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        }
        await axios.post(`/api/books/add`, book, config)
        .then(res => {
            commit('saveNewBooks', res.data.data);
            commit('setBookIsCreating', false);
        }).catch(err => {
            console.log('error', err);
            commit('setBookIsCreating', false);
            throw(err.response);
        });
    },

    async updateBook({ commit }, book) {
        commit('setBookIsUpdating', true);
        const config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        }
        await axios.post(`/api/books/${book.id}`, book, config)
        .then(res => {
            commit('saveUpdatedBook', res.data.data);
            commit('setBookIsUpdating', false);
        }).catch(err => {
            console.log('error', err);
            commit('setBookIsUpdating', false);
            throw(err.response);
        });
    },


    async deleteBook({ commit }, id) {
        commit('setBookIsDeleting', true);
        await axios.delete(`/api/books/${id}`)
        .then(() => {
            commit('setDeleteBook', res.data.data.id);
            commit('setBookIsDeleting', false);
        }).catch(err => {
            console.log('error', err);
            commit('setBookIsDeleting', false);
        });
    },
}

// mutations
const mutations = {
    setBooks: (state, books) => {
        state.books = books
    },

    setBooksPaginated: (state, booksPaginatedData) => {
        state.booksPaginatedData = booksPaginatedData
    },

    setBookDetail: (state, book) => {
        state.book = book;
        state.book.published_at = new Date(book.published_at);
    },

    setDeleteBook: (state, id) => {
        state.booksPaginatedData.data.filter(x => x.id !== id);
    },

    saveNewBooks: (state, book) => {
        state.books.unshift(book)
        state.createdData = book;
    },

    saveUpdatedBook: (state, book) => {
        state.books.unshift(book)
        state.updatedData = book;
    },

    setBookIsLoading(state, isLoading) {
        state.isLoading = isLoading
    },

    setBookIsCreating(state, isCreating) {
        state.isCreating = isCreating
    },

    setBookIsUpdating(state, isUpdating) {
        state.isUpdating = isUpdating
    },

    setBookIsDeleting(state, isDeleting) {
        state.isDeleting = isDeleting
    },

}

export default {
    // namespaced: true,
    state,
    getters,
    actions,
    mutations
}