<template>
  <div>
    <div v-for="(folder, index) in option" :key="index" class="folder">
      <div :style="style" class="folder_name btn" @click="click_folder(folder)"><i :class="folder.show === true ? 'el-icon-arrow-down' : 'el-icon-arrow-up'" style="font-size: 10px;"/> {{ folder.name }}</div>
      <div :style="{height:folder.show === true ? 'auto' : 0}" style="overflow: hidden;">
        <Item v-if="folder.children.length > 0" :el="el + 1" :option="folder.children" />
        <div v-for="(item, i) in folder.item" :key="i" :style="item_style" class="item btn">{{ item.name }}</div>
      </div>
    </div>
  </div>
</template>

<script>
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
  created() {
    this.style.padding = '0px ' + (Number(this.el * 10) + Number(10)) + 'px'
    this.item_style.padding = '0px ' + (Number((Number(this.el) + 1) * 10) + Number(10)) + 'px'
  },
  methods: {
    click_folder(folder) {
      this.$set(folder, 'show', !folder.show)
    }
  }
}
</script>

<style lang="scss" scoped>
$hover_background_color:#f1f1f1;
$border_color:#ececec;
.folder{
  .folder_name:first-child{ }
  .folder_name{ height: 40px; line-height: 40px; border-bottom: 1px solid $border_color;}
  .folder_name:hover{ background-color: $hover_background_color;}
  .folder_name:last-child{ border-top: 1px solid red; }
  .item{ height: 30px; line-height: 30px; }
  .item:hover{ background-color: $hover_background_color; }
}
</style>
