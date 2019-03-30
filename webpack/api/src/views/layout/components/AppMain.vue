<template>
  <div :class="{'show-tab': api_view_list.length > 0}" class="layout-main transition">
    <div class="tab transition">
      <scroll-pane ref="scrollPane" class="tags-view-wrapper">
        <div ref="tag" :class="{'active': api_view_id == 0}" class="tags-view-item btn" @click="open_api(0)">default api</div>
        <div v-for="tag in api_view_list" ref="tag" :class="{'active': tag.id == api_view_id}" :active="tag.active" :key="tag.path" class="tags-view-item btn" @click="open_api(tag.id)">
          {{ tag.name }}
          <span class="delete el-icon-close btn" @click.stop="close_tag(tag.id)"/>
        </div>
      </scroll-pane>
    </div>
    <section v-show="0 == api_view_id" class="api-view" >
      <Api :id="0"/>
    </section>
    <section v-for="(api,index) in api_view_list" v-show="api.id == api_view_id" :key="index" class="api-view" >
      <Api :api="api"/>
    </section>
  </div>
</template>

<script>
import ScrollPane from './TageView/ScrollPane'
import Api from './api'
import { mapGetters } from 'vuex'
export default {
  name: 'AppMain',
  components: { ScrollPane, Api },
  data() {
    return {
      tabs_index: ''
    }
  },
  computed: {
    ...mapGetters([
      'api_view_list',
      'api_view_id'
    ])
  },
  watch: {
    api_view_list() {
      this.$nextTick(() => {
        this.moveToCurrentTag()
      })
    },
    api_view_id() {
      this.tabs_index = String(this.api_view_id)
    }
  },
  methods: {
    handleTabsEdit(targetName, action) {
      console.log(targetName)
      console.log(action)
    },
    open_api(id) {
      this.$store.dispatch('set_api_view_id', id)
    },
    close_tag(id) {
      this.$store.dispatch('delete_api_view', id)
      console.log(id)
    },
    moveToCurrentTag() {
      const tags = this.$refs.tag
      this.$nextTick(() => {
        for (const tag of tags) {
          if (tag.attributes.active && tag.attributes.active.nodeValue === 'true') {
            this.$refs.scrollPane.moveToTarget(tag)
            break
          }
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import "@/styles/index.scss";
$tab_height: 40px;
.layout-main { overflow: hidden;
  .tab{ height: 0;
    .tags-view-item{ display: none; }
  }
  .api-view{ top: 0; position: absolute; width: 100%; bottom: 0;}
}
.layout-main.show-tab{
  .tab{ height: $tab_height; padding: 4px 10px; line-height: 30px; border-bottom: 1px solid $border_color; box-shadow: 0 1px 5px 0px #ccc; position: absolute; width: 100%; top: 0;
    .tags-view-item{display: inline-block; margin: 0 3px; box-shadow: 0 0 3px 0px #ccc; box-sizing: border-box; height: 24px; line-height: 24px; padding: 0 10px; color: #696969;
      .delete:hover{ background-color: #ff5858; border-radius:10px; color: white;}
    }
    .tags-view-item.active{ background-color: #409eff; color: white; box-shadow: 0 0 3px 0px #409eff;}
  }
  .api-view{ top: $tab_height; }
}
</style>
