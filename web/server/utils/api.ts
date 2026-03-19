import axios from 'axios'

export function backendApi(event: Parameters<typeof defineEventHandler>[0]) {
  const config = useRuntimeConfig(event)

  return axios.create({
    baseURL: config.apiBase,
    withCredentials: false,
  })
}
