import type { BookDetailResponse } from '~/types/books'
import { backendApi } from '../../utils/api'

export default defineEventHandler(async (event) => {
  const api = backendApi(event)
  const slug = getRouterParam(event, 'slug')

  const { data } = await api.get<BookDetailResponse>(`/public/libros/${slug}`)
  return data
})
