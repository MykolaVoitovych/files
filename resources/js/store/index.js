import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        tags: null,
    },
    getters: {
        getTags (state) {
            return state.tags
        }
    },
    mutations: {
        setTags: (state, tags) => (state.tags = tags),
    },
    actions: {},
    strict: process.env.NODE_ENV !== 'production'
})
