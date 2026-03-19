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

            <div v-if="book.precio !== null" class="detail-price-block card-surface">
              <div>
                <small>Precio del libro</small>
                <strong>{{ formatPrice(book.precio) }}</strong>
              </div>

              <label class="quantity-field">
                <span>Cantidad</span>
                <input v-model.number="quantity" type="number" min="1">
              </label>

              <div class="detail-order-total">
                <small>Total estimado</small>
                <strong>{{ formatPrice(totalPrice) }}</strong>
              </div>

              <a
                v-if="whatsAppHref"
                :href="whatsAppHref"
                target="_blank"
                rel="noopener noreferrer"
                class="btn btn-primary"
              >
                Hacer un pedido
              </a>
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
              <a :href="book.drive_indice_url" target="_blank" rel="noopener noreferrer" class="btn btn-secondary">
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
    <WhatsAppFloat :phone="whatsappNumber" :message="bookMessage" />
  </div>
</template>

<script setup lang="ts">
import type { BookDetailResponse } from '~/types/books'
import type { PublicCoxResponse } from '~/types/cox'

const { $axios } = useNuxtApp()
const route = useRoute()
const slug = computed(() => String(route.params.slug))
const quantity = ref(1)

const { data, error } = await useAsyncData(
  () => `book-detail-${slug.value}`,
  async () => {
    const [book, cox] = await Promise.all([
      $axios.get<BookDetailResponse>(`/api/libros/${slug.value}`),
      $axios.get<PublicCoxResponse>('/api/cox')
    ])

    return {
      book: book.data,
      cox: cox.data
    }
  },
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
  if (!data.value?.book.data) {
    throw createError({
      statusCode: 404,
      statusMessage: 'No se encontro el libro solicitado.'
    })
  }

  return data.value.book.data
})

const whatsappNumber = computed(() => data.value?.cox?.data?.whatsapp_number || null)

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

const totalPrice = computed(() => (book.value.precio || 0) * Math.max(1, quantity.value || 1))
const bookMessage = computed(() => {
  const qty = Math.max(1, quantity.value || 1)
  const priceText = book.value.precio !== null ? formatPrice(totalPrice.value) : 'precio por confirmar'
  return `Hola, quiero hacer un pedido del libro "${book.value.titulo}" en cantidad ${qty}. Total estimado: ${priceText}.`
})
const whatsAppHref = computed(() => {
  const phone = (whatsappNumber.value || '').replace(/[^\d]/g, '')
  if (!phone) return null
  return `https://wa.me/${phone}?text=${encodeURIComponent(bookMessage.value)}`
})

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

function formatPrice(value: number) {
  return new Intl.NumberFormat('es-BO', {
    style: 'currency',
    currency: 'BOB',
    minimumFractionDigits: 2
  }).format(value)
}
</script>
