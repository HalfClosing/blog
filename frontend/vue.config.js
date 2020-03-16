module.exports = {
  indexPath: process.env.NODE_ENV === 'production'
      ? '../../resources/views/index.blade.php'
      : './public/index.html',
  outputDir: '../public/dist',
  publicPath: process.env.NODE_ENV === 'production'
      ? '/dist/'
      : '/',
  devServer: {
    proxy: {
      // 配置跨域
      '/api': {
        target: 'http://localhost:8000/api/',
        ws: true,
        changeOrigin: true,
        pathRewrite: {
          '^/api': '/'
        }
      },
      '/login': {
        target: 'http://localhost:8000/login/',
        ws: true,
        changeOrigin: true,
        pathRewrite: {
          '^/login': '/'
        }
      }
    }
  },
  configureWebpack: {
    watch: true
  }
};