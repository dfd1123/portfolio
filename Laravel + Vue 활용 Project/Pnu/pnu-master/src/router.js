import Vue from 'vue'
import Router from 'vue-router'
import AuthRequired from './utils/AuthRequired'

Vue.use(Router)

const routes = [
  {
    path: '/',
    redirect: '/master'
  },
  {
    path: '/app',
    redirect: '/master'
  },
  {
    path: '/master',
    redirect: '/master/app'
  },
  {
    path: '/master/home',
    redirect: '/master/app'
  },
  {
    path: '/master/app',
    component: () => import('./views/app'),
    redirect: '/master/app/dashboards',
    beforeEnter: AuthRequired,
    children: [
      {
        path: 'dashboards',
        component: () => import('./views/app/dashboards'),
        redirect: '/master/app/dashboards/main',
        children: [
          { path: 'main', component: () => import('./views/app/dashboards/Main') }
        ]
      },
      {
        path: 'main',
        component: () => import('./views/app/main'),
        redirect: '/master/app/main/user',
        children: [
          { path: 'user', component: () => import('./views/app/main/User') },
          { path: 'batch', component: () => import('./views/app/main/Batch') },
          { path: 'template', component: () => import('./views/app/main/Template') },
          { path: 'total', component: () => import('./views/app/main/Total') },
          { path: 'push', component: () => import('./views/app/main/Push') }
        ]
      },
      {
        path: 'comparison',
        component: () => import('./views/app/comparison'),
        redirect: '/master/app/comparison/student',
        children: [
          { path: 'student', component: () => import('./views/app/comparison/Student') },
          { path: 'major', component: () => import('./views/app/comparison/Major') },
          { path: 'dept', component: () => import('./views/app/comparison/Dept') },
          { path: 'coll', component: () => import('./views/app/comparison/Coll') },
          { path: 'gender', component: () => import('./views/app/comparison/Gender') },
          { path: 'descmajorcd', component: () => import('./views/app/comparison/Descmajorcd') },
          { path: 'descdeptcd', component: () => import('./views/app/comparison/Descdeptcd') },
          { path: 'desccollcd', component: () => import('./views/app/comparison/Desccollcd') },
          { path: 'numpplmajor', component: () => import('./views/app/comparison/Numpplmajor') },
          { path: 'numppldept', component: () => import('./views/app/comparison/Numppldept') },
          { path: 'numpplcoll', component: () => import('./views/app/comparison/Numpplcoll') }
        ]
      }
    ]
  },
  { path: '/master/error', component: () => import(/* webpackChunkName: "error" */ './views/Error') },
  {
    path: '/master/user',
    component: () => import(/* webpackChunkName: "user" */ './views/user'),
    redirect: '/master/user/login',
    children: [
      {
        path: 'login',
        beforeEnter: (to, from, next) => {
          if (localStorage.token) {
            return next('/')
          }
          return next()
        },
        component: () => import(/* webpackChunkName: "user" */ './views/user/Login')
      },
      {
        path: 'change-password',
        beforeEnter: AuthRequired,
        component: () => import(/* webpackChunkName: "change-password" */ './views/user/ChangePassword')
      }
    ]
  },
  { path: '*', component: () => import(/* webpackChunkName: "error" */ './views/Error') }
]

const router = new Router({
  linkActiveClass: 'active',
  routes,
  mode: 'history'
})

export default router
