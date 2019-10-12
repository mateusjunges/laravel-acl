const isDev = process.env.NODE_ENV !== 'production'
const base = isDev ? '/' : '/laravel-acl/'

module.exports = {
  base,
  title: 'Laravel ACL',
  description: 'Manage user permissions and groups',
}
