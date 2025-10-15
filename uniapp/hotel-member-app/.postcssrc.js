module.exports = {
    "plugins": {
      "autoprefixer": {},
      'postcss-pxtorem': {
        rootValue: (item)=>{
            console.log(item.file,'file`111111111111111111111111111111');
        }, // 75表示750设计稿，37.5表示375设计稿
        propList: ['*'],
        // selectorBlackList: ['van'],
        exclude: /web/i
      }
    }
}