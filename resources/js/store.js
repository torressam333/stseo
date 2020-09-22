import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

//To use our store globally
export default new Vuex.Store({
    state: {
        counter: 1000
    },
    mutations: {
        changeCounter(state, payload) {
            state.counter += payload;
        }
    }
});

