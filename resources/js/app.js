require('./bootstrap');

import Vue from 'vue'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)

import store from './store'
import router from './router.js'
import Base from "./Base";

let app
if (document.getElementById('app')) {
    let appElement = document.getElementById('app')
    store.commit('setTags', JSON.parse(appElement.dataset.tags))

    app = new Vue({
        router,
        store,
        render: h => h(Base),
    }).$mount('#app')
}

export default app
