<template>
  <div :class="user ? 'login' : ''" class="layout-header transition">
    <div v-if="user" class="left">
      <div v-if="project_list.length === 0" >
        <el-button type="primary" size="mini" @click="add_project">
          添加项目
        </el-button>
      </div>
      <div v-else>
        <el-dropdown size="small" @command="project_command">
          <el-button type="primary" size="mini">
            {{ project.name }}<i class="el-icon-arrow-down el-icon--right"/>
          </el-button>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item v-for="(project, index) in project_list" :key="index" :command="project.id">{{ project.name }}</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
        <el-button type="primary" size="mini" @click="add_project">
          +
        </el-button>
      </div>
    </div>
    <span class="title">MX-API</span>
    <div v-if="user" class="right">
      <el-dropdown size="small" @command="user_command">
        <el-button type="primary" size="mini">
          {{ user.email }}<i class="el-icon-arrow-down el-icon--right"/>
        </el-button>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item>修改密码</el-dropdown-item>
          <el-dropdown-item command="logout">退出登录</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
    <div v-else class="right">
      <el-button-group>
        <el-button type="primary" size="mini" @click="$store.dispatch('show_reg_form')">注册</el-button>
        <el-button type="primary" size="mini" @click="$store.dispatch('show_login_form')">登陆</el-button>
      </el-button-group>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  data() {
    return {}
  },
  computed: {
    ...mapGetters([
      'user',
      'project_list',
      'project'
    ])
  },
  created() {
    // this.$store.commit('set_user', {})
    // this.$store.dispatch('set_user', {})
    // console.log(this.user)
  },
  methods: {
    handleClick() {},
    // 添加项目
    add_project() {
      this.$store.dispatch('show_project_form')
    },
    project_command(index) {
      const query = JSON.parse(JSON.stringify(this.$route.query))
      query.project = index
      this.$router.push({ name: this.$route.name, query })
      // this.$store.dispatch('set_project_index', index)
    },
    user_command(val) {
      switch (val) {
        case 'logout':
          this.logout()
          break
      }
    },
    // 退出登陆
    logout() {
      this.$request.post('/api/index/logout').then(res => {
        this.$message({
          message: res.msg,
          type: 'success'
        })
        this.$store.dispatch('set_user', '')
        this.$store.dispatch('set_project_list', '')
      }).catch(error => {
        console.log(error)
      })
      console.log('退出登陆')
    }
  }
}
</script>

<style lang="scss" scoped>
@import "@/styles/index.scss";
$layout-header-background-color: #1f88f5;
.login.layout-header{background-color:$layout-header-background-color; user-select:none;}
.layout-header{ background-color: #409eff; color: white;
  .left,.right{display: inline-block; height:$layoutHeaderHeight; line-height: $layoutHeaderHeight; padding: 0 15px;}
  .left{ float: left; }
  .title{ text-align: center; height:$layoutHeaderHeight; line-height: $layoutHeaderHeight; font-size: 18px; }
  .right{ float: right; }
}
</style>
