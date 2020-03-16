<template>
  <div class="edit-article-container">
    <div class="section-tree">
      <div class="tree-wrapper">
        <el-tree
                :data="articles"
                :props="defaultProps"
                :default-expand-all="expendAll"
                @node-click="handleNodeClick"
        ></el-tree>
        <div class="pagination-wrapper">
          <el-pagination
                  small
                  background
                  layout="prev, pager, next"
                  :total="total"
                  @current-change="changePage"
          >
          </el-pagination>
        </div>
      </div>
    </div>
    <div class="section-edit" ref="viewport">
      <div class="title-wrapper">
        <div class="title">
          <el-input placeholder="请输入标题" v-model="article.title">
            <template slot="prepend">标题</template>
          </el-input>
        </div>
        <div class="tag">
          <el-select v-model="article.tags" multiple placeholder="请选择标签">
            <el-option
                    v-for="tag in tags"
                    :key="tag.value"
                    :label="tag.label"
                    :value="tag.value"
            ></el-option>
          </el-select>
        </div>
      </div>
      <div class="editor-wrapper">
        <quill-editor
                v-model="article.content"
                ref="myQuillEditor"
                :options="editorOption"
                @blur="onEditorBlur($event)"
                @focus="onEditorFocus($event)"
                @change="onEditorChange($event)"
        ></quill-editor>
      </div>
    </div>
    <div class="section-operation">
      <div class="button-wrapper">
        <el-tooltip content="保存" placement="left">
          <el-button type="primary" icon="el-icon-edit" @click.prevent="submitArticle" circle></el-button>
        </el-tooltip>
        <el-tooltip content="清空" placement="left">
          <el-button type="warning" icon="el-icon-refresh" @click.prevent="cleanEdit" circle></el-button>
        </el-tooltip>
      </div>
    </div>
  </div>
</template>

<script type="text/ecmascript-6">
import { quillEditor } from 'vue-quill-editor';
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
import options from '@/plugins/editor.option';

export default {
  name: 'editArticle',
  data() {
    return {
      article: {
        title: '',
        tags: [],
        content: ''
      },
      tags: [],
      editorOption: options,
      page: 1,
      total: 0,
      articles: [],
      expendAll: true,
      defaultProps: {
        children: 'children',
        label: 'label'
      }
    };
  },
  created() {
    this.authenticate();
  },
  computed: {
    editor() {
      return this.$refs.myQuillEditor.quill;
    }
  },
  methods: {
    onEditorReady(editor) {},
    onEditorBlur() {},
    onEditorFocus() {},
    onEditorChange() {},
    handleNodeClick(data) {
      this.article = data;
    },
    changePage(page) {
      this.page = page;
      this.initArticleTree();
    },
    authenticate() {
      this.$axios.get('/api/authenticate')
        .then((response) => {
          const res = response.data;
          if (res.state === 200) {
            this.initTagsData();
            this.initArticleTree();
          } else {
            this.$router.push({ path: '/admin' });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    initArticleTree() {
      this.$axios.get(`/api/article/${this.page}`)
        .then((response) => {
          const res = response.data;
          if (res.state === 200) {
            this.total = res.total;
            this.articles = [
              {
                label: '文章列表',
                children: res.data.map(function(article, key) {
                  article.label = `标题：${article.title}`;
                  article.tags = article.tags.map(function(tag, k) {
                    return tag.value;
                  });
                  return article;
                })
              }
            ];
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    initTagsData() {
      this.$axios.get('/api/tags')
        .then((response) => {
          const res = response.data;
          if (res.state === 200) {
            this.tags = res.data;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    submitArticle() {
      this.$axios.post('/api/article/create', this.article)
        .then((response) => {
          const res = response.data;
          if (res.message === 'Unauthenticated') {
            this.$message({
              type: 'error',
              message: '请先登录'
            });
          } else {
            if (res.state === 200) {
              this.$message({
                type: 'success',
                message: this.article.id ? '修改成功' : '提交成功'
              });
              this.initArticleTree();
              this.cleanEdit();
            }
          }
        })
        .catch((error) => {
          const errNum = error.message.substr(-3);
          if (errNum === '401') {
            this.$message({
              type: 'error',
              message: '请先登录'
            });
          }
        });
    },
    cleanEdit() {
      this.article = {};
    }
  },
  components: {
    quillEditor
  }
};
</script>

<style lang="scss">
.edit-article-container {
  display: flex;
  background: rgba(0, 0, 0, 0.8);
  .section-tree {
    margin-right: 20px;
    min-width: 300px;
    background: linear-gradient(45deg, #211136, #bf5853);
    overflow: hidden;
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
    .tree-wrapper {
      position: absolute;
      top: 100px;
      left: 50px;
      width: 250px;
      .el-tree {
        color: #FFF;
        background: transparent;
        .el-tree-node__content:hover {
          color: rgba(7, 17, 27, 0.8);
        }
      }
      .pagination-wrapper {
        margin-top: 50px;
        width: 200px;
        text-align: center;
      }
    }
  }
  .section-edit {
    position: relative;
    margin: auto;
    padding-top: 100px;
    padding-bottom: 150px;
    width: 100%;
    .title-wrapper {
      padding: 20px 0;
      margin-bottom: 10px;
      text-align: center;
      background: #FFF;
      box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
      .title, .tag {
        display: inline-block;
      }
      .title {
        min-width: 400px;
      }
      .tag {
        margin-left: 10px;
      }
    }
    .editor-wrapper {
      padding: 10px;
      min-height: 500px;
      background: #FFF;
      box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
      .quill-editor {
        .ql-toolbar {
          position: fixed;
          top: 30px;
          z-index: 999;
          width: 800px;
          background: #FFF;
        }
        .ql-container {
          border-top: 1px solid #CCC;
          .ql-editor {
            min-height: 500px;
          }
        }
      }
    }
  }
  .section-operation {
    margin-left: 20px;
    min-width: 100px;
    background: linear-gradient(45deg, #211136, #bf5853);
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
    .button-wrapper {
      display: inline-grid;
      position: absolute;
      top: 100px;
      right: 50px;
      button:nth-child(2) {
        margin-left: 0;
        margin-top: 20px;
      }
    }
  }
}
@media screen and (max-width: 1200px) {
  .edit-article-container {
    .section-tree {
      display: none;
    }
    .section-edit {
      margin-left: 20px;
    }
    .section-operation {
      min-width: 50px;
      .button-wrapper {
        right: 5px;
      }
    }
  }
}
</style>
