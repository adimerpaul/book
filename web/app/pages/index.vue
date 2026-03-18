<template>
  <div>
    <SiteHeader />

    <main class="landing-page">
      <HeroCarousel :slides="heroSlides" />
      <AboutSection />
      <MissionVisionSection />
      <BooksGrid :books="books" />
      <PromoBanner />
      <BenefitsSection :benefits="benefits" />
    </main>

    <SiteFooter />
  </div>
</template>

<script setup lang="ts">
import { onBeforeUnmount, onMounted } from 'vue'
import { benefits, books, heroSlides } from '~/data/site'

useSeoMeta({
  title: 'Editores Latinas LTA | Editorial y Librería Digital',
  description:
    'Landing page de Editores Latinas LTA, editorial dedicada a la venta y promoción de libros con enfoque cultural, elegante y contemporáneo.',
  ogTitle: 'Editores Latinas LTA',
  ogDescription:
    'Descubre una editorial moderna dedicada a libros, lectura, conocimiento y cultura.',
  ogType: 'website'
})

let observer: IntersectionObserver | null = null

onMounted(() => {
  const elements = document.querySelectorAll<HTMLElement>('[data-reveal]')

  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible')
          observer?.unobserve(entry.target)
        }
      })
    },
    {
      threshold: 0.18
    }
  )

  elements.forEach((element) => observer?.observe(element))
})

onBeforeUnmount(() => {
  observer?.disconnect()
})
</script>
