import axios from 'axios'
// import { Message, MessageBox } from 'element-ui'
import { Message } from 'element-ui'

// 创建axios实例
const service = axios.create({
  // baseURL: process.env.BASE_API, // api 的 base_url  // 目前采用代理模式 打包文件放在项目根目录 所以不需要base_url
  timeout: 5000 // 请求超时时间
})

// request拦截器
service.interceptors.request.use(
  config => {
    config.headers = ''
    return config
  },
  error => {
    // Do something with request error
    console.log(error) // for debug
    Promise.reject(error)
  }
)

// response 拦截器
service.interceptors.response.use(
  response => {
    /**
     * code为非20000是抛错 可结合自己业务进行修改
     */
    const res = response.data
    if (res.status !== true) {
      Message({
        message: res.msg,
        type: 'error',
        duration: 5 * 1000
      })

      // 50008:非法的token; 50012:其他客户端登录了;  50014:Token 过期了;
      if (res.code === 10000) {
        // MessageBox.confirm(
        //   '你已被登出，可以取消继续留在该页面，或者重新登录',
        //   '确定登出',
        //   {
        //     confirmButtonText: '重新登录',
        //     cancelButtonText: '取消',
        //     type: 'warning'
        //   }
        // ).then(() => {
        //   store.dispatch('FedLogOut').then(() => {
        //     location.reload() // 为了重新实例化vue-router对象 避免bug
        //   })
        // })
        Message({
          message: res.msg,
          type: 'error',
          duration: 5 * 1000
        })
      }
      return Promise.reject(response.data)
    } else {
      return response.data
    }
  },
  error => {
    console.log('err' + error) // for debug
    Message({
      message: error.message,
      type: 'error',
      duration: 5 * 1000
    })
    return Promise.reject(error)
  }
)

export default service
