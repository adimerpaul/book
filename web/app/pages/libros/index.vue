<template>
  <div>
    <SiteHeader />

    <main class="catalog-page page-offset">
      <section class="section-shell section-divider catalog-hero" data-reveal>
        <div class="container catalog-hero-grid">
          <div class="section-heading align-left catalog-hero-copy">
            <span class="eyebrow">Catalogo editorial</span>
            <h1>Libros publicados por Latinas Editores con consulta paginada y detalle por obra.</h1>
            <p>
              Explora nuestro catalogo activo con informacion editorial, autores y descripciones
              completas preparadas para una experiencia web indexable.
            </p>

            <form class="catalog-search" @submit.prevent="applySearch">
              <input
                v-model="searchInput"
                type="search"
                placeholder="Buscar por titulo, genero o autor"
                aria-label="Buscar libros"
              >
              <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
          </div>

          <div class="catalog-meta card-surface">
            <strong>{{ books.meta.total }}</strong>
            <span>titulos publicados en web</span>
            <p>
              Pagina {{ books.meta.current_page }} de {{ books.meta.last_page }} con {{ books.data.length }}
              resultados visibles.
            </p>
          </div>
        </div>
      </section>

      <BooksGrid
        :books="books.data"
        eyebrow="Catalogo disponible"
        title="Todos los libros publicados"
        description="Cada ficha enlaza a una pagina propia con informacion ampliada, preparada para SEO y lectura detallada."
      />

      <section class="section-shell pagination-shell" data-reveal>
        <div class="container">
          <nav
            v-if="books.meta.last_page > 1"
            class="pagination"
            aria-label="Paginacion del catalogo"
          >
            <NuxtLink
              class="btn btn-secondary"
              :class="{ 'is-disabled': books.meta.current_page <= 1 }"
              :to="books.meta.current_page > 1 ? pageLink(books.meta.current_page - 1) : '/libros'"
            >
              Pagina anterior
            </NuxtLink>

            <div class="pagination-status">
              <strong>{{ books.meta.current_page }}</strong>
              <span>de {{ books.meta.last_page }}</span>
            </div>

            <NuxtLink
              class="btn btn-primary"
              :class="{ 'is-disabled': books.meta.current_page >= books.meta.last_page }"
              :to="
                books.meta.current_page < books.meta.last_page
                  ? pageLink(books.meta.current_page + 1)
                  : pageLink(books.meta.current_page)
              "
            >
              Siguiente pagina
            </NuxtLink>
          </nav>
        </div>
      </section>
    </main>

    <SiteFooter />
  </div>
</template>

<script setup lang="ts">
import type { PaginatedBooksResponse } from '~/types/books'

const route = useRoute()
const router = useRouter()
const page = computed(() => {
  const value = Number(route.query.page ?? 1)
  return Number.isFinite(value) && value > 0 ? Math.floor(value) : 1
})
const search = computed(() => String(route.query.q ?? '').trim())
const searchInput = ref(search.value)

const { data, error } = await useAsyncData(
  () => `catalog-books-${page.value}-${search.value}`,
  () =>
    $fetch<PaginatedBooksResponse>('/api/libros', {
      query: {
        page: page.value,
        per_page: 9,
        search: search.value || undefined
      }
    }),
  {
    watch: [page, search]
  }
)

if (error.value) {
  throw createError({
    statusCode: error.value.statusCode || 500,
    statusMessage: 'No fue posible cargar el catalogo de libros.'
  })
}

const books = computed<PaginatedBooksResponse>(() => {
  return (
    data.value ?? {
      data: [],
      meta: {
        current_page: 1,
        last_page: 1,
        per_page: 9,
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

useRevealOnScroll()

useSeoMeta({
  title: computed(() =>
    search.value
      ? `Buscar libros: ${search.value} | Editores Latinas LTA`
      : page.value > 1
        ? `Libros publicados | Pagina ${page.value} | Editores Latinas LTA`
        : 'Libros publicados | Editores Latinas LTA'
  ),
  description: computed(() =>
    search.value
      ? `Resultados para "${search.value}" dentro del catalogo publicado de Editores Latinas LTA.`
      : 'Catalogo paginado de libros publicados por Editores Latinas LTA con fichas editoriales, autores y descripciones completas.'
  ),
  ogTitle: computed(() =>
    search.value ? `Buscar libros: ${search.value} | Editores Latinas LTA` : 'Catalogo de libros | Editores Latinas LTA'
  ),
  ogDescription: computed(() =>
    search.value
      ? `Explora coincidencias para ${search.value} dentro del catalogo publicado de Latinas Editores.`
      : 'Explora los libros publicados de Latinas Editores en una experiencia pensada para descubrimiento y SEO.'
  ),
  ogType: 'website'
})

function pageLink(targetPage: number) {
  return {
    path: '/libros',
    query: {
      ...(targetPage > 1 ? { page: String(targetPage) } : {}),
      ...(search.value ? { q: search.value } : {})
    }
  }
}

function applySearch() {
  router.push({
    path: '/libros',
    query: searchInput.value ? { q: searchInput.value } : {}
  })
}
</script>
