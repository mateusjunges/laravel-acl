const env = require('./env')
const head = require('./head')(env)

module.exports = {
  ...env,
  head,

  themeConfig: {
    locales: {
      '/': {
        sidebar: {
          '/guide/': [
            {
              title: 'Guide',
              collapsable: false,
              children: [
                'getting-started',
                'usage',
              ]
            },
          ]
        }
      }
    }
  }
}
