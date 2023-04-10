import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import auth from './Modules/auth';
import book from './Modules/book';

const store = createStore({
    plugins:[
        createPersistedState()
    ],
    modules:{
        auth,
        book
    }
})

export default store
