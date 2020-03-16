<template>
  <div class="item-wrapper">
    <div class="item shadow-hover" v-for="(item, i) in data" :key="i">
      <div class="avatar">
        <img :src="getAvatar(item.user.avatarUrl, itemConfig.avatar)">
      </div>
      <div class="dynamic-wrapper">
        <div class="info">
          <span class="name blue-link-hover">{{ item.user.username }}</span>
          <div class="tag-wrapper" v-if="item.tags">
            <span class="tag-item" v-for="(tag, j) in item.tags" :key="j">{{ tag.label }}</span>
          </div>
          <span class="time">{{ item.created_at }}</span>
        </div>
        <div class="title">
          <a :href="[item.jumpLink]" target="_blank" class="link blue-link-hover">{{ item.title }}</a>
        </div>
        <div class="desc three-line-ellipsis">{{ item.desc }}</div>
        <div class="operation" v-if="itemConfig.operation">
          <span class="blue-link-hover" :class="{ 'active': iconIsActive(operate, item) }" v-for="(operate, k) in itemConfig.operation" :key="k" @click.prevent="isClick(operate, item)">
            <i :class="operate.icon"></i>{{ nameIsActive(operate, item) }}
          </span>
        </div>
      </div>
    </div>
    <div v-infinite-scroll="loadMore" infinite-scroll-disabled="busy" infinite-scroll-distance="5" v-if="itemConfig.type === 'scroll'">
      <div class="loading-wrapper">
        <i class="el-icon-loading" v-show="!empty"></i>
        <span class="loading-text">{{ animateText }}</span>
      </div>
    </div>
    <div class="paginate-wrapper shadow-hover animate-up-paginate" v-if="itemConfig.type === 'paginate'">
      <el-pagination
              background
              layout="prev, pager, next"
              :page-size="itemConfig.pageNum"
              :total="total"
              @current-change="changePage"
      >
      </el-pagination>
    </div>
  </div>
</template>

<script type="text/ecmascript-6">
export default {
  name: 'listItem',
  props: {
    initData: Function,
    config: Object
  },
  data() {
    return {
      data: [],
      container: {},
      busy: false,
      empty: false,
      animateText: '',
      ellipsis: '...',
      loadNum: 1,
      total: 0
    };
  },
  computed: {
    itemConfig() {
      return Object.assign({
        type: 'scroll',
        operation: null,
        avatar: 'assets/user.jpg',
        loadingText: 'Loading',
        page: 0,
        pageNum: 10,
        pageChangeToTop: true
      }, this.config);
    },
    getAvatar() {
      return function(url, avatar) {
        try {
          avatar = require('@/' + url);
        } catch (err) {
          avatar = require('@/' + avatar);
        }
        return avatar;
      };
    },
    isClick() {
      return function(operate, item) {
        return operate.click ? operate.click(item, operate) : null;
      };
    },
    iconIsActive() {
      return function(operate, item) {
        return operate.iconActive ? operate.iconActive(item, operate) : null;
      };
    },
    nameIsActive() {
      return function(operate, item) {
        return operate.nameActive ? operate.nameActive(item, operate) : null;
      };
    }
  },
  created() {
    switch (this.itemConfig.type) {
    case 'scroll':
      this.loadAnimation();
      break;
    case 'paginate':
      this.initPage();
      break;
    default:
      console.log('Type Error, undefined this type!');
      break;
    }
  },
  methods: {
    initPage() {
      this.$axios.get(`${this.itemConfig.dataUrl}/${this.itemConfig.page}/${this.itemConfig.pageNum}`)
        .then((response) => {
          const res = response.data;
          this.data = res.data;
          this.total = res.total;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    changePage(page) {
      if (this.itemConfig.pageChangeToTop) {
        // 滑动动画效果
        let delay = setInterval(() => {
          this.container.scrollTop -= 50;
          if (this.container.scrollTop <= 0) {
            clearInterval(delay);
          }
        }, 10);
      }
      this.itemConfig.page = page;
      this.initPage();
    },
    loadMore() {
      if (!this.itemConfig.dataUrl) {
        console.log('Missing data');
      }
      this.busy = true;
      this.itemConfig.page++;
      this.$axios.get(`${this.itemConfig.dataUrl}/${this.itemConfig.page}/${this.itemConfig.pageNum}`)
        .then((response) => {
          const res = response.data;
          if (res.state === 200) {
            if (res.data.length === 0) {
              this.itemConfig.loadingText = 'Whoops! data is empty...';
              this.busy = true;
              this.empty = true;
            } else {
              let _this = this;
              setTimeout(() => {
                _this.data.push.apply(_this.data, res.data);
                _this.busy = false;
              }, 800);
            }
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    loadAnimation() {
      let _this = this;
      let animateInterval = setInterval(() => {
        if (this.ellipsis.length < _this.loadNum) {
          _this.loadNum = 1;
        } else {
          _this.animateText = _this.itemConfig.loadingText + _this.ellipsis.substring(0, _this.loadNum);
          _this.loadNum++;
        }
        if (_this.empty) {
          _this.animateText = _this.itemConfig.loadingText;
          clearInterval(animateInterval);
        }
      }, 300);
    },
    handleScroll(e) {
      this.container = e.target;
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
  .item-wrapper {
    margin-left: 10px;
    .item {
      display: flex;
      margin-top: 10px;
      padding: 20px;
      background: #FFF;
      border-radius: 2px;
      .avatar {
        margin-top: 2px;
        padding-right: 20px;
        img {
          width: 36px;
          height: 36px;
          border-radius: 50%;
          cursor: pointer;
          transition: linear 0.5s;
        }
        img:hover {
          transform: rotate(45deg);
        }
      }
      .dynamic-wrapper {
        .info {
          .name {
            display: block;
            font-size: 16px;
            line-height: 16px;
            font-weight: 500;
          }
          .tag-wrapper {
            display: inline-block;
            margin-top: 5px;
            margin-right: 30px;
            .tag-item {
              font-size: 12px;
              line-height: 12px;
              color: #72757B;
              cursor: pointer;
            }
            .tag-item:hover {
              color: #333333;
            }
            .tag-item:not(:first-child) {
              margin-left: 10px;
            }
          }
          .time {
            display: inline-block;
            font-size: 12px;
            line-height: 12px;
            color: #B4B8BD;
          }
        }
        .title {
          margin: 18px 0;
          font-size: 18px;
          line-height: 18px;
          font-weight: 600;
          .link {
            font-size: 18px;
            line-height: 18px;
            color: #333;
            text-decoration: none;
          }
        }
        .desc {
          font-size: 14px;
          line-height: 24px;
          font-weight: 300;
          color: #707070;
        }
        .operation {
          margin: 20px 0;
          span {
            font-size: 12px;
            line-height: 18px;
            color: #B4B8BD;
            i {
              margin-right: 5px;
              font-size: 18px;
              vertical-align: bottom;
            }
          }
          span.active {
            color: #5cb6ff;
          }
          span:not(:first-child) {
            margin-left: 30px;
          }
        }
      }
    }
    .paginate-wrapper {
      padding: 10px 0;
      margin-top: 10px;
      text-align: center;
      background-color: #FFF;
      border-radius: 2px;
    }
    .loading-wrapper {
       margin-top: 20px;
       text-align: center;
       .el-icon-loading {
         font-size: 28px;
         color: #B4B8BD;
       }
       .loading-text {
         display: block;
         color: #B4B8BD;
       }
     }
  }
</style>
