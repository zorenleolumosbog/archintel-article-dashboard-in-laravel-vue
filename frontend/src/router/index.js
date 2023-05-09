import { createRouter, createWebHistory } from 'vue-router'
import TheLogin from '@/views/TheLogin.vue'
import TheRegister from '@/views/TheRegister.vue'
import TheDashboard from '@/views/TheDashboard.vue'
import TheUsers from '@/views/TheUsers.vue'
import TheCompanies from '@/views/TheCompanies.vue'
import TheArticles from '@/views/TheArticles.vue'
import NotFound from '@/views/NotFound.vue'
import axios from 'axios';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { 
      path: '/:pathMatch(.*)*', 
      name: 'nout-found', 
      component: NotFound 
    },
    {
      path: '/',
      name: 'login',
      component: TheLogin
    },
    {
      path: '/register',
      name: 'register',
      component: TheRegister
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: TheDashboard
    },
    {
      path: '/users',
      name: 'users',
      component: TheUsers
    },
    {
      path: '/companies',
      name: 'companies',
      component: TheCompanies
    },
    {
      path: '/articles',
      name: 'articles',
      component: TheArticles
    }
  ]
})

export default router
