<template>
  <div class="essay-container">
    <el-card class="box-card">
      <div class="timeline-list-title">
        <h1>时 间 线</h1>
      </div>
    </el-card>
    <div class="timeline-list">
      <el-timeline>
        <el-timeline-item
                v-for="(timeline, index) in timelines"
                :key="index"
                :type="timeline.type"
                :timestamp="timeline.created_at"
        >
          {{ timeline.content }}
        </el-timeline-item>
      </el-timeline>
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
  name: 'blogTimeline',
  data() {
    return {
      page: 1,
      total: 0,
      timelines: []
    };
  },
  created() {
    this.initListData();
  },
  methods: {
    initListData() {
      this.$axios.get(`/api/timeline/${this.page}`)
        .then((response) => {
          const res = response.data;
          if (res.state === 200) {
            this.timelines = res.data;
            this.total = res.total;
            this.timelines.map((timeline) => {
              timeline.type = 'primary';
              return timeline;
            });
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
.essay-container {
  margin: 20px 20%;
  min-height: 80%;
  .el-card {
    .timeline-list-title {
      text-align: center;
    }
  }
  .timeline-list {
    margin-top: 20px;
    padding-top: 50px;
    padding-bottom: 20px;
    border-radius: 5px;
    background: #FFF;
  }
  .pagination-container {
    margin-top: 20px;
    padding: 10px 0;
    text-align: center;
    background: #FFF;
  }
}
</style>
