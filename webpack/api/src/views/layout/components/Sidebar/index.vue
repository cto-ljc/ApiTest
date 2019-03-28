<template>
  <div class="layout-sidebar transition">
    <div class="search-box">
      <el-input v-model="keyword" placeholder="请输入内容" size="mini" class="search">
        <i slot="prefix" class="el-input__icon el-icon-search"/>
      </el-input>
      <el-dropdown class="add" @command="add_command">
        <el-button type="primary" size="mini">
          +
        </el-button>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item command="category">目录</el-dropdown-item>
          <el-dropdown-item command="api">请求</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
    <div v-if="api_list.length > 0" class="nav">
      <Item :option="api_list"/>
    </div>
    <div v-else class="nav" style="width:100%; height: 60px;">
      <div type="text" style="color:#cacaca; width:100%; text-align: center; line-height: 40px; user-select:none;">暂无栏目</div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Item from './item'
export default {
  components: {
    Item
  },
  data() {
    return {
      keyword: '',
      search_loading: false,
      form: {},
      clock_time: '',
      option: []
    }
  },
  computed: {
    ...mapGetters([
      'project',
      'project_list',
      'api_list'
    ])
  },
  watch: {
    keyword() {
      clearTimeout(this.clock_time)
      this.clock_time = setTimeout(() => {
        console.log(this.keyword)
      }, 500)
    }
  },
  created() {
    this.option = [
      {
        id: 1,
        name: 'test1',
        children: [
          {
            id: 11,
            name: 'test1—1',
            children: [
              {
                id: 111,
                name: 'test1-1-1',
                children: []
              }
            ],
            item: [
              {
                name: '栏目1-1-1'
              },
              {
                name: '栏目1-1-2'
              },
              {
                name: '栏目1-1-3'
              }
            ]
          },
          {
            id: 12,
            name: 'test1—2',
            children: []
          }
        ],
        item: [
          {
            name: '栏目1-1'
          },
          {
            name: '栏目1-2'
          },
          {
            name: '栏目1-3'
          }
        ]
      },
      {
        id: 2,
        name: 'test2',
        children: [],
        item: [
          {
            name: '栏目2'
          },
          {
            name: '栏目2'
          },
          {
            name: '栏目2'
          }
        ]
      },
      {
        id: 3,
        name: 'test3',
        children: []
      }
    ]
  },
  methods: {
    add_command(type) {
      switch (type) {
        case 'category':
          var category_form = {
            project_id: this.project.id,
            pid: 0
          }
          this.$store.dispatch('show_category_form', category_form)
          break
        case 'api':
          var api_form = {
            project_id: this.project.id,
            category_id: 0
          }
          this.$store.dispatch('show_api_form', api_form)
          break
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.layout-sidebar{
  .search-box{ padding: 5px 10px; padding-right: 50px; background: #f9f9f9; border-bottom: 1px solid #e2e2e2; position: relative;
    .search{ border-radius: 15px;}
    .add{ position: absolute;right: 5px; top: 5px;}
  }
}
</style>
