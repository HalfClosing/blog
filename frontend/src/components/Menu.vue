<template>
  <el-header class="header-container">
    <el-menu
            :default-active="activeIndex"
            :router="true"
            mode="horizontal"
            text-color="#fff"
            active-text-color="#ffd04b"
            :background-color="backgroundColor"
            class="el-menu-demo"
    >
      <el-menu-item index="/blogger">
        <template slot="title">HalfClosing</template>
        <img class="user-image" src="../assets/user.jpg" style="width: 36px; height: 36px;">
      </el-menu-item>
      <el-menu-item index="/home">主页</el-menu-item>
    </el-menu>
  </el-header>
</template>

<script type="text/ecmascript-6">
export default {
  name: 'blogMenu',
  data() {
    return {
      backgroundColorMaps: ['#444444']
    };
  },
  computed: {
    backgroundColor() {
      return this.backgroundColorMaps[0];
    },
    activeIndex() {
      const routeName = this.$route.name;
      const pathName = this.$route.path.split('/')[1];
      const path = routeName === pathName || pathName.length === 0 ? routeName : pathName;
      return `/${path}`;
    }
  },
  methods: {}
};
</script>

<style lang="scss">
.el-header {
  padding: 0 !important;
}
.header-container {
  width: 100%;
  .el-menu {
    border-bottom: none !important;
    box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.8);
    .el-menu-item {
      float: right;
      .user-image {
        margin-right: 5px;
        border: 2px solid #ccc;
        border-radius: 50%;
      }
    }
  }
}
</style>
