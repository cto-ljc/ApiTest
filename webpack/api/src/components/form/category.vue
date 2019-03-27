<template>
  <el-dialog :visible.sync="visible" :close-on-click-modal="false" class="form-dialog" title="添加目录" width="500px" @close="close">
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
import { mapGetters } from 'vuex'
export default {
  data() {
    return {
      visible: false,
      form: {
        name: '',
        project_id: 0,
        pid: 0
      },
      submit_status: false,
      rules: {
        name: [
          { required: true, message: '请填写项目名称', trigger: 'blur' }
        ]
      }
    }
  },
  computed: {
    ...mapGetters([
      'project'
    ])
  },
  watch: {
    visible() {
      if (this.visible === false) {
        this.init()
      }
    }
  },
  created() {
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

        this.form.project_id = this.project.id
        console.log(this.form)

        // this.submit_status = true
        // this.$request.post('/api/api_category/append', this.form).then(res => {
        //   this.$message({
        //     message: res.msg,
        //     type: 'success'
        //   })
        //   this.$store.dispatch('set_init_layout', new Date())
        //   this.visible = false
        // }).catch(error => {
        //   console.log(error)
        //   this.submit_status = false
        // })
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
