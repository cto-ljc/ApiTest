const form = {
  state: {
    show_category: '',
    show_reg: '',
    show_login: '',
    show_project: ''
  },
  mutations: {
    SET_CATEGORY_SHOW: (state, data) => {
      state.show_category = data
    },
    SHOW_REG_FORM: (state, data) => {
      state.show_reg = data
    },
    SHOW_LOGIN_FORM: (state, data) => {
      state.show_login = data
    },
    SHOW_PROJECT_FORM: (state, data) => {
      state.show_project = data
    }
  },
  actions: {
    show_category_form({ commit }) {
      commit('SET_CATEGORY_SHOW', new Date())
    },
    show_reg_form({ commit }) {
      commit('SHOW_REG_FORM', new Date())
    },
    show_login_form({ commit }) {
      commit('SHOW_LOGIN_FORM', new Date())
    },
    show_project_form({ commit }) {
      commit('SHOW_PROJECT_FORM', new Date())
    }
  }
}

export default form
