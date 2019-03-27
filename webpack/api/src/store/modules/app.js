const app = {
  state: {
    init_layout: '', // 用这个字段来监听是否需要刷新
    user: '', // 用户信息
    project_list: '', // 项目信息
    project_index: 0,
    project: '',
    api_list: [] // 接口列表
  },
  mutations: {
    SET_INIT_LAYOUT: (state, data) => {
      state.init_layout = data
    },
    SET_USER: (state, data) => {
      state.user = data
    },
    SET_PROJECT_LIST: (state, data) => {
      state.project_list = data
      if (state.project_list.length > 0) {
        state.project = state.project_list[state.project_index]
      }
    },
    SET_PROJECT_INDEX: (state, index) => {
      state.project_index = index
      if (state.project_list.length > 0) {
        state.project = state.project_list[index]
      }
    },
    SET_API_LIST: (state, data) => {
      state.api_list = data
    }
  },
  actions: {
    set_init_layout({ commit }, data) {
      commit('SET_INIT_LAYOUT', data)
    },
    set_user({ commit }, data) {
      commit('SET_USER', data)
    },
    set_project_list({ commit }, data) {
      commit('SET_PROJECT_LIST', data)
    },
    set_project_index({ commit }, data) {
      commit('SET_PROJECT_INDEX', data)
    },
    set_api_list({ commit }, { category, api }) {
      const data = {}
      commit('SET_API_LIST', data)
    }
  }
}

export default app
