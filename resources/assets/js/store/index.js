import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import mutations from './mutations';

const store = new Vuex.Store({
    state: {
        user: null,
        campaign: null,
        loading: false
    },
    mutations,
});

export default store;
