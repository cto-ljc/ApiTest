<template>
  <el-dialog :visible.sync="visible" :close-on-click-modal="false" class="reg-dialog" title="用户注册" width="400px">
    <el-form ref="reg" :model="form" :rules="rules" label-position="left" label-width="65px">
      <el-form-item label="邮箱" prop="email">
        <el-input v-model="form.email" />
      </el-form-item>
      <el-form-item label="密码" prop="password">
        <el-input v-model="form.password" type="password" />
      </el-form-item>
      <el-form-item label="验证码" prop="code">
        <el-row :gutter="0">
          <el-col :span="14">
            <el-input v-model="form.code" />
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
</template>

<script>
import crypto from 'crypto'
export default {
  data() {
    return {
      visible: false,
      form: {
        email: '',
        password: '',
        code: ''
      },
      rules: {
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
          { len: 4, message: '请填写4位验证码', trigger: 'blur' }
        ]
      },
      get_code_btn: {
        name: '获取验证码',
        status: true,
        color: 'primary'
      }
    }
  },
  methods: {
    get_code() {
      if (this.get_code_btn.status === false) {
        return
      }

      var data = {}
      data.email = this.form.email
      data.type = 'reg'

      var reg = /^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/
      if (!reg.test(data.email)) {
        this.$message({
          message: '请填写正确邮箱',
          type: 'error'
        })
        return
      }

      this.get_code_btn.status = false
      this.$request.post('/api/index/sendEmailCode', data).then(res => {
        if (res.status === true) {
          this.$message({
            message: res.msg,
            type: 'success'
          })
          this.count_down(60)
        } else {
          this.get_code_btn.status = true
        }
      }).catch(error => {
        console.log(error)
        this.get_code_btn.status = true
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
    reg() {
      this.$refs['reg'].validate((valid) => {
        if (!valid) {
          return false
        }

        var data = {
          email: this.form.email,
          password: this.form.password,
          code: this.form.code
        }

        var md5 = crypto.createHash('md5')
        md5.update(data.password)
        data.password = md5.digest('hex')

        this.$request.post('/api/index/reg', data).then(res => {
          if (res.status === true) {
            this.$message({
              message: res.msg,
              type: 'success'
            })
            this.$store.dispatch('set_user', res.data.user)
            this.visible = false
          } else {
            this.$message({
              message: res.msg,
              type: 'error'
            })
          }
        })
      })
    }
  }
}
</script>

<style lang="scss">
.reg-dialog{
  .el-dialog__body{ padding-bottom: 0; }
}
</style>
