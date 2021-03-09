import Vue from 'vue'
import Router from 'vue-router'
import AuthRequired from './utils/AuthRequired'

Vue.use(Router)

const routes = [
  {
    path: '/',
    component: () => import('./views/app'),
    redirect: '/app/dashboards',
    beforeEnter: AuthRequired,
    children: [
      {
        path: 'app/dashboards',
        component: () => import('./views/app/dashboards'),
        redirect: '/app/dashboards/main',
        children: [
          { path: 'main', component: () => import('./views/app/dashboards/Main') }
        ]
      },
      {
        path: 'app/planners',
        component: () => import('./views/app/planners'),
        redirect: '/app/planners/user',
        children: [
          { path: 'user', component: () => import('./views/app/planners/User') },
          { path: 'checklist', component: () => import('./views/app/planners/Checklist') },
          { path: 'schedule', component: () => import('./views/app/planners/Schedule') },
          { path: 'symptom', component: () => import('./views/app/planners/Symptom') },
          { path: 'spinfo', component: () => import('./views/app/planners/SymptomInfo') }
        ]
      },
      {
        path: 'app/report',
        redirect: '/app/reports/report/list'
      },
      {
        path: 'app/reports',
        component: () => import('./views/app/reports'),
        redirect: '/app/reports/batch',
        children: [
          { path: 'batch', component: () => import('./views/app/reports/Batch') },
          {
            path: 'report',
            component: () => import('./views/app/reports/report'),
            redirect: '/app/reports/report/list',
            children: [
              { path: 'list', component: () => import('./views/app/reports/report/List') },
              { path: 'plan', component: () => import('./views/app/reports/report/Plan') },
              { path: 'write', component: () => import('./views/app/reports/report/Write') }
            ]
          },
          {
            path: 'info',
            component: () => import('./views/app/reports/info'),
            redirect: '/app/reports/info/disease',
            children: [
              { path: 'disease', component: () => import('./views/app/reports/info/Disease') },
              { path: 'medicine', component: () => import('./views/app/reports/info/Medicine') },
              { path: 'nutrition', component: () => import('./views/app/reports/info/Nutrition') },
              { path: 'health', component: () => import('./views/app/reports/info/Health') }
            ]
          }
        ]
      },
      {
        path: 'app/customers',
        component: () => import('./views/app/customers'),
        redirect: '/app/customers/notice',
        children: [
          { path: 'notice', component: () => import('./views/app/customers/Notice') },
          { path: 'faq', component: () => import('./views/app/customers/Faq') },
          { path: 'qna', component: () => import('./views/app/customers/Qna') }
        ]
      },
      {
        path: 'app/etcs',
        component: () => import('./views/app/etcs'),
        redirect: '/app/etcs/admin',
        children: [
          { path: 'admin', component: () => import('./views/app/etcs/Admin') },
          { path: 'term', component: () => import('./views/app/etcs/Term') }
        ]
      }
    ]
  },
  { path: '/error', component: () => import(/* webpackChunkName: "error" */ './views/Error') },
  {
    path: '/user',
    component: () => import(/* webpackChunkName: "user" */ './views/user'),
    redirect: '/user/login',
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
