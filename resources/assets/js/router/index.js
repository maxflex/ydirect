import Vue from 'vue'
import Router from 'vue-router'
import routes from './routes'
import store from '@/store'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes
})

// router.beforeEach((to, from, next) => {
//   if (store.)
//   next()
// })

export default router
