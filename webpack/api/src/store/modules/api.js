const api = {
  state: {
    id: 0,
    list: [] // 窗口列表
  },
  mutations: {
    APPEND_API_VIEW: (state, api) => {
      try {
        state.id = api.id
        for (var i in state.list) {
          if (state.list[i].id === api.id) {
            throw new Error('complate')
          }
        }
        state.list.push(api)
      } catch (e) {
        return
      }
    },
    SET_API_VIEW_ID: (state, id) => {
      state.id = id
    },
    DELETE_API_VIEW: (state, id) => {
      try {
        for (var i in state.list) {
          if (state.list[i].id === id) {
            state.list.splice(i, 1)
            // 如果删除的id是当前id 则给当前id重新赋值
            if (state.id === id) {
              state.id = state.list[state.list.length - 1].id
            }
            throw new Error('complate')
          }
        }
        state.list.push(api)
      } catch (e) {
        return
      }
    }
  },
  actions: {
    append_api_view({ commit }, api) {
      commit('APPEND_API_VIEW', api)
    },
    set_api_view_id({ commit }, id) {
      commit('SET_API_VIEW_ID', id)
    },
    delete_api_view({ commit }, api) {
      commit('DELETE_API_VIEW', api)
    }
  }
}

export default api
