import { createRouter, createWebHistory } from 'vue-router'

import store from '@/store'

const ifNotAuthenticated = (to, from, next) => {
    if (!store.getters['app/isAuthenticated']) {
        next()
        return
    }
    next('/')
}

const ifAuthenticated = (to, from, next) => {
    if (store.getters['app/isAuthenticated']) {
        next()
        return
    }
    //next('/login')
    return next({ path: '/login' })
}


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    { path: '/', redirect: '/home' },
    {
      path: '/',
      component: () => import('../layouts/default.vue'),
      children: [
        {
          path: 'home',
          name: 'home',
          component: () => import('../views/home.vue'),
          beforeEnter: ifAuthenticated,
          
        },
        {
          path: '/grupos', 
          name: 'grupos',
          component: () => import('@/views/grupos/index.vue'),
          beforeEnter: ifAuthenticated,
          meta: {
            layout: 'content',
            requiresAuth: true,
            //levelOne: 'evaluar'
          },
        },
        {
          path: '/reportes', 
          name: 'reportes',
          component: () => import('@/views/reportes/index.vue'),
          beforeEnter: ifAuthenticated,
          meta: {
            layout: 'content',
            requiresAuth: true,
            //levelOne: 'evaluar'
          },
        },

    
      ]
    },
    {
      path: '/',
      component: () => import('../layouts/blank.vue'),
      children: [
        {
          path: 'login',
          name: 'login',
          component: () => import('../views/login.vue'),
          beforeEnter: ifNotAuthenticated
        },
        {
          path: 'login/:id/:token',
          name: 'loginid',
          component: () => import('../views/login.vue'),
          beforeEnter: ifNotAuthenticated,
        },
        {
          path: 'error-401',
          name: 'error-401',
          component: () => import('../views/notRegistered.vue'),
        },
        {
          path: 'error-403',
          name: 'error-403',
          component: () => import('../views/NotAuthorized.vue'),
        },
        {
          path: '/:pathMatch(.*)*',
          name: 'error-404',
          component: () => import('../views/Error404.vue'),
        },
      ],
    },
  ],
})

function searchPath(argument, level, levelTwo, path) {
  var find = argument.find(el => el.path == level);
  if (find != undefined) {
    if(find.children) {
        if (!searchPath(find.children, levelTwo, null, path)) {
          return searchPath(find.children, path, null, null)
        } else {
          return true
        }
    } else {
      return true
    }
  } 

  return false
}

router.beforeEach((to, _, next) => {
  //console.log(to)
  var menu = store.state.app.menu
  const levelOne=to.meta.levelOne ? to.meta.levelOne : to.path
  const levelTwo=to.meta.levelTwo ? to.meta.levelTwo : to.path

  if(to.meta.requiresAuth && to.meta.noValidate==undefined) {

    if (!store.getters['app/isAuthenticated']) return next({ path: '/login' })
    var findpath = searchPath(menu, levelOne, levelTwo, to.path) 
    if(!findpath) return next({ name: 'error-403' })
  }
    
  return next()

})



export default router
