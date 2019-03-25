const app = {
  state: {
    user: ''
  },
  mutations: {
    SET_USER: (state, user) => {
      state.user = user
    }
  },
  actions: {
    set_user({ commit }, user) {
      commit('SET_USER', user)
    }
  }
}

export default app
