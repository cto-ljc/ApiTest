// import { list_to_tree } from '@/utils/tool'
const app = {
  state: {
    init_layout: '', // 用这个字段来监听是否需要刷新
    user: '', // 用户信息
    project_list: '', // 项目信息
    project_id: 0,
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
        state.project = state.project_list[0]
        if (state.project_id !== 0) {
          state.project_list.forEach(item => {
            if (item.id === Number(state.project_id)) {
              state.project = item
            }
          })
        }
      }
    },
    SET_PROJECT_ID: (state, id) => {
      state.project_id = Number(id)
      if (state.project_list.length > 0) {
        state.project_list.forEach(item => {
          if (item.id === id) {
            state.project = item
          }
        })
      }
    },
    SET_API_LIST: (state, { category, api }) => {
      category = init_option(category)
      state.api_list = category
    },
    APPEND_API_CATEGORY: (state, category) => {
      category.children = [] // 为了在添加的时候能够有这个字段 否则会报错
      if (category.pid === 0) {
        state.api_list.push(category)
      } else {
        append_category_item(state.api_list, category)
      }
    },
    UPDATE_API_CATEGORY: (state, category) => {
      update_category_item(state.api_list, category)
    },
    DELETE_API_CATEGORY: (state, id) => {
      delete_category_item(state.api_list, id)
    },
    APPEND_API: (state, api) => {
      append_api_item(state.api_list, api)
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
    set_project_id({ commit }, data) {
      commit('SET_PROJECT_ID', data)
    },
    set_api_list({ commit }, { category, api }) {
      const data = { category, api }
      commit('SET_API_LIST', data)
    },
    append_api_category({ commit }, category) {
      commit('APPEND_API_CATEGORY', category)
    },
    update_api_category({ commit }, category) {
      commit('UPDATE_API_CATEGORY', category)
    },
    delete_api_category({ commit }, id) {
      commit('DELETE_API_CATEGORY', id)
    },
    append_api({ commit }, api) {
      commit('APPEND_API', api)
    }
  }
}

export default app

function init_option(category) {
  let option = []
  category.forEach(item => {
    option.push({
      id: item.id,
      name: item.name,
      children: [],
      item: [],
      pid: item.pid
    })
  })

  option = tree_option(option, 0)

  return option
}

function tree_option(option, pid) {
  const list = []
  option.forEach(item => {
    if (Number(item.pid) === Number(pid)) {
      item.children = tree_option(option, item.id)
      list.push(item)
    }
  })
  return list
}

function append_category_item(api_list, category) {
  try {
    api_list.forEach(item => {
      if (item.id === category.pid) {
        item.children.push(category)
        throw new Error('complate')
      }
      if (item.children.length > 0) {
        append_category_item(item.children, category)
      }
    })
  } catch (e) {
    return
  }
}

function update_category_item(api_list, category) {
  try {
    for (var i in api_list) {
      const item = api_list[i]
      if (Number(item.id) === Number(category.id)) {
        api_list.splice(i, 1, category)
        throw new Error('complate')
      }
      if (item.children.length > 0) {
        update_category_item(item.children, category)
      }
    }
  } catch (e) {
    return
  }
}

function delete_category_item(api_list, id) {
  try {
    for (var i in api_list) {
      const item = api_list[i]
      if (Number(item.id) === Number(id)) {
        api_list.splice(i, 1)
        throw new Error('complate')
      }
      if (item.children.length > 0) {
        delete_category_item(item.children, id)
      }
    }
  } catch (e) {
    return
  }
}

function append_api_item(api_list, api) {
  console.log(api)
}
