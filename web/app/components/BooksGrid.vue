<template>
  <section id="libros" class="section-shell section-divider" data-reveal>
    <div class="container">
      <div class="section-heading">
        <span class="eyebrow">{{ eyebrow }}</span>
        <h2>{{ title }}</h2>
        <p>{{ description }}</p>
      </div>

      <div v-if="books.length" class="books-grid">
        <article v-for="book in books" :key="book.id" class="book-card card-surface">
          <div class="book-cover" :class="{ 'has-image': Boolean(book.portada_url) }">
            <img
              v-if="book.portada_url"
              class="book-cover-image"
              :src="book.portada_url"
              :alt="`Portada de ${book.titulo}`"
            />
            <template v-else>
              <span class="book-accent">{{ book.genero || 'Libro' }}</span>
              <strong>{{ book.titulo }}</strong>
              <small>{{ book.autor || 'Autor por confirmar' }}</small>
            </template>
          </div>

          <div class="book-content">
            <span class="book-format">{{ book.subgenero || book.genero || 'Catalogo editorial' }}</span>
            <h3>{{ book.titulo }}</h3>
            <p class="book-author">{{ book.autor || 'Autor por confirmar' }}</p>
            <p>
              {{
                book.resumen ||
                'Libro disponible en el catalogo publicado de Latinas Editores con ficha de detalle.'
              }}
            </p>
            <NuxtLink :to="`/libros/${book.slug}`" class="book-link">Ver detalle</NuxtLink>
          </div>
        </article>
      </div>

      <div v-else class="catalog-empty card-surface">
        <strong>No hay libros publicados por el momento.</strong>
        <p>{{ emptyMessage }}</p>
      </div>

      <div v-if="showCatalogLink" class="catalog-cta">
        <NuxtLink to="/libros" class="btn btn-secondary">Explorar catalogo completo</NuxtLink>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import type { BookListItem } from '~/types/books'

withDefaults(
  defineProps<{
    books: BookListItem[]
    eyebrow?: string
    title?: string
    description?: string
    emptyMessage?: string
    showCatalogLink?: boolean
  }>(),
  {
    eyebrow: 'Descubre nuestros libros',
    title: 'Un catalogo que combina literatura, pensamiento, formacion y sensibilidad editorial.',
    description:
      'Presentamos una seleccion de titulos publicados para lectores curiosos, espacios educativos y proyectos culturales.',
    emptyMessage: 'Cuando se publiquen nuevos titulos apareceran aqui con su ficha completa.',
    showCatalogLink: false
  }
)
</script>
