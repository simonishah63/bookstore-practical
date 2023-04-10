import axios from 'axios'

const state = () => ({
        authenticated:false,
        user:{}
})

const getters = {
        authenticated: state => state.authenticated,
        user: state => state.user,
};

const mutations = {
    setAuthenticated (state, payload) {
        state.authenticated = payload
    },
    setUser (state, payload) {
        state.user = payload
    }
};

const actions = {
    async login({ dispatch }, payload) {
        try {
            await axios.get('/sanctum/csrf-cookie');

            await axios.post('/api/login', payload).then((res) => {
                return dispatch('getUser');
            }).catch((err) => {
                throw err.response
            });
        } catch (e) {
            throw e
        }
    },
    async register({ dispatch }, payload) {
        try {await axios.post('/api/register' , payload).then((res) => {
                return dispatch('login' , { 'email' : payload.email , 'password' : payload.password})
            }).catch((err) => {
                throw(err.response)
            })
        } catch (e) {
            throw (e)
        }
    },
    async logout({ commit }) {
        await axios.post('/api/logout').then((res) => {
            commit('setUser', null);
            commit('setAuthenticated',false)
        }).catch((err) => {
            
        })
    },
    async getUser({commit}) {
        await axios.get('/api/user').then((res) => {
            commit('setUser', res.data.data);
            commit('setAuthenticated',true)
        }).catch((err) => {
            commit('setUser',{})
            commit('setAuthenticated',false)
            throw err.response
        })
    },
};

export default {
    //namespaced: true,
    state,
    getters,
    actions,
    mutations
}