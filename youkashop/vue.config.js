const autoprefixer = require('autoprefixer');
const pxtorem = require('postcss-pxtorem');
const path = require('path');

const defaultSettings = require('./src/settings.js');

function resolve(dir) {
  return path.join(__dirname, dir)
}

const name = defaultSettings.title || '悠咖'; // page title

module.exports = {
  //outputDir: '../docs',
  publicPath: process.env.NODE_ENV === 'production' ? '/' : '/',

  css: {
    loaderOptions: {
      postcss: {
        plugins: [
          autoprefixer(),
          pxtorem({
            rootValue: 37.5,
            propList: ['*'],
            // 该项仅在使用 Circle 组件时需要
            // 原因参见 https://github.com/youzan/vant/issues/1948
            selectorBlackList: ['van-circle__layer']
          })
        ]
      },
      less: {
        javascriptEnabled: true,
        modifyVars: {
          // red: '#03a9f4',
          // blue: '#3eaf7c',
          // orange: '#f08d49',
          // 'text-color': '#111'
        }
      }
    }
  },
  configureWebpack: {
    // provide the app's title in webpack's name field, so that
    // it can be accessed in index.html to inject the correct title.
    name: name,
    resolve: {
      alias: {
        '@': resolve('src')
      }
    }
  },
  chainWebpack: config => {
    const types = ['vue-modules', 'vue', 'normal-modules', 'normal'];
    types.forEach(type => addStyleResource(config.module.rule('less').oneOf(type)))
  },

  devServer: {
    proxy: {
        '/api': {
            target: 'http://192.168.0.121/index.php',
            changeOrigin: true
        }
    }
  }
};

function addStyleResource(rule) {
  rule.use('style-resource')
    .loader('style-resources-loader')
    .options({
      patterns: [
        path.resolve(__dirname, resolve('src/assets/style/variable.less')), // 需要全局导入的less
      ],
    })
}