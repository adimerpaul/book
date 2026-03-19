import type { PaginatedBooksResponse } from '~/types/books'

export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig(event)
  const query = getQuery(event)

  return await $fetch<PaginatedBooksResponse>('/public/libros', {
    baseURL: config.apiBase,
    query
  })
})
