<template>
  <div class="w960 mgt30">
    <el-cascader
      :options="options"
      change-on-select
    />
  </div>
</template>

<script scoped>
import area from  '@/assets/json/area.json';  //引入外部js文件
//注意：上述代码中的 areaJS 可以写为以下任意格式：areaJS/areaJs/areajs 
//建议：注意驼峰命名法的使用
export default {
  data() {
    return {
      options: area,  //areajson为引入外部js文件的json数组名称
    };
  }
};
</script>

<style>
</style>