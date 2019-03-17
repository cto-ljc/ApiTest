<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <el-select v-model="app_id" placeholder="请选择" size="mini">
          <el-option v-for="item in app_list" :key="item.id" :label="item.name" :value="item.id"/>
        </el-select>
        <el-cascader :options="api_data" placeholder="api列表" filterable size="mini" />
        <!-- <el-button type="primary" size="mini">添加项目</el-button> -->
        <el-dropdown split-button type="primary" size="mini" style="float:right;" @command="app_handle_command" @click="add_app">
          添加项目
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item command="app_list">项目管理</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </div>
      <el-form ref="api" :model="api_form" :label-width="api_form_label_width" size="mini" label-position="left">
        <el-form-item v-for="(item,index) in api_item" :label="item.label + (item.remark ? '（' + item.remark + '）' : '') + '：'" :key="index" >
          <el-input v-model="api_form[item.field]" />
        </el-form-item>
        <mu-button class="btn" color="primary" @click="submit" >提交</mu-button>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default{
  data() {
    return {
      app_list: [{
        id: 1,
        name: '测试项目'
      }],
      app_id: 1,
      api_item: [
        {
          label: '邮箱不是吧',
          field: 'email',
          remark: ''
        }, {
          label: '密码aaa',
          field: 'password',
          remark: '备注备注备注'
        }
      ],

      // api信息
      api_data: [{
        value: '0',
        label: '测试栏目',
        children: [
          {
            value: '1',
            label: '测试api'
          }
        ]
      }, {
        value: '3',
        label: '测试api2'
      }],

      // api表单
      api_form: {},
      api_form_label_width: ''
    }
  },
  computed: {
    ...mapState([
      'user'
    ])
  },
  created() {
    this.init()
  },
  methods: {
    init() {
      if (this.user && this.user.uid) {
        console.log('请求接口')
      } else {
        console.log('不用请求')
      }
    },
    add_app() {
      console.log('添加项目')
    },
    submit() {
      console.log(this.api_form)
    },
    // 下拉菜单点击事件
    app_handle_command(command) {
      console.log(command)
      switch (command) {
        case 'app_list':
          this.$router.push({ name: 'app_list' })
          break
      }
    }
  }
}
</script>

<style scoped>
.content { max-width: 800px; margin: auto;}
.el-form-item--mini.el-form-item, .el-form-item--small.el-form-item{ margin-bottom: 10px; }
</style>
