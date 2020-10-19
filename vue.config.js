module.exports = {
  pwa: {
    name: '我的腾讯微博',
    themeColor: '#389FDA',
    appleMobileWebAppCapable: 'yes',
    appleMobileWebAppStatusBarStyle: 'default',
    iconPaths: {
      favicon32: 'img/icons/favicon-32x32.png',
      favicon16: 'img/icons/favicon-16x16.png',
      appleTouchIcon: 'img/icons/apple-touch-icon.png',
      maskIcon: 'img/icons/safari-pinned-tab.svg',
      msTileImage: 'img/icons/mstile-150x150.png',
    },
  },
  chainWebpack: config => {
    config.plugin('html').tap(args => {
      args[0].title = '我的腾讯微博';
      return args;
    });
  },
  productionSourceMap: false,
};
