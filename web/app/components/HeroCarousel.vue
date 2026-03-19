<template>
  <section id="inicio" class="hero section-shell section-divider">
    <div class="container">
      <div class="hero-shell">
        <div class="hero-copy">
          <span class="eyebrow">{{ activeSlide.eyebrow }}</span>
          <h1>{{ activeSlide.title }}</h1>
          <p class="hero-text">{{ activeSlide.description }}</p>

          <div class="hero-actions">
            <a :href="activeSlide.primary_cta_url || '/libros'" class="btn btn-primary">
              {{ activeSlide.primary_cta_label || 'Ver libros' }}
            </a>
            <a :href="activeSlide.secondary_cta_url || '/#contacto'" class="btn btn-secondary">
              {{ activeSlide.secondary_cta_label || 'Contactanos' }}
            </a>
          </div>

          <div class="hero-meta">
            <span class="meta-chip">{{ activeSlide.badge || 'Colecciones con identidad' }}</span>
            <span class="meta-note">Diseno editorial, cultura y lectura con presencia comercial.</span>
          </div>
        </div>

        <div class="hero-stage">
          <div class="stage-backdrop"></div>
          <div class="stage-orbit orbit-one"></div>
          <div class="stage-orbit orbit-two"></div>

          <div
            v-for="(slide, slideIndex) in resolvedSlides"
            :key="slide.id"
            class="stage-card"
            :class="[
              `card-${slide.theme}`,
              {
                'is-active': slideIndex === current
              }
            ]"
          >
            <div class="cover-stack">
              <template v-if="slide.covers.length">
                <a
                  v-for="(cover, index) in slide.covers.slice(0, 3)"
                  :key="cover.id"
                  class="cover cover-link"
                  :class="coverClass(index)"
                  :href="`/libros/${cover.slug}`"
                  :aria-label="`Ver libro ${cover.titulo}`"
                >
                  <img
                    v-if="cover.portada_url"
                    :src="cover.portada_url"
                    :alt="`Portada de ${cover.titulo}`"
                  >
                  <span v-else class="cover-fallback">{{ cover.titulo }}</span>
                </a>
              </template>
              <template v-else>
                <span class="cover cover-main"></span>
                <span class="cover cover-side cover-side-a"></span>
                <span class="cover cover-side cover-side-b"></span>
              </template>
            </div>
            <div class="stage-card-copy">
              <small>{{ slide.eyebrow }}</small>
              <strong>{{ slide.badge || slide.title }}</strong>
              <p>{{ slide.description }}</p>
            </div>
          </div>

          <div class="hero-controls" aria-label="Controles del carrusel">
            <button
              v-for="(slide, slideIndex) in resolvedSlides"
              :key="slide.id"
              type="button"
              class="hero-dot"
              :class="{ 'is-active': slideIndex === current }"
              :aria-label="`Ir al slide ${slide.id}`"
              @click="current = slideIndex"
            ></button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import type { PublicHeroSlider } from '~/types/hero-sliders'

const props = defineProps<{
  slides: PublicHeroSlider[]
}>()

const current = ref(0)
const fallbackSlide: PublicHeroSlider = {
  id: 0,
  eyebrow: 'Editorial contemporanea',
  title: 'Libros que conectan conocimiento, memoria y futuro.',
  description:
    'Editores Latinas reúne obras que inspiran pensamiento critico, sensibilidad cultural y formacion permanente.',
  badge: 'Colecciones con identidad',
  theme: 'heritage',
  primary_cta_label: 'Ver libros',
  primary_cta_url: '/libros',
  secondary_cta_label: 'Contactanos',
  secondary_cta_url: '/#contacto',
  covers: []
}
const resolvedSlides = computed(() => (props.slides.length ? props.slides : [fallbackSlide]))
const activeSlide = computed(() => resolvedSlides.value[current.value] ?? resolvedSlides.value[0])

let intervalId: ReturnType<typeof setInterval> | null = null

onMounted(() => {
  intervalId = setInterval(() => {
    current.value = (current.value + 1) % resolvedSlides.value.length
  }, 5000)
})

onBeforeUnmount(() => {
  if (intervalId) {
    clearInterval(intervalId)
  }
})

function coverClass(index: number) {
  if (index === 0) return 'cover-main'
  if (index === 1) return 'cover-side cover-side-a'
  return 'cover-side cover-side-b'
}
</script>
