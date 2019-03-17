<template>
  <el-dialog :visible.sync="visible" :close-on-click-modal="false" class="login-dialog" title="用户登录" width="400px" @close="close">
    <el-form ref="login" :model="login_form" :rules="login_rules" label-position="left" label-width="55px">
      <el-form-item label="邮箱" prop="email">
        <el-input v-model="login_form.email" />
      </el-form-item>
      <el-form-item label="密码" prop="password">
        <el-input v-model="login_form.password" type="password" />
      </el-form-item>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <mu-button full-width class="btn" color="primary" @click="submit">确 定</mu-button>
    </div>
  </el-dialog>
</template>

<script>
export default {
  data() {
    return {
      visible: false,
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
      }
    }
  },
  watch: {
    visible() {
      console.log(this.visible)
      if (this.visible === false) {
        this.$refs['login'].resetFields()
      }
    }
  },
  methods: {
    submit() {},
    close() {
      this.$emit('close', false)
    }
  }
}
</script>
