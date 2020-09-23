import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

//To use our store globally
export default new Vuex.Store({
    state: {
        counter: 1000,
        deleteModalObj : {
            showDeleteModal: false,
            deleteUrl: '',
            data: null,
            deletingIndex: -1,
            isDeleted: false
        },
    },
    //Mutations take a call from an action and update the state.
    //Mutations are used to commit + track State changes
    mutations: {
        changeCounter(state, payload) {
            state.counter += payload;
        },
        setDeleteModal(state, data) {
            state.deleteModalObj = {
                showDeleteModal: false,
                deleteUrl: '',
                data: null,
                deletingIndex: -1,
                isDeleted: data
            };
        },
        setDeletingModalObj(state, data) {
            state.deleteModalObj = data;
        }
    },
    //Actions call mutations
    actions: {
        changeCounterAction({commit}, payload) {
            commit('changeCounter', payload)
        }
    },
    //Used to access state
    getters: {
        getCounter(state) {
            return state.counter;
        },
        getDeleteModalObj(state) {
            return state.deleteModalObj;
        }
    },
});

