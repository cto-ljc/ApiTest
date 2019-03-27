import Vue from 'vue'
import Vuex from 'vuex'
import app from './modules/app'
import form from './modules/form'
import getters from './getters'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    app,
    form
  },
  getters
})

export default store
