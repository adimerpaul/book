<template>
  <section class="section-shell section-divider" data-reveal>
    <div class="container">
      <div class="section-heading">
        <span class="eyebrow">Por Qué Elegirnos</span>
        <h2>Una propuesta editorial que equilibra selección, cercanía y proyección comercial.</h2>
      </div>

      <div class="benefits-grid">
        <article v-for="(benefit, index) in benefits" :key="benefit.title" class="benefit-card card-surface">
          <span class="benefit-index">0{{ index + 1 }}</span>
          <h3>{{ benefit.title }}</h3>
          <p>{{ benefit.description }}</p>
        </article>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import type { Benefit } from '~/data/site'

defineProps<{
  benefits: Benefit[]
}>()
</script>
