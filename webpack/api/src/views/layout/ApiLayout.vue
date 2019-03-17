<template>
  <el-container >
    <el-header height="50px">
      <div class="content">
        <div class="logo btn" @click="logo">api-test</div>
        <div v-if="user.uid" class="user" >
          <div>{{ user.email }}</div>
          <div class="logout btn" @click="logout">退出</div>
        </div>
        <div v-else class="user">
          <div class="btn" @click="login_form_visible = true; $refs['login'] ? $refs['login'].resetFields() : '';">登录</div>
          <div class="btn" @click="reg_form_visible = true; $refs['reg'] ? $refs['reg'].resetFields() : '';">注册</div>
        </div>
      </div>
    </el-header>
    <el-main>
      <div class="content">
        <el-tabs v-if="user.uid" v-model="active_tab" @tab-click="tab_click">
          <el-tab-pane label="api-test" name="main" />
          <el-tab-pane label="项目管理" name="app_list"/>
        </el-tabs>
        <router-view ref="child"/>
      </div>
    </el-main>

    <el-dialog :visible.sync="login_form_visible" :close-on-click-modal="false" class="login-dialog" title="用户登录" width="400px">
      <el-form ref="login" :model="login_form" :rules="login_rules" label-position="left" label-width="55px">
        <el-form-item label="邮箱" prop="email">
          <el-input v-model="login_form.email" />
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input v-model="login_form.password" type="password" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <mu-button full-width class="btn" color="primary" @click="login">确 定</mu-button>
      </div>
    </el-dialog>

    <el-dialog :visible.sync="reg_form_visible" :close-on-click-modal="false" class="reg-dialog" title="用户注册" width="400px">
      <el-form ref="reg" :model="reg_form" :rules="reg_rules" label-position="left" label-width="65px">
        <el-form-item label="邮箱" prop="email">
          <el-input v-model="reg_form.email" />
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input v-model="reg_form.password" type="password" />
        </el-form-item>
        <el-form-item label="验证码" prop="code">
          <el-row :gutter="0">
            <el-col :span="14">
              <el-input v-model="reg_form.code" />
            </el-col>
            <el-col :span="10">
              <mu-button :color="get_code_btn.color" class="get_code_btn btn" @click="get_code">{{ get_code_btn.name }}</mu-button>
            </el-col>
          </el-row >
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <mu-button full-width class="btn" color="primary" @click="reg" >注册</mu-button>
      </div>
    </el-dialog>
  </el-container>
</template>
<script>
import crypto from 'crypto'
export default {
  data() {
    return {
      // 登录
      login_form_visible: false,
      login_form: {
        email: 'cto-ljc@qq.com',
        password: '123456'
      },
      login_rules: {
        email: [
          { required: true, message: '请填写邮箱', trigger: 'blur' },
          { type: 'email', message: '邮箱格式不正确', trigger: 'blur' }
        ],
        password: [
          { required: true, message: '请填写密码', trigger: 'blur' },
          { min: 6, max: 18, message: '长度在 6 到 18 个字符', trigger: 'blur' }
        ]
      },

      // 注册
      reg_form_visible: false,
      reg_form: {
        email: '',
        password: '',
        code: ''
      },
      reg_rules: {
        email: [
          { required: true, message: '请填写邮箱', trigger: 'blur' },
          { type: 'email', message: '邮箱格式不正确', trigger: 'blur' }
        ],
        password: [
          { required: true, message: '请填写密码', trigger: 'blur' },
          { min: 6, max: 18, message: '长度在 6 到 18 个字符', trigger: 'blur' }
        ],
        code: [
          { required: true, message: '请填写验证码', trigger: 'blur' },
          { len: 6, message: '请填写6位验证码', trigger: 'blur' }
        ]
      },
      get_code_btn: {
        name: '获取验证码',
        status: true,
        color: 'primary'
      },
      user: {},
      active_tab: ''
    }
  },
  watch: {
    $route() {
      this.init_tab()
    }
  },
  created() {
    this.init()
  },
  methods: {
    login() {
      this.$refs['login'].validate((valid) => {
        if (!valid) {
          return false
        }

        var data = {
          email: this.login_form.email,
          password: this.login_form.password
        }

        var md5 = crypto.createHash('md5')
        md5.update(data.password)
        data.password = md5.digest('hex')

        this.$request.post('/api/index/login', data).then(response => {
          var res = response.data
          if (res.success) {
            this.init()
            this.login_form_visible = false
          } else {
            this.$message.warning(res.msg)
          }
        })
      })
    },
    logout() {
      this.$request.post('/api/index/logout').then(response => {
        var res = response.data
        if (res.success) {
          this.init()
        } else {
          this.$message.warning(res.msg)
        }
      })
    },
    reg() {
      this.$refs['reg'].validate((valid) => {
        if (!valid) {
          return false
        }

        var data = {
          email: this.reg_form.email,
          password: this.reg_form.password,
          code: this.reg_form.code
        }

        var md5 = crypto.createHash('md5')
        md5.update(data.password)
        data.password = md5.digest('hex')

        this.$request.post('/api/index/reg', data).then(response => {
          var res = response.data
          if (res.success) {
            this.init()
            this.reg_form_visible = false
          } else {
            this.$message.warning(res.msg)
          }
        })
      })
    },

    get_code() {
      if (this.get_code_btn.status === false) {
        return
      }
      this.get_code_btn.status = false

      var data = {}
      data.email = this.reg_form.email
      data.type = 'reg'
      this.$request.post('/api/index/sendEmailCode', data).then(res => {
        if (res.success === true) {
          this.count_down(60)
        } else {
          this.get_code_btn.status = true
        }
      })
    },
    count_down(time) {
      time--
      if (time === 0) {
        this.get_code_btn.status = true
        this.get_code_btn.name = '获取验证码'
        this.get_code_btn.color = 'primary'
      } else {
        this.get_code_btn.name = '重发(' + time + ')'
        this.get_code_btn.color = '#d0d0d0'
        setTimeout(res => {
          this.count_down(time)
        }, 1000)
      }
    },

    init() {
      this.init_tab()
      this.get_data()
    },
    init_tab() {
      var active_tab = this.$route.name
      switch (active_tab) {
        case 'app_form':
          active_tab = 'app_list'
          break
        case 'api_list':
          active_tab = 'app_list'
          break
        case 'api_item_form':
          active_tab = 'app_list'
          break
        case 'api_form':
          active_tab = 'app_list'
          break
      }
      this.active_tab = active_tab
    },
    // 获取数据接口
    get_data() {
      this.$request.post('/api/index/main').then(response => {
        var res = response.data
        if (res.success === false) {
          return
        }
        var user = res.data.user_info
        this.user = user
        this.$store.commit('set_user', user)

        this.$refs.child.init()
      })
    },
    logo() {},
    tab_click(e) {
      var name = e.name
      this.$router.push({ name })
    }
  }
}
</script>

<style>
.login-dialog .btn,.reg-dialog .btn{box-shadow: none;}
.login-dialog .el-dialog__body{ padding-bottom: 0; }
.reg-dialog .el-dialog__body{ padding-bottom: 0; }
.reg-dialog .get_code_btn{ float: right; height: 40px; line-height: 40px; box-shadow: none;  }
</style>
<style scoped>
.el-header{background-color: #345ba2;color: white;line-height: 50px; position: fixed; width: 100%;}
.el-header .logo{font-size: 20px; font-weight: bolder; display: inline-block;}
.el-header .user{ float: right;}
.el-header .user > div{ display: inline-block; padding: 0 10px;}

.el-aside {background-color: #D3DCE6;color: #333;text-align: center;line-height: 200px;}
.el-main {background-color: #E9EEF3;color: #333; position: fixed; width: 100%; top: 50px; bottom: 0;}
body > .el-container {margin-bottom: 40px;}
.el-container:nth-child(5) .el-aside,
.el-container:nth-child(6) .el-aside {line-height: 260px;}
.el-container:nth-child(7) .el-aside {line-height: 320px;}

.content { max-width: 800px; margin: auto;}
</style>
