<template>
  <div v-loading="loading" style="padding:15px;">
    <div style="width:400px;">
      <el-form ref="form" :model="form" :rules="rules" label-width="80px" size="mini" >
        <el-form-item label="上级" prop="category_id">
          <el-select v-model="form.category_id" placeholder="请选择上级栏目" size="mini">
            <el-option label="无" value="0"/>
            <el-option v-for="item in category" :key="item.value" :label="item.label" :value="item.value"/>
          </el-select>
        </el-form-item>
        <el-form-item label="名称" prop="name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="字段" prop="field">
          <el-input v-model="form.field" />
        </el-form-item>
        <el-form-item label="备注" prop="remark">
          <el-input v-model="form.remark" />
        </el-form-item>
        <el-form-item label="排序">
          <el-input v-model="form.sort"/>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" size="mini" @click="submit">保存</el-button>
          <el-button size="mini" @click="$router.go(-1)">返回</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
export default{
  data() {
    return {
      category: [],
      form: {},
      rules: {
        name: [
          { required: true, message: '请填写栏目名称', trigger: 'blur' }
        ],
        field: [
          { required: true, message: '请填写字段名称', trigger: 'blur' }
        ]
      },
      loading: false
    }
  },
  created() {
    this.init()
  },
  methods: {
    init() {
      this.form = {}

      var id = this.$route.query.id
      if (id) {
        this.loading = true
        this.form.id = id
        this.get_data()
      }
    },
    get_data() {
      var data = {}
      data.id = this.form.id
      this.$axios.post('/api/api/item', data).then(response => {
        this.loading = false
        var res = response.data
        if (res.success) {
          var app = res.data.app
          app.main = JSON.parse(app.main_json)
          this.form = app
        } else {
          this.$message.warning(res.msg)
        }
      })
    },
    submit() {
      this.$refs['form'].validate((valid) => {
        if (!valid) {
          return false
        }

        var data = this.form
        let url = ''
        if (this.form.id) {
          url = '/api/api/update'
        } else {
          url = '/api/api/append'
        }

        this.$axios.post(url, data).then(response => {
          var res = response.data
          if (res.success) {
            // this.init()
            this.$message.success(res.msg)
            this.$router.go(-1)
          } else {
            this.$message.warning(res.msg)
          }
        })
      })
    }
  }
}
</script>

<style scoped>
.main_input_block{ margin-bottom: 10px; padding-right: 60px; position: relative; }
.main_input_block .del_main_btn{ position: absolute; top: 0; right: 0; }
</style>
