const form = {
  state: {
    show_category: '',
    category_form: {}, // 表单字段
    show_api_form: '',
    api_form: {}, // 表单字段

    show_login: '',
    show_project: ''
  },
  mutations: {
    SET_CATEGORY_SHOW: (state, data) => {
      state.show_category = new Date()
      state.category_form = data
    },
    SET_API_SHOW: (state, data) => {
      state.show_api_form = new Date()
      state.api_form = data
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
    show_reg_form({ commit }) {
      commit('SHOW_REG_FORM', new Date())
    },
    show_login_form({ commit }) {
      commit('SHOW_LOGIN_FORM', new Date())
    },
    show_project_form({ commit }) {
      commit('SHOW_PROJECT_FORM', new Date())
    },
    show_category_form({ commit }, form) {
      commit('SET_CATEGORY_SHOW', form)
    },
    show_api_form({ commit }, form) {
      commit('SET_API_SHOW', form)
    }
  }
}

export default form
