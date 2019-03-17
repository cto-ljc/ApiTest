import { asyncRouterMap, constantRouterMap } from '@/router'

/**
 * 通过meta.role判断是否与当前用户权限匹配
 * @param roles_auth
 * @param route
 */
function hasPermission(roles_auth, route) {
  if (route.meta && route.meta.auth) {
    return roles_auth ? roles_auth.includes(route.name) : false
  } else {
    return true
  }
}

/**
 * 递归过滤异步路由表，返回符合用户角色权限的路由表
 * @param routes asyncRouterMap
 * @param roles_auth
 */
function filterAsyncRouter(routes, roles_auth) {
  const res = []

  routes.forEach(route => {
    const tmp = { ...route }
    if (hasPermission(roles_auth, tmp)) {
      if (tmp.children) {
        tmp.children = filterAsyncRouter(tmp.children, roles_auth)
      }
      res.push(tmp)
    }
  })

  return res
}

const permission = {
  state: {
    routers: constantRouterMap,
    addRouters: ''
  },
  mutations: {
    SET_ROUTERS: (state, routers) => {
      state.addRouters = routers
      state.routers = constantRouterMap.concat(routers)
    }
  },
  actions: {
    GenerateRoutes({ commit }, data) {
      return new Promise(resolve => {
        const { roles } = data
        const { roles_auth } = data
        let accessedRouters
        if (roles.includes('admin')) {
          accessedRouters = asyncRouterMap
        } else {
          accessedRouters = filterAsyncRouter(asyncRouterMap, roles_auth)
        }
        commit('SET_ROUTERS', accessedRouters)
        resolve()
      })
    }
  }
}

export default permission
