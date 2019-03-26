<template>
  <div>
    main
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default{
  data() {
    return {}
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
      // if (this.user && this.user.uid) {
      //   console.log('已登录')
      // } else {
      //   console.log('未登陆')
      // }
    },
    submit() {
      console.log(this.api_form)
    }
  }
}
</script>

<style scoped>
.content { max-width: 800px; margin: auto;}
.el-form-item--mini.el-form-item, .el-form-item--small.el-form-item{ margin-bottom: 10px; }
</style>
