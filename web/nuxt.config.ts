// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  css: ['~/assets/css/main.css'],
  runtimeConfig: {
    apiBase: process.env.NUXT_API_BASE || 'http://localhost:8000/api',
    public: {
      siteUrl: process.env.NUXT_PUBLIC_SITE_URL || 'http://localhost:3000'
    }
  },
  app: {
    head: {
      title: 'Editores Latinas LTA',
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
