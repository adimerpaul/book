<template>
  <div>
    <SiteHeader />

    <main class="landing-page">
      <HeroCarousel :slides="heroSlides" />
      <AboutSection />
      <MissionVisionSection />
      <BooksGrid
        :books="books.data"
        title="Novedades publicadas y libros disponibles"
        description="La web ahora consulta el catalogo publicado desde la API y enlaza cada libro con una pagina propia."
        :show-catalog-link="true"
      />
      <PromoBanner />
      <BenefitsSection :benefits="benefits" />
    </main>

    <SiteFooter />
  </div>
</template>

<script setup lang="ts">
import type { PaginatedBooksResponse } from '~/types/books'
import { benefits, heroSlides } from '~/data/site'

const { data, error } = await useAsyncData('home-books', () =>
  $fetch<PaginatedBooksResponse>('/api/libros', {
    query: {
      page: 1,
      per_page: 6
    }
  })
)

if (error.value) {
  throw createError({
    statusCode: error.value.statusCode || 500,
    statusMessage: 'No fue posible cargar los libros publicados.'
  })
}

const books = computed<PaginatedBooksResponse>(() => {
  return (
    data.value ?? {
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
