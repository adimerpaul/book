import axios from 'axios'

export default defineNuxtPlugin(() => {
  const requestUrl = useRequestURL()
  const baseURL = import.meta.server ? requestUrl.origin : ''

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
