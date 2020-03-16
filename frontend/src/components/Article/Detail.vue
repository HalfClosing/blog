<template>
  <div class="detail-container">
    <el-card class="card-box">
      <div slot="header">
        {{ article.title }}
        <div class="tags">
          <el-button v-for="(tag, index) in article.tags" :key="index" type="danger" round>{{ tag.label }}</el-button>
        </div>
        <div class="view-num">
          <el-tooltip content="浏览数" placement="top">
            <i class="el-icon-view"></i>
          </el-tooltip>
          <span>{{ article.viewNum }}</span>
        </div>
      </div>
      <div class="article-content ql-snow">
        <div class="ql-editor" v-html="article.content"></div>
      </div>
      <el-divider>欢迎阅读！</el-divider>
      <div class="star-wrapper">
        <transition name="zoom">
          <i class="el-icon-star-off" v-show="!starIcon" @click.prevent="changeStarIcon"></i>
        </transition>
        <transition name="zoom">
          <i class="el-icon-star-on" v-show="starIcon" @click.prevent="changeStarIcon"></i>
        </transition>
        <div class="star-text">{{ starIcon ? '点过了哟！' : '觉得有用就点亮它！' }}</div>
      </div>
      <div class="article-footer">
        <div class="author">
          <span>By {{ article.user.username }}</span>
        </div>
        <div class="date">
          <span>{{ article.created_at }}</span>
        </div>
      </div>
    </el-card>
    <to-top :config="toTopConfig"></to-top>
  </div>
</template>

<script type="text/ecmascript-6">
import ToTop from '@/components/Common/ToTop';
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';

export default {
  name: 'articleDetail',
  data() {
    return {
      starIcon: false,
      article: {
        user: {
          username: 'author'
        }
      },
      toTopConfig: {
        position: {
          right: 50,
          bottom: 120
        },
        width: 50,
        height: 50,
        speedType: 'ease-out'
      }
    };
  },
  activated() { // keep-alive 组件激活时调用
    this.$axios.get(`/api/article/detail/${this.$route.params.id}`)
      .then((response) => {
        const res = response.data;
        if (res.state === 200) {
          this.article = res.data;
          this.starIcon = this.article.star.state;
        }
      })
      .catch((error) => {
        console.log(error);
      });
  },
  deactivated() { // keep-alive 组件停用时调用
    this.emptyArticleData(); // 清空article数据
  },
  methods: {
    changeStarIcon() {
      this.$axios.post('/api/article/star', { 'state': this.starIcon, 'articleId': this.$route.params.id })
        .then((response) => {
          const res = response.data;
          if (res.errno === 'success') {
            this.starIcon = res.state;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    emptyArticleData() {
      this.article = {
        user: {
          username: 'author'
        }
      };
    }
  },
  components: {
    ToTop
  }
};
</script>

<style lang="scss">
.detail-container {
  margin: 20px 20%;
  .el-card {
    min-height: 100%;
    .el-card__header {
      position: relative;
      font-size: 18px;
      font-weight: 500;
      text-align: center;
      .tags {
        position: absolute;
        top: 18px;
        right: 30px;
        button {
          margin-top: 4px;
          padding: 1px 5px;
          font-size: 12px;
          vertical-align: top;
        }
      }
      .view-num {
        display: inline-block;
        position: absolute;
        top: 28px;
        margin-left: 10px;;
        line-height: 12px;
        font-size: 12px;
        color: rgba(7, 17, 27, 0.5);
        i {
          margin-right: 5px;
          font-weight: 700;
        }
      }
    }
    .el-card__body {
      .article-content {
        font-size: 15px;
      }
      .el-divider {
        margin: 40px 0;
        .el-divider__text {
           color: rgba(7, 17, 27, 0.5)
        }
      }
      .star-wrapper {
        position: relative;
        text-align: center;
        color: rgba(7, 17, 27, 0.5);
        .el-icon-star-off, .el-icon-star-on {
          position: absolute;
          left: 49%;
          transition: all 0.5s;
          transform: scale(1, 1);
          &.zoom-enter, &.zoom-leave-to {
            transform: scale(2, 2);
          }
        }
        .el-icon-star-off:hover, .el-icon-star-on:hover {
          transform: scale(1.5, 1.5);
        }
        i {
          font-size: 28px;
        }
        .star-text {
          padding-top: 38px;
          line-height: 12px;
          font-size: 12px;
        }
      }
      .article-footer {
        line-height: 15px;
        font-size: 15px;
        text-align: right;
        color: rgba(7, 17, 27, 0.5);
        padding-right: 20px;
        .author {
          margin-top: 50px;
          padding: 10px 0;
        }
        .date {
          margin-bottom: 50px;
        }
      }
    }
  }
}
</style>
