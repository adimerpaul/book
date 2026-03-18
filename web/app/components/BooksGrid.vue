<template>
  <section id="libros" class="section-shell section-divider" data-reveal>
    <div class="container">
      <div class="section-heading">
        <span class="eyebrow">Descubre Nuestros Libros</span>
        <h2>Un catálogo que combina literatura, pensamiento, formación y sensibilidad editorial.</h2>
        <p>
          Presentamos una selección de títulos con identidad propia, ideal para lectores curiosos,
          espacios educativos y proyectos culturales.
        </p>
      </div>

      <div class="books-grid">
        <article v-for="book in books" :key="book.id" class="book-card card-surface">
          <div class="book-cover" :class="`tone-${book.tone}`">
            <span class="book-accent">{{ book.accent }}</span>
            <strong>{{ book.title }}</strong>
            <small>{{ book.author }}</small>
          </div>
          <div class="book-content">
            <span class="book-format">{{ book.format }}</span>
            <h3>{{ book.title }}</h3>
            <p class="book-author">{{ book.author }}</p>
            <p>{{ book.description }}</p>
            <a href="#contacto" class="book-link">Ver más</a>
          </div>
        </article>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import type { Book } from '~/data/site'

defineProps<{
  books: Book[]
}>()
</script>
