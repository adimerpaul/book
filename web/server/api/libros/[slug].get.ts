import type { BookDetailResponse } from '~/types/books'

export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig(event)
  const slug = getRouterParam(event, 'slug')

  return await $fetch<BookDetailResponse>(`/public/libros/${slug}`, {
    baseURL: config.apiBase
  })
})
