import type { PublicCoxResponse } from '~/types/cox'
import { backendApi } from '../../utils/api'

export default defineEventHandler(async (event) => {
  const api = backendApi(event)
  const { data } = await api.get<PublicCoxResponse>('/public/cox')
  return data
})
