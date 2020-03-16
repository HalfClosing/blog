import Vue from 'vue';
import Router from 'vue-router';
import Home from '@/components/Home/Home';
import Article from '@/components/Article/Article';
import ArticleDetail from '@/components/Article/Detail';
import Timeline from '@/components/Timeline/Timeline';
import Blogger from '@/components/Blogger/Blogger';
import Admin from '@/components/Admin/Admin';
import EditArticle from '@/components/Admin/EditArticle';

Vue.use(Router);

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      redirect: '/home'
    },
    {
      path: '/home',
      name: 'home',
      component: Home
    },
    {
      path: '/article',
      name: 'article',
      component: Article
    },
    {
      path: '/article/:id(\\d+)',
      name: 'articleDetail',
      component: ArticleDetail
    },
    {
      path: '/timeline',
      name: 'timeline',
      component: Timeline
    },
    {
      path: '/blogger',
      name: 'blogger',
      component: Blogger
    },
    {
      path: '/admin',
      name: 'admin',
      component: Admin
    },
    {
      path: '/admin/editArticle',
      name: 'editArticle',
      component: EditArticle
    }
  ]
});
