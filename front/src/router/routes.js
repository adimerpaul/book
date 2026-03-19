const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue'), meta: {requiresAuth: true} },
      {
        path: '/usuarios',
        component: () => import('pages/usuarios/Usuarios.vue'),
        meta: {requiresAuth: true, perm: 'Usuarios'}
      },
      {
        path: '/autores',
        component: () => import('pages/autores/Autores.vue'),
        meta: {requiresAuth: true}
      },
      {
        path: '/libros',
        component: () => import('pages/libros/Libros.vue'),
        meta: {requiresAuth: true}
      },
      {
        path: '/hero-sliders',
        component: () => import('pages/hero-sliders/HeroSliders.vue'),
        meta: {requiresAuth: true}
      },
      {
        path: '/cox',
        component: () => import('pages/cox/Cox.vue'),
        meta: {requiresAuth: true}
      },
      {
        path:'/cambiar-contrasena',
        component: () => import('pages/cambiar-contrasena/CambiarContrasena.vue'),
        meta: { requiresAuth: true }
      }

    ]
  },
  {
    path: '/login',
    component: () => import('layouts/Login.vue'),
  },
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
