const api = {
  state: {
    list: [] // 窗口列表
  },
  mutations: {
    APPEND_API_VIEW: (state, api) => {
      state.list.push(api)
    }
  },
  actions: {
    append_api_view({ commit }, api) {
      commit('APPEND_API_VIEW', api)
    }
  }
}

export default api
