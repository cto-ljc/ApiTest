const app = {
  state: {
    user: '', // 用户信息
    project_list: '' // 项目信息
  },
  mutations: {
    SET_USER: (state, data) => {
      state.user = data
    },
    SET_PROJECT_LIST: (state, data) => {
      state.project_list = data
    }
  },
  actions: {
    set_user({ commit }, data) {
      commit('SET_USER', data)
    },
    set_project_list({ commit }, data) {
      commit('SET_PROJECT_LIST', data)
    }
  }
}

export default app
