<template>
  <div class="weather-wrapper shadow-hover">
    <div class="weather sunny" :style="weatherStyle" v-if="type === 'sunny'"></div>
    <div class="weather cloudy" v-if="type === 'cloudy'"></div>
    <div class="weather rainy" v-if="type === 'rainy'"></div>
    <div class="weather snowy" v-if="type === 'snowy'"></div>
  </div>
</template>

<script type="text/ecmascript-6">
export default {
  name: 'weather',
  props: {
    type: String,
    size: Object
  },
  computed: {
    weatherStyle() {
      return {
        width: this.size.width + 'px',
        height: this.size.height + 'px'
      };
    }
  }
};
</script>

<style lang="scss">
.weather-wrapper {
  display: inline-block;
  margin-top: 10px;
  .weather {
    position: relative;
    display: inline-block;
    width: 100%;
    height: 100%;
    border-radius: 2px;
  }
  .sunny:before {
    position: absolute;
    content: '';
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    background-color: #F6D936;
    border-radius: 50%;
    box-shadow: 0 0 20px #FF0;
    z-index: 2;
    transform: translate(-50%, -50%);
  }
  .sunny:after {
    position: absolute;
    content: '';
    left: 0;
    margin: -8px 0 0 -8px;
    width: calc(100% + 16px);
    height: calc(100% + 16px);
    background-color: #FFEB3B;
    border: none;
    z-index: 1;
    animation: sunScale 2s linear infinite;
    clip-path: polygon(
      50% 0%,
      65.43% 25%,
      93.3% 25%,
      78.87% 50%,
      93.3% 75%,
      64.43% 75%,
      50% 100%,
      35.57% 75%,
      6.7% 75%,
      21.13% 50%,
      6.7% 25%,
      35.57% 25%);
  }
  .cloudy:before, .rainy:before, .snowy:before {
    position: absolute;
    content: '';
    top: 50%;
    left: 25%;
    width: 36px;
    height: 36px;
    background-color: #FFF;
    border-radius: 50%;
    box-shadow:
      #FFF 22px -15px 0 6px,
      #FFF 57px -6px 0 2px,
      #FFF 87px 4px 0 -4px,
      #FFF 33px 6px 0 6px,
      #FFF 61px 6px 0 2px,
      #CCC 29px -23px 0 6px,
      #CCC 64px -14px 0 2px,
      #CCC 94px -4px 0 -4px;
    z-index: 2;
    transform: translate(0, -50%);
  }
  .cloudy:before {
    animation: cloudMove 2s linear infinite;
  }
  .rainy:after {
    position: absolute;
    content: '';
    top: 50%;
    left: 35%;
    width: 4px;
    height: 14px;
    background-color: #FFF;
    border-radius: 2px;
    box-shadow:
      #FFF 25px -10px 0,
      #FFF 50px 0 0,
      #FFF 75px -10px 0,
      #FFF 0px 25px 0,
      #FFF 25px 15px 0,
      #FFF 50px 25px 0,
      #FFF 75px 15px 0,
      #FFF 0 50px 0,
      #FFF 25px 40px 0,
      #FFF 50px 50px 0,
      #FFF 75px 40px 0;
    animation: rainDrop 2s linear infinite;
  }
  .snowy:after {
    position: absolute;
    content: '';
    top: 50%;
    left: 25%;
    width: 8px;
    height: 8px;
    background-color: #FFF;
    border-radius: 50%;
    box-shadow:
      #FFF 25px -10px 0,
      #FFF 50px 0 0,
      #FFF 75px -10px 0,
      #FFF 0 25px 0,
      #FFF 25px 15px 0,
      #FFF 50px 25px 0,
      #FFF 75px 15px 0,
      #FFF 0 50px 0,
      #FFF 25px 40px 0,
      #FFF 50px 50px 0,
      #FFF 75px 40px 0;
    animation: snowDrop 2s linear infinite;
  }
}
@keyframes sunScale {
  0% {
    transform: scale(1) rotate(0);
  }
  50% {
    transform: scale(1.2) rotate(90deg);
  }
  100% {
    transform: scale(1) rotate(180deg);
  }
}
@keyframes cloudMove {
  0% {
    transform: translate(-50%, -50%);
  }
  50% {
    transform: translate(-50%, -60%);
  }
  100% {
    transform: translate(-50%, -50%);
  }
}
@keyframes rainDrop {
  0% {
    transform: translate(0, -0) rotate(10deg);
  }
  100% {
    transform: translate(-4px, 24px) rotate(10deg);
    box-shadow:
      #FFF 25px -10px 0,
      #FFF 50px 0 0,
      #FFF 75px -10px 0,
      #FFF 0 25px 0,
      #FFF 25px 15px 0,
      #FFF 50px 25px 0,
      #FFF 75px 15px 0,
      rgba(255, 255, 255, 0) 0 50px 0,
      rgba(255, 255, 255, 0) 25px 40px 0,
      rgba(255, 255, 255, 0) 50px 50px 0,
      rgba(255, 255, 255, 0) 75px 40px 0;
  }
}
@keyframes snowDrop {
  0% {
    transform: translateY(0);
  }
  100% {
    transform: translateY(25px);
    box-shadow:
      #FFF 25px -10px 0,
      #FFF 50px 0 0,
      #FFF 75px -10px 0,
      #FFF 0 25px 0,
      #FFF 25px 15px 0,
      #FFF 50px 25px 0,
      #FFF 75px 15px 0,
      rgba(255, 255, 255, 0) 0 50px 0,
      rgba(255, 255, 255, 0) 25px 40px 0,
      rgba(255, 255, 255, 0) 50px 50px 0,
      rgba(255, 255, 255, 0) 75px 40px 0;
  }
}
</style>
