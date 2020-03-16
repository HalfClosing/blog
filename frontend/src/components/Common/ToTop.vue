<template>
  <div class="to-top" :style="topStyle" @click.prevent="toTop()" v-show="showToTop">
    <i class="el-icon-top"></i>
  </div>
</template>

<script type="text/ecmascript-6">
export default {
  name: 'toTop',
  props: {
    config: Object
  },
  data() {
    return {
      container: {},
      showToTop: false,
      speed: 50
    };
  },
  computed: {
    topConfig() {
      return Object.assign({
        speedType: 'linear'
      }, this.config);
    },
    topStyle() {
      return {
        height: this.topConfig.height + 'px',
        width: this.topConfig.width + 'px',
        right: this.topConfig.position.right + 'px',
        bottom: this.topConfig.position.bottom + 'px'
      };
    }
  },
  methods: {
    handleScroll(e) {
      this.container = e.target;
      this.showToTop = (this.container.scrollTop > 500);
    },
    toTop() {
      // 滑动动画效果
      let delay = setInterval(() => {
        let speed = 0;
        if (this.topConfig.speedType === 'linear') {
          speed = this.speed;
        } else if (this.topConfig.speedType === 'ease-out') {
          speed = Math.floor(this.container.scrollTop / 16);
        }
        this.container.scrollTop -= speed || this.container.scrollTop;
        if (this.container.scrollTop <= 0) {
          clearInterval(delay);
        }
      }, 10);
    }
  },
  mounted() {
    window.addEventListener('scroll', this.handleScroll, true);
  },
  destroy() {
    window.removeEventListener('scroll', this.handleScroll);
  }
};
</script>

<style lang="scss">
  .to-top {
    position: fixed;
    z-index: 9999;
    background: #dedede;
    border-radius: 5px;
    text-align: center;
    transition: color linear 0.2s;
    .el-icon-top {
      margin-top: 8px;
      font-size: 36px;
      line-height: 40px;
      color: #FFF;
    }
  }
  .to-top:hover {
    cursor: pointer;
    background: #CCC;
  }
</style>
