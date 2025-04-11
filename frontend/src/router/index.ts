import { createRouter, createWebHistory } from 'vue-router'

import Login from '@/components/Login.vue';
import Home from '@/components/Home.vue';

const routes = [
  { path: '/', component: Login },
  {
    path: '/application',
    component: Home,
    redirect: '/application/inicio',
    children: [
      {
        path: 'inicio',
        component: {
          template: '<p>Você está na página inicial. Use o menu para navegar.</p>'
        }
      },
      { path: 'reservas', component: () => import('@/views/Reservations.vue') },
      { path: 'mesas', component: () => import('@/views/Tables.vue') },
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, _from, next) => {
  const isAuthenticated = !!localStorage.getItem('user_token');

  if (to.path !== '/' && !isAuthenticated) {
    next('/');
  } else if (to.path === '/' && isAuthenticated) {
    next('/home');
  } else {
    next();
  }
});

export default router;