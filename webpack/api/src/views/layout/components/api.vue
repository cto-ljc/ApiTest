<template>
  <div class="app-container">
    <el-card shadow="always">
      <div slot="header" class="clearfix">
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
              <el-input v-model="scope.row.key" placeholder="key" size="mini"/>
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
                <el-button class="delete" type="text" icon="el-icon-close"/>
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
    }
  },
  data() {
    return {
      api: {
        type: 'get',
        url: ''
      },
      param_data: []
    }
  },
  watch: {
    param_data() {
      console.log('param_data')
    }
  },
  created() {
    this.init()
  },
  methods: {
    init() {
      const empty_param = {
        key: '',
        value: '',
        description: ''
      }
      this.param_data.push(empty_param)
    },
    handleSelectionChange(val) {
      console.log(val)
    },
    save() {
      console.log(this.param_data)
    }
  }
}
</script>

<style lang="scss" scoped="">
.url{ position: relative; padding-right: 125px;
  .button-group{ position: absolute; right: 0; top: 0;}
}
.table{
  .description{ position: relative; padding-right: 28px;
    .delete{ position: absolute; right: 0; top: 0; height: 28px; line-height: 28px; padding: 0;}
  }
}
</style>
