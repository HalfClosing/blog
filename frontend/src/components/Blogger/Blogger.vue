<template>
  <div class="blogger-container">
    <el-card class="box-card">
      <div slot="header" class="title">
        博客信息
      </div>
      <div class="content-block">
        <div class="row">
          <span>博主：</span>
          <span>HalfClosing</span>
        </div>
        <div class="row">
          <span>简介：</span>
          <span>最后的倔强...</span>
        </div>
        <div class="row" style="display:none;">
          <span>邮箱：</span>
          <span>www.halfclosing@outlook.com</span>
        </div>
      </div>
    </el-card>
  </div>
</template>

<script type="text/ecmascript-6">
export default {
  name: 'blogger'
};
</script>

<style lang="scss">
.blogger-container {
  margin: 20px 10%;
  min-height: 80%;
  .title {
    text-align: center;
  }
  .content-block {
    margin-bottom: 20px;
    .row {
      margin-left: 35%;
      margin-top: 10px;
    }
  }
}
</style>
