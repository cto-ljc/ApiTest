<template>
  <div class="app-wrapper">
    <Header/>
    <div class="layout_content">
      <Sidebar/>
      <AppMain/>
    </div>
  </div>
</template>

<script>
import { Header, Sidebar, AppMain } from './components'
export default {
  components: {
    Header,
    Sidebar,
    AppMain
  },
  data() {
    return {}
  },
  created() {
  },
  methods: {}
}
</script>
<style lang="scss">
@import "@/styles/index.scss";
.layout-header{ position: absolute; top: 0; width: 100%; height: $layoutHeaderHeight; text-align: center;}
.layout_content{ position: absolute; top: 50px; bottom: 0; width: 100%;
 .layout-sidebar{ position: absolute; top: 0; left: 0; bottom: 0; width: $layoutSidebarWidth; box-shadow: 2px 0px 2px 0px #ccc;}
 .layout-main{ position: absolute; top: 0; right: 0; bottom: 0; left: $layoutSidebarWidth;}
}
</style>
