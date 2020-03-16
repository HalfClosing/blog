<template>
  <div id="app">
    <el-container class="is-vertical">
      <blog-menu v-if="!isHideMenu" />
      <blog-main />
    </el-container>
  </div>
</template>

<script type="text/ecmascript-6">
import BlogMenu from '@/components/Menu';
import BlogMain from '@/components/Main';

export default {
  name: 'app',
  computed: {
    isHideMenu() {
      return this.$route.path.split('/')[1] === 'admin';
    }
  },
  components: {
    BlogMenu,
    BlogMain
  }
};
</script>

<style lang="scss">
html, body, #app{
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
}

.el-container {
  width: 100%;
  height: 100%;
}
</style>
