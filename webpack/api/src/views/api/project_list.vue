<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span style="height:28px; line-height: 28px; display:inline-block;">项目列表</span>
        <!-- <el-button type="primary" size="mini">添加项目</el-button> -->
        <el-button type="primary" style="float:right;" size="mini" @click="$router.push({name:'app_form'})">添加项目</el-button>
      </div>
      <div>
        <el-table v-loading="loading" :data="app_list" style="width: 100%">
          <el-table-column prop="name" label="项目"/>
          <el-table-column prop="sort" label="排序" />
          <el-table-column label="操作" width="180">
            <template slot-scope="scope">
              <el-button type="text" size="small" @click="api(scope.row)">接口列表</el-button>
              <el-button type="text" size="small" @click="edit_app(scope.row)">编辑</el-button>
              <el-button type="text" size="small" style="color:red;" @click="del_app(scope.row)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </el-card>
  </div>
</template>

<script>
export default{
  data() {
    return {
      app_list: [],
      loading: false
    }
  },
  created() {
    this.init()
  },
  methods: {
    init() {
      this.get_data()
    },
    get_data() {
      this.loading = true
      this.$axios.post('/api/app/getList').then(response => {
        this.loading = false
        var res = response.data
        this.app_list = res.data.app_list.data
      })
    },
    api(app) {
      var app_id = app.id
      this.$router.push({ name: 'api_list', query: { app_id: app_id }})
    },
    edit_app(app) {
      var app_id = app.id
      this.$router.push({ name: 'app_form', query: { id: app_id }})
    },
    del_app(app) {
      this.$confirm('你将删除项目, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        var app_id = app.id
        var data = {}
        data.id = app_id

        this.$axios.post('/api/app/delete', data).then(res => {
          this.init()
          this.$message({
            type: 'success',
            message: '删除成功!'
          })
        })
      }).catch(() => {
        // 取消删除
      })
    }
  }
}
</script>
