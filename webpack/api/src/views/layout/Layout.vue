<template>
  <div class="app-wrapper">
    <Header/>
    <div class="layout_content">layout</div>
  </div>
</template>

<script>
import { Header } from './components'
export default {
  components: {
    Header
  },
  data() {
    return {}
  }
}
</script>
<style lang="scss" scoped>
@import "@/styles/index.scss";
.layout_content{ position: absolute; top: 50px; bottom: 0; width: 100%; }
</style>
