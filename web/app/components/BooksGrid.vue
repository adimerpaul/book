<template>
  <section id="libros" class="section-shell section-divider" data-reveal>
    <div class="container">
      <div class="section-heading">
        <span class="eyebrow">{{ eyebrow }}</span>
        <h2>{{ title }}</h2>
        <p>{{ description }}</p>
      </div>

      <div v-if="books.length" class="books-grid">
        <article
          v-for="book in books"
          :key="book.id"
          class="book-card card-surface"
          role="link"
          tabindex="0"
          @click="handleCardClick($event, book.slug)"
          @keydown.enter="openBook(book.slug)"
        >
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
            <p v-if="book.precio !== null" class="book-price">{{ formatPrice(book.precio) }}</p>
            <p>
              {{
                book.resumen ||
                'Libro disponible en el catalogo publicado de Latinas Editores con ficha de detalle.'
              }}
            </p>

            <div v-if="book.precio !== null" class="book-order-inline">
              <label class="book-quantity-inline">
                <span>Cantidad</span>
                <input v-model.number="quantities[book.id]" type="number" min="1" @click.stop>
              </label>
              <a
                v-if="whatsAppHref(book)"
                :href="whatsAppHref(book)"
                class="btn btn-primary"
                target="_blank"
                rel="noopener noreferrer"
                @click.stop
              >
                Pedir por WhatsApp
              </a>
            </div>

            <div class="book-actions">
              <NuxtLink :to="`/libros/${book.slug}`" class="book-link" @click.stop>Ver detalle</NuxtLink>
            </div>
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

const props = withDefaults(
  defineProps<{
    books: BookListItem[]
    whatsappNumber?: string | null
    eyebrow?: string
    title?: string
    description?: string
    emptyMessage?: string
    showCatalogLink?: boolean
  }>(),
  {
    whatsappNumber: null,
    eyebrow: 'Descubre nuestros libros',
    title: 'Un catalogo que combina literatura, pensamiento, formacion y sensibilidad editorial.',
    description:
      'Presentamos una seleccion de titulos publicados para lectores curiosos, espacios educativos y proyectos culturales.',
    emptyMessage: 'Cuando se publiquen nuevos titulos apareceran aqui con su ficha completa.',
    showCatalogLink: false
  }
)

const router = useRouter()
const quantities = reactive<Record<number, number>>({})

watch(
  () => props.books,
  (items) => {
    items.forEach((book) => {
      if (!quantities[book.id]) {
        quantities[book.id] = 1
      }
    })
  },
  { immediate: true }
)

function openBook(slug: string) {
  router.push(`/libros/${slug}`)
}

function handleCardClick(event: MouseEvent, slug: string) {
  const target = event.target as HTMLElement | null
  if (target?.closest('a, button, input, label')) return
  openBook(slug)
}

function formatPrice(value: number) {
  return new Intl.NumberFormat('es-BO', {
    style: 'currency',
    currency: 'BOB',
    minimumFractionDigits: 2
  }).format(value)
}

function whatsAppHref(book: BookListItem) {
  const phone = (props.whatsappNumber || '').replace(/[^\d]/g, '')
  if (!phone || book.precio === null) return null
  const quantity = Math.max(1, quantities[book.id] || 1)
  const total = book.precio * quantity
  const text = encodeURIComponent(
    `Hola, quiero pedir el libro "${book.titulo}" en cantidad ${quantity}. Total estimado: ${formatPrice(total)}.`
  )
  return `https://wa.me/${phone}?text=${text}`
}
</script>
