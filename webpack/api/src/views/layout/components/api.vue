<template>
  <div class="app-container api-components">
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
            <el-button type="primary" size="mini" @click="send">send</el-button>
            <el-button type="primary" size="mini" @click="save">保存</el-button>
          </div>
        </div>
      </div>
      <div>
        请求参数：
        <el-table
          ref="api.param"
          :data="api.param"
          class="table"
          tooltip-effect="dark"
          style="width: 100%"
          @selection-change="handleSelectionChange">
          <el-table-column label="" width="30">
            <template slot-scope="scope">
              <el-checkbox v-model="scope.row.status"/>
            </template>
          </el-table-column>
          <el-table-column prop="key" label="key">
            <template slot-scope="scope">
              <el-input v-model="scope.row.key" :class="{'disabled': scope.row.status !== true}" placeholder="key" size="mini" @change="write_key($event, scope.$index)"/>
            </template>
          </el-table-column>
          <el-table-column prop="value" label="value">
            <template slot-scope="scope">
              <el-input v-model="scope.row.value" :class="{'disabled': scope.row.status !== true}" placeholder="value" size="mini"/>
            </template>
          </el-table-column>
          <el-table-column prop="description" label="备注">
            <template slot-scope="scope">
              <div class="description">
                <el-input v-model="scope.row.description" :class="{'disabled': scope.row.status !== true}" placeholder="备注" size="mini"/>
                <el-button v-if="scope.$index < (api.param.length - 1)" class="delete" type="text" icon="el-icon-close" @click="delete_param(scope.$index)"/>
              </div>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </el-card>
    <el-card v-if="request_status" style="margin-top: 15px;">
      <el-tabs v-model="active_return_view">
        <el-tab-pane label="json" name="json">
          <json-viewer :value="json_data" :expand-depth="5" boxed />
        </el-tab-pane>
        <el-tab-pane label="html" name="html">
          <div v-html="html_data"/>
        </el-tab-pane>
        <el-tab-pane label="source" name="source">角色管理</el-tab-pane>
      </el-tabs>
      <!-- <img :src="return_data"/> -->
    </el-card>
  </div>
</template>

<script>
import axios from 'axios'
import JsonViewer from 'vue-json-viewer'
export default {
  components: { JsonViewer },
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
          param: [],
          url: ''
        }
      },
      type: Object
    }
  },
  data() {
    return {
      api_rename_status: false,
      request_status: false,
      active_return_view: 'json',
      return_data: '',
      json_data: '',
      html_data: ''
    }
  },
  watch: {
    api_rename_status() {
      if (this.api_rename_status === true) {
        this.$nextTick(() => {
          this.$refs.api_rename.$el.querySelector('input').focus()
        })
      }
    }
  },
  created() {
    this.api.param = this.api.param ? this.api.param : []
    this.init()
  },
  methods: {
    init() {
      this.push_api_param()
    },
    api_rename() {
      this.api_rename_status = false
      console.log('api rename to ' + this.api.name)
    },
    push_api_param() {
      const empty_param = {
        key: '',
        value: '',
        description: ''
      }
      this.api.param.push(empty_param)
    },
    delete_param(index) {
      this.api.param.splice(index, 1)
    },
    handleSelectionChange(val) {
      console.log(val)
    },
    write_key(value, index) {
      if (index === (this.api.param.length - 1)) {
        this.api.param[index].status = true
        this.push_api_param()
      }
    },
    save() {
      const data = JSON.parse(JSON.stringify(this.api))

      data.param.splice(data.param.length - 1, 1)

      this.$request.post('/api/api/update', data).then((res) => {
        this.$store.dispatch('update_api', data)
        console.log(res)
      }).catch(() => {})
      console.log(this.api)
    },
    send() {
      const api = this.api

      if (api.url === '') {
        return
      }
      const param = {}
      api.param.forEach(item => {
        if (item.key) {
          param[item.key] = item.value
        }
      })

      const request = axios.create()
      request.interceptors.response.use(
        response => {
          return response
          // return 'data:image/png;base64,' + btoa(
          //   new Uint8Array(response.data).reduce((data, byte) => data + String.fromCharCode(byte), '')
          // )
        },
        error => {
          console.log(error)
        }
      )

      this.request_status = false
      request({
        method: api.type,
        url: api.url,
        data: param,
        responseType: 'arraybuffer'
      }).then(response => {
        this.request_status = true
        const data = String.fromCharCode.apply(null, new Uint8Array(response.data))
        this.html_data = data
        this.json_data = JSON.parse(data)
        this.data = response.data
      })
    }
  }
}
</script>
<style lang="scss">
.api-components{
  .disabled{
    input{color: #e0e0e0;}
  }
  .el-table--enable-row-hover .el-table__body tr:hover>td{ background-color: inherit; }
}
</style>
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
