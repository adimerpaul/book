// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  css: ['~/assets/css/main.css'],
  app: {
    head: {
      title: 'Editores Latinas LTA',
      meta: [
        {
          name: 'description',
          content:
            'Editorial y librería digital dedicada a la venta y promoción de libros que impulsan la lectura, la cultura y el conocimiento.'
        }
      ]
    }
  }
})
