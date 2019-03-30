<template>
  <div class="app-container">
    <!-- <div shadow="always" class="api-name-card">
      接口名称
    </div> -->
    <el-card shadow="always">
      <div slot="header" class="header">
        <div class="api-name">
          <span v-if="api_rename_status === false">
            {{ api.name }}
            <i v-if="api.id" class="el-icon-edit edit_btn btn" type="text" size="mini" @click="api_rename_status = true"/>
          </span>
          <el-input v-else ref="api_rename" v-model="api.name" :focus="api_rename_status" placeholder="request name" size="mini" style="width:300px;" @blur="api_rename"/>
        </div>
        <div class="url">
          <el-input v-model="api.url" placeholder="请求地址" class="input-with-select" size="mini">
            <el-select slot="prepend" v-model="api.type" placeholder="请选择" style="width: 80px;">
              <el-option label="get" value="get"/>
              <el-option label="post" value="post"/>
            </el-select>
          </el-input>
          <div class="button-group">
            <el-button type="primary" size="mini">send</el-button>
            <el-button type="primary" size="mini" @click="save">保存</el-button>
          </div>
        </div>
      </div>
      <div>
        <el-table
          ref="param_data"
          :data="param_data"
          class="table"
          tooltip-effect="dark"
          style="width: 100%"
          @selection-change="handleSelectionChange">
          <el-table-column prop="key" label="key">
            <template slot-scope="scope">
              <!-- <el-input v-if="scope.$index === (param_data.length - 1)" v-model="scope.row.key" placeholder="key2" size="mini"/>
              <el-input v-else v-model="scope.row.key" placeholder="key" size="mini"/> -->
              <el-input v-model="scope.row.key" placeholder="key" size="mini" @change="write_key($event, scope.$index)"/>
            </template>
          </el-table-column>
          <el-table-column prop="value" label="value">
            <template slot-scope="scope">
              <el-input v-model="scope.row.value" placeholder="value" size="mini"/>
            </template>
          </el-table-column>
          <el-table-column prop="description" label="备注">
            <template slot-scope="scope">
              <div class="description">
                <el-input v-model="scope.row.description" placeholder="value" size="mini"/>
                <el-button v-if="scope.$index < (param_data.length - 1)" class="delete" type="text" icon="el-icon-close" @click="delete_param(scope.$index)"/>
              </div>
            </template>
          </el-table-column>
          <!-- <el-table-column label="" width="40" show-overflow-tooltip>
            <template slot-scope="scope"></template>
          </el-table-column> -->
          <!-- <el-table-column prop="name" label="姓名" width="120"/>
          <el-table-column prop="address" label="地址" show-overflow-tooltip/> -->
        </el-table>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      default: 0,
      type: Number
    },
    api: {
      default: () => {
        return {
          id: 0,
          name: 'untitled request',
          type: 'get',
          url: ''
        }
      },
      type: Object
    }
  },
  data() {
    return {
      api_rename_status: false,
      param_data: []
    }
  },
  watch: {
    param_data() {
      console.log('param_data')
    },
    api_rename_status() {
      if (this.api_rename_status === true) {
        this.$nextTick(() => {
          this.$refs.api_rename.$el.querySelector('input').focus()
        })
      }
    }
  },
  created() {
    this.init()
  },
  methods: {
    init() {
      this.push_param_data()
    },
    api_rename() {
      this.api_rename_status = false
      console.log('api rename to ' + this.api.name)
    },
    push_param_data() {
      const empty_param = {
        key: '',
        value: '',
        description: ''
      }
      this.param_data.push(empty_param)
    },
    delete_param(index) {
      this.param_data.splice(index, 1)
    },
    handleSelectionChange(val) {
      console.log(val)
    },
    write_key(value, index) {
      // this.$set(this.param_data[index], 'key', value)
      console.log(this.param_data[index].key)
      if (index === (this.param_data.length - 1)) {
        this.push_param_data()
      }
    },
    save() {
      console.log(this.param_data)
    }
  }
}
</script>

<style lang="scss" scoped>
@import "@/styles/index.scss";
.api-name-card{margin-bottom:10px; box-shadow: 0 2px 12px 0 rgba(0,0,0,.1); padding:15px 20px;}

.header{
  .api-name{ margin-bottom: 10px;
    .edit_btn{ color:$primary-color; }
  }
  .url{ position: relative; padding-right: 125px;
    .button-group{ position: absolute; right: 0; top: 0;}
  }
}

.table{
  .description{ position: relative; padding-right: 28px;
    .delete{ position: absolute; right: 0; top: 0; height: 28px; line-height: 28px; padding: 0;}
  }
}
</style>
