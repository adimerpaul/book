<template>
  <div>
    <SiteHeader />

    <main class="book-detail-page page-offset">
      <section class="section-shell section-divider" data-reveal>
        <div class="container book-detail-grid">
          <div class="book-detail-cover card-surface" :class="{ 'has-image': Boolean(book.portada_url) }">
            <img
              v-if="book.portada_url"
              :src="book.portada_url"
              :alt="`Portada de ${book.titulo}`"
            />
            <div v-else class="book-cover-placeholder">
              <span class="book-accent">{{ book.genero || 'Libro' }}</span>
              <strong>{{ book.titulo }}</strong>
              <small>{{ book.autor.nombre || 'Autor por confirmar' }}</small>
            </div>
          </div>

          <article class="book-detail-copy">
            <NuxtLink to="/libros" class="detail-back">Volver al catalogo</NuxtLink>
            <span class="eyebrow">Ficha editorial</span>
            <h1>{{ book.titulo }}</h1>
            <p class="detail-author">{{ book.autor.nombre }}</p>
            <p class="detail-summary">
              {{ book.resumen || 'Este libro ya forma parte del catalogo publicado de Latinas Editores.' }}
            </p>

            <div class="detail-tags">
              <span v-for="item in detailTags" :key="item">{{ item }}</span>
            </div>

            <div class="detail-content card-surface">
              <h2>Descripcion</h2>
              <p>{{ book.contenido || book.resumen || 'Contenido editorial en actualizacion.' }}</p>
            </div>

            <div v-if="book.reconocimiento" class="detail-content card-surface">
              <h2>Reconocimientos</h2>
              <p>{{ book.reconocimiento }}</p>
            </div>

            <div v-if="book.drive_indice_url" class="detail-actions">
              <a :href="book.drive_indice_url" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                Ver indice
              </a>
            </div>
          </article>
        </div>
      </section>

      <section v-if="book.fotografias.length" class="section-shell" data-reveal>
        <div class="container">
          <div class="section-heading align-left">
            <span class="eyebrow">Galeria</span>
            <h2>Imagenes relacionadas con la obra</h2>
          </div>

          <div class="detail-gallery">
            <div v-for="image in book.fotografias" :key="image" class="detail-gallery-item card-surface">
              <img :src="image" :alt="`Imagen del libro ${book.titulo}`" />
            </div>
          </div>
        </div>
      </section>
    </main>

    <SiteFooter />
  </div>
</template>

<script setup lang="ts">
import type { BookDetailResponse } from '~/types/books'

const route = useRoute()
const slug = computed(() => String(route.params.slug))

const { data, error } = await useAsyncData(
  () => `book-detail-${slug.value}`,
  () => $fetch<BookDetailResponse>(`/api/libros/${slug.value}`),
  {
    watch: [slug]
  }
)

if (error.value) {
  throw createError({
    statusCode: error.value.statusCode || 404,
    statusMessage: 'No se encontro el libro solicitado.'
  })
}

const book = computed(() => {
  if (!data.value?.data) {
    throw createError({
      statusCode: 404,
      statusMessage: 'No se encontro el libro solicitado.'
    })
  }

  return data.value.data
})

const detailTags = computed(() =>
  [
    book.value.genero,
    book.value.subgenero,
    book.value.editorial,
    book.value.idioma,
    book.value.pais,
    book.value.paginas ? `${book.value.paginas} paginas` : null,
    book.value.fecha_publicacion ? formatDate(book.value.fecha_publicacion) : null
  ].filter(Boolean) as string[]
)

useRevealOnScroll()

useSeoMeta({
  title: computed(() => `${book.value.titulo} | Editores Latinas LTA`),
  description: computed(
    () =>
      book.value.resumen ||
      `Conoce ${book.value.titulo}, libro publicado por Editores Latinas LTA.`
  ),
  ogTitle: computed(() => book.value.titulo),
  ogDescription: computed(
    () =>
      book.value.resumen ||
      `Ficha editorial y descripcion de ${book.value.titulo}.`
  ),
  ogImage: computed(() => book.value.portada_url || undefined),
  ogType: 'article'
})

function formatDate(value: string) {
  return new Intl.DateTimeFormat('es-BO', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  }).format(new Date(`${value}T00:00:00`))
}
</script>
