<template>
  <el-dialog :visible.sync="visible" :close-on-click-modal="false" :title="title" class="form-dialog" width="600px" @close="close">
    <el-form ref="form" :model="form" :rules="rules" label-position="left" label-width="85px">
      <el-form-item label="接口名称" prop="name">
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
      title: '',
      visible: false,
      form: {
        name: ''
      },
      submit_status: false,
      rules: {
        name: [
          { required: true, message: '请填写接口名称', trigger: 'blur' }
        ]
      }
    }
  },
  computed: {
    ...mapGetters([
      'api_form'
    ])
  },
  watch: {
    visible() {
      if (this.visible === false) {
        this.init()
      }
    },
    api_form() {
      this.form = this.api_form
      this.title = this.form.id ? '编辑接口' : '添加接口'
    }
  },
  methods: {
    init() {
      this.$refs['form'].resetFields()
      this.submit_status = false
    },
    submit() {
      console.log(this.form)
      if (this.submit_status === true) {
        return
      }
      this.$refs['form'].validate((valid) => {
        if (!valid) {
          return false
        }

        const url = this.form.id ? '/api/api/update' : '/api/api/append'
        const dispatch = this.form.id ? 'update_api' : 'append_api'

        this.submit_status = true
        this.$request.post(url, this.form).then(res => {
          this.$message({
            message: res.msg,
            type: 'success'
          })

          const api = res.data.api
          this.$store.dispatch(dispatch, api)
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
