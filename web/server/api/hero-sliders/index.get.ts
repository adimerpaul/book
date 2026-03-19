import type { PublicHeroSliderResponse } from '~/types/hero-sliders'

export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig(event)

  return await $fetch<PublicHeroSliderResponse>('/public/hero-sliders', {
    baseURL: config.apiBase
  })
})
