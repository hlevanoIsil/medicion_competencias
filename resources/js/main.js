/* eslint-disable import/order */
import '@/@iconify/icons-bundle'
import App from '@/App.vue'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
import router from '@/router'
import '@core-scss/template/index.scss'
import '@layouts/styles/index.scss'
import '@styles/styles.scss'
import { createPinia } from 'pinia'
import { createApp } from 'vue'
import store from './store'
//import axios from 'axios'
import axios from '@axios';
import moment from "moment";
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

loadFonts()

// Create vue app
const app = createApp(App)

const options = {
  confirmButtonColor: '#18BC5A',
  cancelButtonColor: '#FF4538',
};

app.config.globalProperties.$http = axios

app.config.globalProperties.$moment = moment


// Use plugins
app.use(vuetify)
app.use(createPinia())
app.use(router)
app.use(store)
app.use(VueSweetalert2, options);

app.provide("http", app.config.globalProperties.$http);
app.provide("moment", moment);

// Mount vue app
app.mount('#app')
