<template>
  <div class="article-container">
    <el-card class="box-card">
      <div class="article-list-title">
        <h1>学 习 记 录</h1>
      </div>
    </el-card>
    <div class="article-list">
      <el-card class="box-card" shadow="hover" v-for="(list, index) in articleList" :key="index">
        <div class="list-item" @click.prevent="showArticleDetail(list.id)">
          <div class="item-img">
            <img src="../../assets/user.jpg" style="width: 50px; height: 50px;">
          </div>
          <div class="item-title">
            <span>{{ list.title }}</span>
            <div class="item-tag">
              <el-button type="danger" v-for="(tag, i) in list.tags" :key="i" round>{{ tag.label }}</el-button>
            </div>
          </div>
          <div class="item-count">
            <div class="count">
              <i class="el-icon-view"></i><span> {{ list.viewNum }}</span>
              <i class="el-icon-star-off"></i><span> 123</span>
            </div>
            <div class="date"><span>{{ list.created_at }}</span></div>
          </div>
        </div>
      </el-card>
    </div>
    <div class="pagination-container" v-show="total > 0">
      <el-pagination
              background
              layout="prev, pager, next"
              :total="total"
              @current-change="changePage"
      >
      </el-pagination>
    </div>
  </div>
</template>

<script type="text/ecmascript-6">
export default {
  name: 'blogArticle',
  data() {
    return {
      articleList: [],
      page: 1,
      total: 0
    };
  },
  created() {
    this.initListData();
  },
  methods: {
    showArticleDetail(id) {
      this.$router.push({ path: `/article/${id}` });
    },
    initListData() {
      this.$axios.get(`/api/article/${this.page}`)
        .then((response) => {
          const res = response.data;
          if (res.state === 200) {
            this.articleList = res.data;
            this.total = res.total;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    changePage(page) {
      this.page = page;
      this.initListData();
    }
  }
};
</script>

<style lang="scss">
.article-container {
  margin: 20px 20%;
  min-height: 80%;
  .el-card {
    .article-list-title {
      text-align: center;
    }
  }
  .article-list {
    margin-top: 20px;
    .el-card {
      border-radius: 0;
      .el-card__body {
        padding: 0;
        .list-item {
          display: flex;
          .item-img {
            border-radius: 2px;
            img {
              margin: 12px 20px 8px 20px;
              border-radius: 50%;
            }
          }
          .item-title {
            position: relative;
            flex: 1;
            padding: 27px 0;
            line-height: 20px;
            font-size: 20px;
            .item-tag {
              position: absolute;
              right: 15px;
              bottom: 12px;
              button {
                margin-top: 4px;
                padding: 1px 5px;
                font-size: 12px;
                opacity: 0.8;
              }
            }
          }
          .item-count {
            margin: 0 20px;
            .count {
              margin-top: 20px;
              font-size: 15px;
              color: rgba(7, 17, 27, 0.5);
              span {
                font-size: 12px;
              }
              i {
                vertical-align: middle;
              }
              i:nth-child(3) {
                margin-left: 5px;
              }
            }
            .date{
              margin-top: 5px;
              font-size: 12px;
              text-align: right;
              color: rgba(7, 17, 27, 0.5)
            }
          }
        }
        .list-item:hover {
          cursor: pointer;
          background: #EEE;
          border-left: 5px solid rgba(7, 17, 27, 0.5);
          .item-title {
            .item-tag {
              button {
                opacity: 1;
              }
            }
          }
        }
      }
    }
  }
  .pagination-container {
    margin-top: 20px;
    padding: 10px 0;
    text-align: center;
    background: #FFF;
  }
}
@media screen and (max-width: 1000px) {
  .article-container {
    .article-list {
      .item-count {
        display: none;
      }
    }
  }
}

@media screen and (max-width: 800px) {
  .article-container {
    .article-list {
      .item-img {
        display: none;
      }
      .item-title {
        margin-left: 15px;
      }
    }
  }
}
</style>
