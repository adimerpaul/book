import type { PublicHeroSliderResponse } from '~/types/hero-sliders'
import { backendApi } from '../../utils/api'

export default defineEventHandler(async (event) => {
  const api = backendApi(event)
  const { data } = await api.get<PublicHeroSliderResponse>('/public/hero-sliders')
  return data
})
