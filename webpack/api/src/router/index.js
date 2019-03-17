import Vue from 'vue'
import Router from 'vue-router'

// in development-env not use lazy-loading, because lazy-loading too many pages will cause webpack hot update too slow. so only in production use lazy-loading;
// detail: https://panjiachen.github.io/vue-element-admin-site/#/lazy-loading

Vue.use(Router)

/* Layout */
// import Layout from '../views/layout/Layout'
// import Empty from '../views/layout/Empty'

/**
* hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
* alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
*                                if not set alwaysShow, only more than one route under the children
*                                it will becomes nested mode, otherwise not show the root menu
* redirect: noredirect           if `redirect:noredirect` will no redirect in the breadcrumb
* name:'router-name'             the name is used by <keep-alive> (must set!!!)
* meta : {
    title: 'title'               the name show in submenu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    breadcrumb: false            if false, the item will hidden in breadcrumb(default is true)
  }
**/
export const constantRouterMap = [
  // { path: '/login', component: () => import('@/views/login/index'), hidden: true },
  { path: '/404', component: () => import('@/views/404'), hidden: true },
  {
    path: '/',
    component: () => import('@/views/layout/ApiLayout'),
    children: [
      {
        path: '/',
        name: 'api',
        component: () => import('@/views/api/index')
      }, {
        path: '/project_list',
        name: 'project_list',
        component: () => import('@/views/api/project_list')
      }, {
        path: '/project_form',
        name: 'project_form',
        component: () => import('@/views/api/project_form')
      }, {
        path: '/api_list',
        name: 'api_list',
        component: () => import('@/views/api/api_list')
      }, {
        path: '/api_item_form',
        name: 'api_item_form',
        component: () => import('@/views/api/api_item_form')
      }, {
        path: '/api_form',
        name: 'api_form',
        component: () => import('@/views/api/api_form')
      }
    ]
  }
]

export default new Router({
  // mode: 'history', //后端支持可开
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRouterMap
})

export const asyncRouterMap = []
