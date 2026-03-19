import type { PaginatedBooksResponse } from '~/types/books'
import { backendApi } from '../../utils/api'

export default defineEventHandler(async (event) => {
  const api = backendApi(event)
  const query = getQuery(event)

  const { data } = await api.get<PaginatedBooksResponse>('/public/libros', {
    params: query
  })

  return data
})
