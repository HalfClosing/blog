<template>
  <div class="block-wrapper shadow-hover" :class="{ 'fixed-scroll' : blockConfig.isFixed }">
    <div class="block-title">
      <span>{{ block.title }}</span>
      <span class="more blue-link-hover" v-if="blockConfig.hasMore">更多</span>
    </div>
    <div class="block-content">
      <div class="block-item gray-back-hover" v-for="(item, i) in block.data" :key="i">
        <el-tooltip :content="item.origin.title" placement="bottom" :open-delay="500">
          <div class="item-title blue-link-hover single-ellipsis">{{ item.origin.title }}</div>
        </el-tooltip>
        <div class="item-tags" v-if="item.origin.tags">
          <span v-for="(tag, j) in item.origin.tags" :key="j">{{ tag.label }}</span>
        </div>
        <div class="item-operation" v-if="blockConfig.operation">
          <span class="blue-link-hover" v-for="(operate, i) in blockConfig.operation" :key="i">
            <i :class="operate.icon"></i>{{ operate.name }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/ecmascript-6">
export default {
  name: 'listBlock',
  props: {
    block: Object,
    config: Object
  },
  computed: {
    blockConfig() {
      return Object.assign({
        hasMore: true,
        isFixed: false,
        operation: null
      }, this.config);
    }
  }
};
</script>

<style lang="scss">
  .block-wrapper {
    width: 220px;
    border-radius: 2px;
    background: #FFF;
    .block-title {
      padding: 15px 20px;
      border-bottom: 1px solid rgba(241, 241, 241, 0.8);
      span {
        font-size: 16px;
        line-height: 16px;
        font-weight: 400;
        color: #1D1F23;
      }
      .more {
        float: right;
        margin-top: 5px;
        font-size: 12px;
        line-height: 12px;
        color: #7A7A7A;
      }
    }
    .block-content {
      padding-bottom: 20px;
      .block-item {
        padding: 10px 20px;
        .item-title {
          font-size: 15px;
          line-height: 15px;
          color: #333333;
        }
        .item-tags {
          display: flex;
          margin: 8px 0;
          span {
            font-size: 12px;
            line-height: 12px;
            color: #A5A9AE;
          }
          span:not(:last-child) {
            margin-right: 10px;
          }
        }
        .item-operation {
          display: flex;
          span {
            font-size: 12px;
            line-height: 18px;
            color: #B4B8BD;
            i {
              margin-right: 3px;
            }
          }
          span:not(:last-child) {
            margin-right: 20px;
          }
        }
      }
    }
  }
</style>
