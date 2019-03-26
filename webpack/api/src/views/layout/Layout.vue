<template>
  <div class="app-wrapper">
    <Header/>
    <div class="layout_content">
      <Sidebar/>
      <AppMain/>
    </div>
  </div>
</template>

<script>
import { Header, Sidebar, AppMain } from './components'
import { mapGetters } from 'vuex'
export default {
  components: {
    Header,
    Sidebar,
    AppMain
  },
  data() {
    return {}
  },
  computed: {
    ...mapGetters([
      'user',
      'project_list'
    ])
  },
  watch: {
    user() {
      if (this.user !== '' && this.project_list === '') { // 登陆 或者注册情况下 重新请求页面数据
        this.get_data()
      }
    }
  },
  created() {
    this.get_data()
  },
  methods: {
    get_data() {
      this.$request.post('/api/index/getMainData').then(res => {
        if (!res.data.user) {
          return
        }

        const user = res.data.user
        const project_list = res.data.project_list

        this.$store.dispatch('set_project_list', project_list)
        this.$store.dispatch('set_user', user)
      }).catch(error => {
        console.log(error)
      })
    }
  }
}
</script>
<style lang="scss">
@import "@/styles/index.scss";
.layout-header{ position: absolute; top: 0; width: 100%; height: $layoutHeaderHeight; text-align: center;}
.layout_content{ position: absolute; top: 50px; bottom: 0; width: 100%;
 .layout-sidebar{ position: absolute; top: 0; left: 0; bottom: 0; width: $layoutSidebarWidth; box-shadow: 2px 0px 2px 0px #ccc;}
 .layout-main{ position: absolute; top: 0; right: 0; bottom: 0; left: $layoutSidebarWidth;}
}
</style>
