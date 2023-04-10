import './bootstrap';
import '../sass/app.scss'
import Router from './router'
import store from './store';
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import "@hennge/vue3-pagination/dist/vue3-pagination.css";

import { createApp } from 'vue/dist/vue.esm-bundler';

const app = createApp({})
app.use(VueSweetalert2);
app.use(Router)
app.use(store)
app.mount('#app')