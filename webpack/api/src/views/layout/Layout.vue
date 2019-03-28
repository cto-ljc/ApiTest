<template>
  <div class="app-wrapper">
    <Header/>
    <div :class="{'show_sidebar': project_list.length > 0}" class="layout_content">
      <Sidebar/>
      <AppMain/>
    </div>
    <Category ref="category"/>
    <Api ref="api"/>
    <Login ref="login"/>
    <Reg ref="reg"/>
    <Project ref="project"/>
  </div>
</template>

<script>
import { Header, Sidebar, AppMain } from './components'
import { Category, Api, Login, Reg, Project } from '@/components/form/index'
import { mapGetters } from 'vuex'
export default {
  components: { Header, Sidebar, AppMain, Category, Api, Login, Reg, Project },
  data() {
    return {}
  },
  computed: {
    ...mapGetters([
      'user',
      'project',
      'project_list',
      'init_layout',
      'show_category',
      'show_login',
      'show_reg',
      'show_project',
      'show_api_form',
      'api_form'
    ])
  },
  watch: {
    user() {},
    init_layout() {
      this.get_data()
    },
    show_category() {
      this.$refs['category'].visible = true
    },
    show_api_form() {
      this.$refs['api'].visible = true
    },
    show_login() {
      this.$refs['login'].visible = true
    },
    show_reg() {
      this.$refs['reg'].visible = true
    },
    show_project() {
      this.$refs['project'].visible = true
    },
    $route() {
      this.init()
    }
  },
  mounted() {
    this.init()
  },
  methods: {
    init() {
      if (this.$route.query.project) {
        this.$store.dispatch('set_project_id', this.$route.query.project)
      }
      this.get_data()
    },
    get_data() {
      const project_id = this.$route.query.project
      const param = {
        project_id
      }
      this.$request.post('/api/index/getMainData', param).then(res => {
        if (!res.data.user) {
          return
        }

        const user = res.data.user
        const project_list = res.data.project_list
        const category = res.data.category_list
        const api = res.data.api_list

        this.$store.dispatch('set_project_list', project_list)
        this.$store.dispatch('set_user', user)
        this.$store.dispatch('set_api_list', { category, api })
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
 .layout-sidebar{ position: absolute; top: 0; left: -$layoutSidebarWidth; bottom: 0; width: $layoutSidebarWidth; box-shadow: 2px 0px 2px 0px #ccc;}
 .layout-main{ position: absolute; top: 0; right: 0; bottom: 0; left: 0; }
}
.layout_content.show_sidebar{
 .layout-sidebar{ left: 0}
 .layout-main{left: $layoutSidebarWidth;}
}
</style>
