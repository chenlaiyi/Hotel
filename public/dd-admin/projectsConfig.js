/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2024-04-22 22:27:45
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-02-25 19:27:26
 */
const config = {
  shop: {
    pages: {
      index: {
        entry: 'src/projects/shop/main.js',
        template: 'public/index.html',
        filename: process.env.NODE_ENV === 'production' ? 'shop.html' : 'index.html'
      }
    },
    devServer: {
      port: 8080, // 端口地址
      open: false, // 是否自动打开浏览器页面
      host: '0.0.0.0', // 指定使用一个 host，默认是 localhost
      https: false, // 使用https提供服务
      disableHostCheck: true,
      // 设置代理
      proxy: {
        // '/eopenhapi': {
        //   target: 'http://open.jdpay.com',
        //   changeOrigin: true
        // },
        // '/hapi': {
        //   target: 'http://open.jdpay.com',
        //   changeOrigin: true
        // }
      }
    }
  },
  backend: {
    pages: {
      index: {
        entry: 'src/projects/backend/main.js',
        template: 'public/index.html',
        filename: process.env.NODE_ENV === 'production' ? 'backend.html' : 'index.html'
      }
    },
    devServer: {
      port: 8081, // 端口地址
      open: false, // 是否自动打开浏览器页面
      host: '0.0.0.0', // 指定使用一个 host，默认是 localhost
      https: false, // 使用https提供服务
      disableHostCheck: true,
      // 设置代理
      proxy: {
        // '/eopenhapi': {
        //   target: 'http://open.jdpay.com',
        //   changeOrigin: true
        // },
        // '/hapi': {
        //   target: 'http://open.jdpay.com',
        //   changeOrigin: true
        // }
      }
    }
  },
  tea: {
    pages: {
      index: {
        entry: 'src/projects/tea/main.js',
        template: 'public/index.html',
        filename: process.env.NODE_ENV === 'production' ? 'tea.html' : 'index.html'
      }
    },
    devServer: {
      port: 8082, // 端口地址
      open: false, // 是否自动打开浏览器页面
      host: '0.0.0.0', // 指定使用一个 host，默认是 localhost
      https: false, // 使用https提供服务
      disableHostCheck: true,
      // 设置代理
      proxy: {
        // '/eopenhapi': {
        //   target: 'http://open.jdpay.com',
        //   changeOrigin: true
        // },
        // '/hapi': {
        //   target: 'http://open.jdpay.com',
        //   changeOrigin: true
        // }
      }
    }
  },
  hotel: {
    pages: {
      index: {
        entry: 'src/projects/hotel/main.js',
        template: 'public/index.html',
        filename: process.env.NODE_ENV === 'production' ? 'hotel.html' : 'index.html'
      }
    },
    devServer: {
      port: 8083, // 端口地址
      open: false, // 是否自动打开浏览器页面
      host: '0.0.0.0', // 指定使用一个 host，默认是 localhost
      https: false, // 使用https提供服务
      disableHostCheck: true,
      // 设置代理
      proxy: {
        // '/eopenhapi': {
        //   target: 'http://open.jdpay.com',
        //   changeOrigin: true
        // },
        // '/hapi': {
        //   target: 'http://open.jdpay.com',
        //   changeOrigin: true
        // }
      }
    }
  },
  hotelwork: {
    pages: {
      index: {
        entry: 'src/projects/hotelwork/main.js',
        template: 'public/index.html',
        filename: process.env.NODE_ENV === 'production' ? 'hotelwork.html' : 'index.html'
      }
    },
    devServer: {
      port: 8083, // 端口地址
      open: false, // 是否自动打开浏览器页面
      host: '0.0.0.0', // 指定使用一个 host，默认是 localhost
      https: false, // 使用https提供服务
      disableHostCheck: true,
      // 设置代理
      proxy: {
        // '/eopenhapi': {
        //   target: 'http://open.jdpay.com',
        //   changeOrigin: true
        // },
        // '/hapi': {
        //   target: 'http://open.jdpay.com',
        //   changeOrigin: true
        // }
      }
    }
  },
  farm: {
    pages: {
      index: {
        entry: 'src/projects/farm/main.js',
        template: 'public/index.html',
        filename: process.env.NODE_ENV === 'production' ? 'farm.html' : 'index.html'
      }
    },
    devServer: {
      port: 8085, // 端口地址
      open: false, // 是否自动打开浏览器页面
      host: '0.0.0.0', // 指定使用一个 host，默认是 localhost
      https: false, // 使用https提供服务
      disableHostCheck: true,
      // 设置代理
      proxy: {
        // '/eopenhapi': {
        //   target: 'http://open.jdpay.com',
        //   changeOrigin: true
        // },
        // '/hapi': {
        //   target: 'http://open.jdpay.com',
        //   changeOrigin: true
        // }
      }
    }
  },
  open: {
    pages: {
      index: {
        entry: 'src/projects/open/main.js',
        template: 'public/index.html',
        filename: process.env.NODE_ENV === 'production' ? 'open.html' : 'index.html'
      }
    },
    devServer: {
      port: 8085, // 端口地址
      open: false, // 是否自动打开浏览器页面
      host: '0.0.0.0', // 指定使用一个 host，默认是 localhost
      https: false, // 使用https提供服务
      disableHostCheck: true,
      // 设置代理
      proxy: {
        // '/eopenhapi': {
        //   target: 'http://open.jdpay.com',
        //   changeOrigin: true
        // },
        // '/hapi': {
        //   target: 'http://open.jdpay.com',
        //   changeOrigin: true
        // }
      }
    }
  }
}
module.exports = config
