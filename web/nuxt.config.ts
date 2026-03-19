// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  css: ['~/assets/css/main.css'],
  runtimeConfig: {
    apiBase: process.env.NUXT_API_BASE || 'http://localhost:8000/api',
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || process.env.NUXT_API_BASE || 'http://localhost:8000/api',
      siteUrl: process.env.NUXT_PUBLIC_SITE_URL || 'http://localhost:3000'
    }
  },
  nitro: {
    prerender: {
      crawlLinks: false,
      routes: ['/', '/libros']
    }
  },
  app: {
    head: {
      title: 'Editores Latinas LTA',
      link: [
        { rel: 'icon', type: 'image/png', href: '/logo.png' },
        { rel: 'apple-touch-icon', href: '/logo.png' }
      ],
      meta: [
        {
          name: 'description',
          content:
            'Editorial y libreria digital dedicada a la venta y promocion de libros que impulsan la lectura, la cultura y el conocimiento.'
        }
      ]
    }
  }
})
