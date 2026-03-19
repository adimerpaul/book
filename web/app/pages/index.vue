<template>
  <div>
    <SiteHeader />

    <main class="landing-page">
      <HeroCarousel :slides="heroSlides" />
      <AboutSection />
      <MissionVisionSection />
      <BooksGrid
        :books="books.data"
        :whatsapp-number="whatsappNumber"
        title="Novedades publicadas y libros disponibles"
        description="La web ahora consulta el catalogo publicado desde la API y enlaza cada libro con una pagina propia."
        :show-catalog-link="true"
      />
      <PromoBanner />
      <BenefitsSection :benefits="benefits" />
    </main>

    <SiteFooter />
    <WhatsAppFloat :phone="whatsappNumber" />
  </div>
</template>

<script setup lang="ts">
import type { PaginatedBooksResponse } from '~/types/books'
import type { PublicCoxResponse } from '~/types/cox'
import type { PublicHeroSliderResponse } from '~/types/hero-sliders'
import { benefits } from '~/data/site'

const { $axios } = useNuxtApp()

const { data, error } = await useAsyncData('home-content', async () => {
  const [hero, books, cox] = await Promise.all([
    $axios.get<PublicHeroSliderResponse>('/api/hero-sliders'),
    $axios.get<PaginatedBooksResponse>('/api/libros', {
      params: {
        page: 1,
        per_page: 6
      }
    }),
    $axios.get<PublicCoxResponse>('/api/cox')
  ])

  return {
    hero: hero.data,
    books: books.data,
    cox: cox.data
  }
})

if (error.value) {
  throw createError({
    statusCode: error.value.statusCode || 500,
    statusMessage: 'No fue posible cargar el contenido principal de la web.'
  })
}

const heroSlides = computed<PublicHeroSliderResponse['data']>(() => data.value?.hero?.data ?? [])
const whatsappNumber = computed(() => data.value?.cox?.data?.whatsapp_number || null)

const books = computed<PaginatedBooksResponse>(() => {
  return (
    data.value?.books ?? {
      data: [],
      meta: {
        current_page: 1,
        last_page: 1,
        per_page: 6,
        total: 0,
        from: null,
        to: null
      },
      links: {
        prev: null,
        next: null
      }
    }
  )
})

useSeoMeta({
  title: 'Editores Latinas LTA | Editorial y Libreria Digital',
  description:
    'Landing page de Editores Latinas LTA, editorial dedicada a la venta y promocion de libros con enfoque cultural, elegante y contemporaneo.',
  ogTitle: 'Editores Latinas LTA',
  ogDescription:
    'Descubre una editorial moderna dedicada a libros, lectura, conocimiento y cultura.',
  ogType: 'website'
})

useRevealOnScroll()
</script>
