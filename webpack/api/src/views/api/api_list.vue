<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span style="height:28px; line-height: 28px; display:inline-block;">接口列表</span>
        <!-- <el-button type="primary" size="mini">添加项目</el-button> -->
        <div style="display:inline-block; float:right;">
          <el-select v-model="category_index" placeholder="请选择" size="mini">
            <el-option v-for="item in category" :key="item.value" :label="item.label" :value="item.value"/>
          </el-select>

          <el-button type="primary" size="mini" @click="$router.push({name:'api_form',query:{app_id:app_id}})">添加接口</el-button>
          <el-button type="primary" size="mini" @click="$router.push({name:'api_item_form',query:{app_id:app_id}})">添加栏目</el-button>
        </div>
      </div>
      <div>
        <el-table v-loading="loading" :data="api_list" style="width: 100%">
          <el-table-column prop="name" label="名称" />
          <el-table-column prop="sort" label="排序" />
          <el-table-column label="操作" width="180">
            <template slot-scope="scope">
              <el-button type="text" size="small" @click="edit_api(scope.row)">编辑</el-button>
              <el-button type="text" size="small" style="color:red;" @click="del_api(scope.row)">删除</el-button>
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
      app_id: '',
      api_list: [],

      category_index: '',
      category: [],
      loading: false
    }
  },
  created() {
    this.init()
  },
  methods: {
    init() {
      this.app_id = this.$route.query.app_id
      this.get_data()
    },
    get_data() {
      this.loading = true
      this.$axios.post('/api/api/getList').then(response => {
        this.loading = false
        var res = response.data
        this.app_list = res.data.app_list.data
      })
    }
  }
}
</script>
