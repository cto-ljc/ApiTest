<template>
  <div>
    <div v-for="(folder, index) in option" :key="index" class="folder">
      <div :style="style" class="folder_name btn" @click="click_folder(folder)"><i :class="folder.show === true ? 'el-icon-arrow-down' : 'el-icon-arrow-up'" style="font-size: 10px;"/>
        {{ folder.name }}
        <div class="dropdown" @click.stop="">
          <el-dropdown trigger="click" size="mini" @command="category_command">
            <el-button type="text" size="mini" title="操作" style="width:30px; color:#000000;" >
              ···
            </el-button>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item v-if="el<3" :command="{type: 'append_category', folder}">添加子目录</el-dropdown-item>
              <el-dropdown-item :command="{type: 'append_api', folder}">添加请求</el-dropdown-item>
              <el-dropdown-item :command="{type: 'rename', folder}">重命名</el-dropdown-item>
              <el-dropdown-item :command="{type: 'del', folder}">删除</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </div>
      </div>
      <div :style="{height:folder.show === true ? 'auto' : 0,overflow:folder.show === true ? '' : 'hidden'}">
        <Item v-if="folder.children.length > 0" :el="el + 1" :option="folder.children" />
        <div v-for="(item, i) in folder.item" :key="i" :style="item_style" class="item btn" @click="click_api(item)">
          {{ item.name }}
          <div class="dropdown" @click.stop="">
            <el-dropdown trigger="click" size="mini" @command="api_command">
              <el-button class="operation btn" type="text" size="mini" title="操作">
                ···
              </el-button>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item :command="{type: 'rename', api: item}">重命名</el-dropdown-item>
                <el-dropdown-item :command="{type: 'del', api: item}">删除</el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'Item',
  props: {
    option: {
      type: Array,
      default: () => []
    },
    el: {
      type: Number,
      default: 0,
      required: false
    }
  },
  data() {
    return {
      prefix: '',
      style: {},
      item_style: {}
    }
  },
  computed: {
    ...mapGetters([
      'project'
    ])
  },
  created() {
    this.style.padding = '0px 5px 0px ' + (Number(this.el * 15) + Number(10)) + 'px'
    this.item_style.padding = '0px 5px 0px ' + (Number((Number(this.el) + 1) * 15) + Number(12)) + 'px'
  },
  methods: {
    click_folder(folder) {
      this.$set(folder, 'show', !folder.show)
    },
    category_command(command) {
      const type = command.type
      const folder = command.folder

      switch (type) {
        case 'append_category':
          var category_form = {
            project_id: this.project.id,
            pid: folder.id
          }
          this.$store.dispatch('show_category_form', category_form)
          break
        case 'append_api':
          var api_form = {
            project_id: this.project.id,
            category_id: folder.id
          }
          this.$store.dispatch('show_api_form', api_form)
          break
        case 'rename':
          this.$store.dispatch('show_category_form', JSON.parse(JSON.stringify(folder)))
          break
        case 'del':
          this.$confirm('删除此栏目?', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            const id_array = this.get_option_id(folder)
            const data = { id_array }
            this.$request.post('/api/api_category/batchDelete', data).then(() => {
              this.$store.dispatch('delete_api_category', folder.id)
              this.$message({
                type: 'success',
                message: '删除成功!'
              })
            }).catch(() => {})
          }).catch(() => {})
          break
      }
    },
    api_command(command) {
      const type = command.type
      const api = command.api

      switch (type) {
        case 'rename':
          this.$store.dispatch('show_api_form', JSON.parse(JSON.stringify(api)))
          break
        case 'del':
          this.$confirm('删除此接口?', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            const data = { id: api.id }
            this.$request.post('/api/api/delete', data).then(() => {
              console.log(123321123123)
              this.$store.dispatch('delete_api', api)
              this.$store.dispatch('delete_api_view', api.id)
              this.$message({
                type: 'success',
                message: '删除成功!'
              })
            }).catch(() => {})
          }).catch(() => {})
          break
      }
    },
    // 获取所有子集（包括自己）的id
    get_option_id(folder) {
      var id_array = []
      if (folder.children.length > 0) {
        folder.children.forEach(item => {
          id_array = id_array.concat(this.get_option_id(item))
        })
      }
      id_array.push(folder.id)
      return id_array
    },
    // 点击api列表
    click_api(api) {
      this.$store.dispatch('append_api_view', JSON.parse(JSON.stringify(api)))
    }
  }
}
</script>

<style lang="scss" scoped>
@import "@/styles/index.scss";
$hover_background_color:#f1f1f1;
.folder{ position: relative;
  .dropdown { position: absolute; top: 1px; width: 28px; height: 30px;  right: 0; opacity: 0;}
  .folder_name:first-child{ }
  .folder_name{ height: 40px; line-height: 40px; border-bottom: 1px solid $border_color;}
  .folder_name:hover{ box-shadow: 0px 1px 5px 1px #e8e8e8;
    .dropdown{ opacity: 1; }
  }
  .folder_name:last-child{ border-top: 1px solid red; }
  .item{ height: 30px; line-height: 30px; position: relative;
    .dropdown{
      .operation{ color:#000; width: 28px;  }
    }
  }
  .item:last-child{ border-bottom:1px solid $border_color;}
  // .item:hover{ background-color: $hover_background_color; }
  .item:hover{ box-shadow: 0px 1px 5px 1px #e8e8e8;
    .dropdown{ opacity: 1; }
  }
}
</style>
