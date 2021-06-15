import Vue from 'vue'
import VueRouter from 'vue-router'

import Search from './pages/Search'
import Create from './pages/Create'

Vue.use(VueRouter)

let router =  new VueRouter({
    routes: [
        {
            path: '/',
            name: 'search',
            component: Search,
        },
        {
            path: '/create',
            name: 'create',
            component: Create
        }
    ],
    linkExactActiveClass: 'is-active'
})

export default router
