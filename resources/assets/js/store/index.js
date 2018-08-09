import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import mutations from './mutations';
import getters from './getters';

const store = new Vuex.Store({
  state: {
    drawer: true,
    user: null,
    campaign: null,
    loading: false
  },
  mutations,
  getters,
});

export default store;
