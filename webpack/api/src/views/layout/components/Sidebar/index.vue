<template>
  <div class="layout-sidebar">
    <div class="search-box">
      <el-input v-model="keyword" placeholder="请输入内容" size="mini" class="search">
        <i slot="prefix" class="el-input__icon el-icon-search"/>
      </el-input>
      <el-dropdown class="add">
        <el-button type="primary" size="mini">
          +
        </el-button>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item>目录</el-dropdown-item>
          <el-dropdown-item>请求</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
    <div class="nav">
      <Item :option="option"/>
    </div>
  </div>
</template>

<script>
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
