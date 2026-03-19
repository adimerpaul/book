export type HeroSliderCover = {
  id: number
  titulo: string
  slug: string
  portada_url: string | null
}

export type PublicHeroSlider = {
  id: number
  eyebrow: string
  title: string
  description: string
  badge: string | null
  theme: 'heritage' | 'catalog' | 'community'
  primary_cta_label: string | null
  primary_cta_url: string | null
  secondary_cta_label: string | null
  secondary_cta_url: string | null
  covers: HeroSliderCover[]
}

export type PublicHeroSliderResponse = {
  data: PublicHeroSlider[]
}
