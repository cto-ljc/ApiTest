<template>
  <el-dialog :visible.sync="visible" :close-on-click-modal="false" class="form-dialog" title="添加请求" width="600px" @close="close">
    <el-form ref="form" :model="form" :rules="rules" label-position="left" label-width="85px">
      <el-form-item label="名称" prop="name">
        <el-input v-model="form.name" />
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
      form: {
        name: ''
      },
      submit_status: false,
      rules: {
        name: [
          { required: true, message: '请填写项目名称', trigger: 'blur' }
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
      this.$refs['form'].resetFields()
      this.submit_status = false
    },
    submit() {
      if (this.submit_status === true) {
        return
      }
      this.$refs['form'].validate((valid) => {
        if (!valid) {
          return false
        }

        this.submit_status = true
        this.$request.post('/api/project/append', this.form).then(res => {
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

<style lang="scss">

</style>
