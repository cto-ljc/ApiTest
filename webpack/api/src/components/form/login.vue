<template>
  <el-dialog :visible.sync="visible" :close-on-click-modal="false" class="form-dialog" title="用户登录" width="400px" @close="close">
    <el-form ref="login" :model="form" :rules="rules" label-position="left" label-width="55px">
      <el-form-item label="邮箱" prop="email">
        <el-input v-model="form.email" />
      </el-form-item>
      <el-form-item label="密码" prop="password">
        <el-input v-model="form.password" type="password" />
      </el-form-item>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <mu-button full-width class="btn" color="primary" @click="submit">确 定</mu-button>
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
        email: 'cto-ljc@qq.com',
        password: '123456'
      },
      submit_status: false,
      rules: {
        email: [
          { required: true, message: '请填写邮箱', trigger: 'blur' },
          { type: 'email', message: '邮箱格式不正确', trigger: 'blur' }
        ],
        password: [
          { required: true, message: '请填写密码', trigger: 'blur' },
          { min: 6, max: 18, message: '长度在 6 到 18 个字符', trigger: 'blur' }
        ]
      }
    }
  },
  watch: {
    visible() {
      if (this.visible === false) {
        this.init()
      }
    }
  },
  methods: {
    init() {
      this.$refs['login'].resetFields()
      this.submit_status = false
    },
    submit() {
      if (this.submit_status === true) {
        return
      }
      console.log('登陆')
      this.$refs['login'].validate((valid) => {
        if (!valid) {
          return false
        }

        var data = {
          email: this.form.email,
          password: this.form.password
        }

        var md5 = crypto.createHash('md5')
        md5.update(data.password)
        data.password = md5.digest('hex')

        this.submit_status = true
        this.$request.post('/api/index/login', data).then(res => {
          this.$message({
            message: res.msg,
            type: 'success'
          })
          this.$store.dispatch('set_init_layout', new Date())
          this.visible = false
        }).catch(error => {
          console.log(error)
          this.submit_status = false
        })
      })
    },
    close() {
      this.$emit('close', false)
    }
  }
}
</script>
