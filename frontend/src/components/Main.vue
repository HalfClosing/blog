<template>
  <el-main>
    <keep-alive>
      <router-view />
    </keep-alive>
    <div class="footer-container" v-if="!isHideFooter">
      <el-divider></el-divider>
      <div class="intro">
        Welcome to HalfClosing Blog !
      </div>
      <div class="copyright">
        Copyright © 2019 HalfClosing Blog. All Rights Reserved.
      </div>
    </div>
  </el-main>
</template>

<script type="text/ecmascript-6">
export default {
  name: 'blogMain',
  computed: {
    isHideFooter() {
      return this.$route.path.split('/')[1] === 'admin';
    }
  }
};
</script>

<style lang="scss">
.el-main {
  width: 100%;
  padding: 0 !important;
  background: #EEE;
  overflow-x: hidden !important;
  .footer-container {
    display: inline-block;
    width: 100%;
    height: 200px;
    color: #D2D3D6;
    text-align: center;
    background: #444;
    border-top: 3px solid rgb(255, 208, 75);
    .el-divider {
      margin-top: 50px;
    }
    .intro {
      margin-top: 40px;
    }
    .copyright {
      margin-top: 10px;
    }
  }
}
</style>
