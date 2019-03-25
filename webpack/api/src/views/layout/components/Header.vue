<template>
  <div :class="user ? 'login' : ''" class="layout-header transition">
    <div v-if="user" class="left">
      <el-dropdown size="small">
        <el-button type="primary" size="mini">
          项目名称<i class="el-icon-arrow-down el-icon--right"/>
        </el-button>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item>目录</el-dropdown-item>
          <el-dropdown-item>请求</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
      <el-button type="primary" size="mini">
        +
      </el-button>
    </div>
    <span class="title">MX-API</span>
    <div v-if="user" class="right">
      <el-dropdown size="small">
        <el-button type="primary" size="mini">
          用户账号<i class="el-icon-arrow-down el-icon--right"/>
        </el-button>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item>设置</el-dropdown-item>
          <el-dropdown-item>退出登录</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
    <div v-else class="right">
      <el-button-group>
        <el-button type="primary" size="mini">注册</el-button>
        <el-button type="primary" size="mini" @click="$refs['login'].visible = true">登陆</el-button>
      </el-button-group>
    </div>
    <login ref="login"/>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Login from '@/components/form/login'
export default {
  components: {
    Login
  },
  data() {
    return {}
  },
  computed: {
    ...mapGetters([
      'user'
    ])
  },
  created() {
    // this.$store.commit('set_user', {})
    // this.$store.dispatch('set_user', {})
    console.log(this.user)
  },
  methods: {
    handleClick() {}
  }
}
</script>

<style lang="scss" scoped>
@import "@/styles/index.scss";
$layout-header-background-color: #1f88f5;
.login.layout-header{background-color:$layout-header-background-color}
.layout-header{ background-color: #409eff; color: white;
  .left,.right{display: inline-block; height:$layoutHeaderHeight; line-height: $layoutHeaderHeight; padding: 0 15px;}
  .left{ float: left; }
  .title{ text-align: center; height:$layoutHeaderHeight; line-height: $layoutHeaderHeight; font-size: 18px; }
  .right{ float: right; }
}
</style>
