<template>
  <div class="home-container">
    <div class="left-panel">
      <div class="user-wrapper shadow-hover">
        <div class="user-avatar">
          <img class="user-image" src="../../assets/user.jpg">
        </div>
        <div class="user-info">
          <span>个人信息</span>
          <span>地址：成都市 <i class="el-icon-location-information"></i></span>
        </div>
        <weather :type="weatherType" :size="weatherSize"></weather>
      </div>
      <div class="list-wrapper shadow-hover">
        <ul class="list-block">
          <li class="list-item">
          </li>
        </ul>
      </div>
    </div>
    <div class="center-panel">
      <list-item :initData="initItemData" :config="itemConfig"></list-item>
    </div>
    <div class="right-panel">
      <div class="block">
        <list-block :block="selectionBlock" :config="blockConfig"></list-block>
      </div>
    </div>
    <to-top :config="toTopConfig"></to-top>
  </div>
</template>

<script type="text/ecmascript-6">
import Weather from '@/components/Weather/Weather';
import ListItem from '@/components/Common/ListItem';
import ListBlock from '@/components/Common/ListBlock';
import ToTop from '@/components/Common/ToTop';

export default {
  name: 'home',
  data() {
    return {
      page: 1,
      total: 0,
      articleList: [],
      selectionBlock: {
        title: '精选内容',
        data: []
      },
      weatherType: 'sunny',
      weatherSize: {
        width: 30,
        height: 30
      },
      itemConfig: {
        type: 'scroll',
        operation: [
          {
            icon: 'el-icon-s-opportunity',
            iconActive: function (item) {
              return item.isAddUse;
            },
            name: '有用',
            nameActive: function (item, operate) {
              return item.useNum ? item.useNum + '人觉得有用' : operate.name;
            },
            click: (item) => {
              this.$axios.post('/api/operate', {
                'originId': item.id,
                'type': 'article_use',
                'state': item.isAddUse
              })
                .then((response) => {
                  const res = response.data;
                  if (res.errno === 'success') {
                    item.isAddUse ? item.useNum-- : item.useNum++;
                    item.isAddUse = res.state;
                    this.$message({
                      showClose: true,
                      message: item.isAddUse ? `觉得《${item.title}》有用！` : `觉得《${item.title}》没用...(T＿T)/~~`
                    });
                  }
                })
                .catch((error) => {
                  console.log(error);
                });
            }
          },
          {
            icon: 'el-icon-s-promotion',
            name: '分享'
          }
        ],
        dataUrl: '/api/article',
        pageNum: 5
      },
      blockConfig: {
        isFixed: true,
        operation: [
          {
            icon: 'el-icon-s-opportunity',
            name: '有用'
          },
          {
            icon: 'el-icon-s-promotion',
            name: '分享'
          }
        ]
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
  created() {
    this.initBlockData();
  },
  methods: {
    initItemData(data) {
      this.$axios.get(`/api/article/${this.page}`)
        .then((response) => {
          const res = response.data;
          if (res.state === 200) {
            data = res.data;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    initBlockData() {
      this.$axios.get(`/api/selection/1/4`)
        .then((response) => {
          const res = response.data;
          if (res.state === 200) {
            this.selectionBlock.data = res.data;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  components: {
    Weather,
    ListItem,
    ListBlock,
    ToTop
  }
};
</script>

<style lang="scss">
.home-container {
  margin: 0 auto 20px auto;
  width: 1140px;
  min-height: 100%;
  .left-panel {
    float: left;
    display: block;
    width: 240px;
    height: 100%;
    vertical-align: top;
    .user-wrapper {
      margin-top: 10px;
      padding: 30px 0;
      text-align: center;
      border-radius: 2px;
      background: #FFF;
      transition: all linear 0.2s;
      .user-avatar {
        .user-image {
          width: 100px;
          height: 100px;
          border-radius: 50%;
        }
      }
      .user-info {
        margin-top: 20px;
        span {
          display: block;
          margin: 8px 0;
          font-size: 16px;
          line-height: 16px;
          text-align: center;
          color: #707070;
          i {
            font-size: 15px;
            line-height: 16px;
            vertical-align: baseline;
          }
        }
      }
    }
    .list-wrapper {
      height: 600px;
      border-radius: 2px;
      background: #FFF;
      .list-block {
        list-style: none;
        margin-block-start: 10px;
        margin-block-end: 0;
      }
    }
  }
  .center-panel {
    float: left;
    display: block;
    margin-bottom: 20px;
    width: 680px;
    height: 100%;
  }
  .right-panel {
    float: left;
    display: block;
    width: 220px;
    height: 100%;
    vertical-align: top;
    .block {
      margin-top: 10px;
      margin-left: 10px;
    }
  }
}
</style>
