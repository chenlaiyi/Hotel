/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-02-13 14:41:54
 */
'use strict'
const path = require('path')
const DianDiConfigPlugin = require('./src/utils/DainDiConfigPlugin.js')
const defaultSettings = require('./src/settings.js')
const indexHtml = require('./index.html')
const webpack = require('webpack')
function resolve(dir) {
  return path.join(__dirname, dir)
}
const name = defaultSettings.title || 'diandi-element-admin' // page title

// If your port is set to 80,
// use administrator privileges to execute the command line.
// For example, Mac: sudo npm run
// You can change the port by the following method:
// port = 9527 npm run dev OR npm run dev --port = 9527
const port = process.env.port || process.env.npm_config_port || 9527 // dev port

const models = require('./projectsConfig.js')
const projectName = process.env.PROJECT_NAME || 'shop'
const outputDir = projectName ? `dist/${projectName}/pro-admin` : 'dist/pro-admin'
// All configuration item explanations can be find in https://cli.vuejs.org/config/
module.exports = {
  ...models[projectName],
  publicPath: process.env.NODE_ENV === 'production' ? `/${projectName}/pro-admin` : `/${projectName}`,
  outputDir: outputDir,
  indexPath: `dist/${projectName}/index.html`,
  assetsDir: 'static',
  lintOnSave: process.env.NODE_ENV === 'development',
  productionSourceMap: false,
  devServer: {
    port: port,
    open: true,
    overlay: {
      warnings: false,
      errors: true
    },
    before: require('./mock/mock-server.js')
  },
  configureWebpack: {
    // provide the app's title in webpack's name field, so that
    // it can be accessed in index.html to inject the correct title.
    name: name,
    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'src/'),
        /**
         * 定义当前运行项目路径
         */
        '@projectName': path.resolve(__dirname, `src/projects/${projectName}`)
      }
    },
    plugins: [
      new webpack.DefinePlugin({
        'process.env.PROJECT_NAME': JSON.stringify(process.env.PROJECT_NAME || 'shop'),
        'process.env.API_PREFIX': JSON.stringify(process.env.API_PREFIX || 'api')
      })
    ]
  },
  chainWebpack(config) {
    // 过滤掉部分模块
    if (process.env.NODE_ENV === 'production') {
      config.plugin('html-index').tap(() => [
        {
          filename: `../index.html`,
          templateParameters: {
            BASE_URL: `/${projectName}/pro-admin/`
          },
          // template: path.resolve(__dirname, '/public/index.html'), // 设置新的模板路径
          templateContent: () => {
            return indexHtml.replace(/<%= BASE_URL %>/g, `/${projectName}/pro-admin/`)
          },
          inject: true, // 是否将资源注入到模板中，默认为true
          chunksSortMode: 'auto', // 控制chunk的排序方式
          // 配置元数据
          // title: '你的网站标题', // 设置网页标题
          // meta: {
          //   // 设置关键词
          //   keywords: '关键词1, 关键词2, 关键词3',
          //   // 设置描述
          //   description: '这是你的网页描述，可以是多句话介绍网站内容。'
          // },
          minify: { // HTML压缩配置，根据需要开启或关闭
            collapseWhitespace: true,
            removeComments: true,
            removeRedundantAttributes: true,
            useShortDoctype: true,
            removeEmptyAttributes: true,
            removeStyleLinkTypeAttributes: true,
            keepClosingSlash: true,
            minifyJS: true,
            minifyCSS: true,
            minifyURLs: true
          },
          rel: 'preload',
          // to ignore runtime.js
          // https://github.com/vuejs/vue-cli/blob/dev/packages/@vue/cli-service/lib/config/app.js#L171
          fileBlacklist: [/\.map$/, /hot-update\.js$/, /runtime\..*\.js$/],
          include: 'initial'
        }
      ])
    }
    // when there are many pages, it will cause too many meaningless requests
    // config.plugins.delete('prefetch')

    // set svg-sprite-loader
    config.module.rule('svg').exclude.add(resolve('src/icons')).end()
    config.module
      .rule('icons')
      .test(/\.svg$/)
      .include.add(resolve('src/icons'))
      .end()
      .use('svg-sprite-loader')
      .loader('svg-sprite-loader')
      .options({
        symbolId: 'icon-[name]'
      })
      .end()

    config.when(process.env.NODE_ENV !== 'development', (config) => {
      config
        .plugin('ScriptExtHtmlWebpackPlugin')
        .after('html')
        .use('script-ext-html-webpack-plugin', [
          {
            // `runtime` must same as runtimeChunk name. default is `runtime`
            inline: /runtime\..*\.js$/
          }
        ])
        .end()
      config.optimization.splitChunks({
        chunks: 'all',
        cacheGroups: {
          libs: {
            name: 'chunk-libs',
            test: /[\\/]node_modules[\\/]/,
            priority: 10,
            chunks: 'initial' // only package third parties that are initially dependent
          },
          elementUI: {
            name: 'chunk-elementUI', // split elementUI into a single package
            priority: 20, // the weight needs to be larger than libs and app or it will be packaged into libs or app
            test: /[\\/]node_modules[\\/]_?element-ui(.*)/ // in order to adapt to cnpm
          },
          commons: {
            name: 'chunk-commons',
            test: resolve('src/components'), // can customize your rules
            minChunks: 3, //  minimum common number
            priority: 5,
            reuseExistingChunk: true
          }
        }
      })
      // https:// webpack.js.org/configuration/optimization/#optimizationruntimechunk
      config.optimization.runtimeChunk('single')
    })
    // 动态配置
    if (process.env.NODE_ENV === 'production') {
      config.plugin('DianDiConfigPlugin').use(new DianDiConfigPlugin([
        {
          fileName: 'micro-service-config.js',
          path: 'public/configs',
          content: ''
        },
        {
          fileName: 'index.html',
          path: 'public',
          content: indexHtml
        }
      ]))
    }
  },
  transpileDependencies: ['diandi-admin']
}
