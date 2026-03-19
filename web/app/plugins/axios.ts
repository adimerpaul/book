import axios from 'axios'

export default defineNuxtPlugin(() => {
  const config = useRuntimeConfig()
  const baseURL = import.meta.server ? config.apiBase : config.public.apiBase

  const api = axios.create({
    baseURL,
    withCredentials: false,
  })

  return {
    provide: {
      axios: api,
    },
  }
})
