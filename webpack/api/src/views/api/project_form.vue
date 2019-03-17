<template>
  <div v-loading="loading" style="padding:15px;">
    <div style="width:400px;">
      <el-form ref="form" :model="form" :rules="rules" label-width="80px" size="mini" >
        <el-form-item label="名称" prop="name">
          <el-input v-model="form.name"/>
        </el-form-item>
        <el-form-item label="排序">
          <el-input v-model="form.sort"/>
        </el-form-item>
        <el-form-item label="域名">
          <div v-for="(item,index) in form.main" :key="index" class="main_input_block">
            <el-input v-model="form.main[index]"/>
            <el-button type="primary" class="del_main_btn" size="mini" @click="del_main(index)">删除</el-button>
          </div>
          <div class="main_input_block">
            <el-button type="primary" size="mini" icon="el-icon-plus" @click="add_main"/>
          </div>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" size="mini" @click="submit" >保存</el-button>
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
      form: {},
      rules: {
        name: [
          { required: true, message: '请填写项目名称', trigger: 'blur' }
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
      this.$axios.post('/api/app/item', data).then(response => {
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

        // this.init_form_main()
        var data = this.form
        var main_array = this.valid_main()
        if (!main_array.length) {
          this.$alert('请至少填写一个有效域名')
          return
        }
        data.main_json = JSON.stringify(main_array)

        let url = ''
        if (this.form.id) {
          url = '/api/app/update'
        } else {
          url = '/api/app/append'
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
    },
    valid_main() {
      var strRegex = '^((https|http|ftp|rtsp|mms)?://)' +
      '?(([0-9a-z_!~*\'().&amp;=+$%-]+: )?[0-9a-z_!~*\'().&amp;=+$%-]+@)?' + // ftp的user@
      '(([0-9]{1,3}\.){3}[0-9]{1,3}' + // IP形式的URL- 199.194.52.184
      '|' // 允许IP和DOMAIN（域名）
      '([0-9a-z_!~*\'()-]+\.)*' + // 域名- www.
      '([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.' + // 二级域名
      '[a-z]{2,6})' + // first level domain- .com or .museum
      '(:[0-9]{1,4})?' + // 端口- :80
      '((/?)|' + // a slash isn't required if there is no file name
      '(/[0-9a-z_!~*\'().;?:@&amp;=+$,%#-]+)+/?)$'
      var re = new RegExp(strRegex)

      var main_array = []
      for (var i in this.form.main) {
        var main = this.form.main[i]
        // 域名校验
        if (re.test(main)) {
          main_array.push(main)
        }
      }
      return main_array
    },
    init_form_main() {
      var id_array = []
      for (const i in this.form.main) {
        var main = this.form.main[i]
        if (main === '') {
          id_array.unshift(i)
        }
      }
      for (const i in id_array) {
        var index = id_array[i]
        this.form.main.splice(index, 1)
      }
    },
    add_main() {
      if (this.form.main) {
        this.form.main.push('')
      } else {
        this.$set(this.form, 'main', [''])
      }
    },
    del_main(index) {
      this.form.main.splice(index, 1)
    }
  }
}
</script>

<style scoped>
.main_input_block{ margin-bottom: 10px; padding-right: 60px; position: relative; }
.main_input_block .del_main_btn{ position: absolute; top: 0; right: 0; }
</style>
