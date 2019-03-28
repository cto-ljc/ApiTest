<template>
  <el-dialog :visible.sync="visible" :close-on-click-modal="false" :title="title" class="form-dialog" width="500px" @close="close">
    <el-form ref="form" :model="form" :rules="rules" label-position="left" label-width="85px">
      <el-form-item label="目录名称" prop="name">
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
      title: '添加目录',
      visible: false,
      form: {},
      submit_status: false,
      rules: {
        name: [
          { required: true, message: '请填写目录名称', trigger: 'blur' }
        ]
      }
    }
  },
  computed: {
    ...mapGetters([
      'category_form'
    ])
  },
  watch: {
    visible() {
      if (this.visible === false) {
        this.init()
      }
    },
    category_form() {
      this.form = this.category_form
      this.title = this.form.id ? '编辑目录' : '添加目录'
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

        const url = this.form.id ? '/api/api_category/update' : '/api/api_category/append'
        const dispatch = this.form.id ? 'update_api_category' : 'append_api_category'

        this.submit_status = true
        this.$request.post(url, this.form).then(res => {
          this.$message({
            message: res.msg,
            type: 'success'
          })
          const category = res.data.category
          this.$store.dispatch(dispatch, category)
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
