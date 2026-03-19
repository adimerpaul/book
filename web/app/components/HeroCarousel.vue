<template>
  <section id="inicio" class="hero section-shell section-divider">
    <div class="container">
      <div class="hero-shell">
        <div class="hero-copy">
          <span class="eyebrow">{{ activeSlide.eyebrow }}</span>
          <h1>{{ activeSlide.title }}</h1>
          <p class="hero-text">{{ activeSlide.description }}</p>

          <div class="hero-actions">
            <NuxtLink to="/libros" class="btn btn-primary">{{ activeSlide.primaryCta }}</NuxtLink>
            <NuxtLink to="/#contacto" class="btn btn-secondary">{{ activeSlide.secondaryCta }}</NuxtLink>
          </div>

          <div class="hero-meta">
            <span class="meta-chip">{{ activeSlide.badge }}</span>
            <span class="meta-note">Diseno editorial, cultura y lectura con presencia comercial.</span>
          </div>
        </div>

        <div class="hero-stage">
          <div class="stage-backdrop"></div>
          <div class="stage-orbit orbit-one"></div>
          <div class="stage-orbit orbit-two"></div>

          <div
            v-for="slide in slides"
            :key="slide.id"
            class="stage-card"
            :class="[
              `card-${slide.theme}`,
              {
                'is-active': slide.id === activeSlide.id
              }
            ]"
          >
            <div class="cover-stack">
              <span class="cover cover-main"></span>
              <span class="cover cover-side cover-side-a"></span>
              <span class="cover cover-side cover-side-b"></span>
            </div>
            <div class="stage-card-copy">
              <small>{{ slide.eyebrow }}</small>
              <strong>{{ slide.badge }}</strong>
              <p>{{ slide.description }}</p>
            </div>
          </div>

          <div class="hero-controls" aria-label="Controles del carrusel">
            <button
              v-for="slide in slides"
              :key="slide.id"
              type="button"
              class="hero-dot"
              :class="{ 'is-active': slide.id === activeSlide.id }"
              :aria-label="`Ir al slide ${slide.id}`"
              @click="current = slide.id - 1"
            ></button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import type { HeroSlide } from '~/data/site'

const props = defineProps<{
  slides: HeroSlide[]
}>()

const current = ref(0)
const activeSlide = computed(() => props.slides[current.value] ?? props.slides[0])

let intervalId: ReturnType<typeof setInterval> | null = null

onMounted(() => {
  intervalId = setInterval(() => {
    current.value = (current.value + 1) % props.slides.length
  }, 5000)
})

onBeforeUnmount(() => {
  if (intervalId) {
    clearInterval(intervalId)
  }
})
</script>
