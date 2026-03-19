<template>
  <a
    v-if="whatsAppHref"
    :href="whatsAppHref"
    class="whatsapp-float"
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Escribir por WhatsApp"
  >
    <span class="whatsapp-float-label">WhatsApp</span>
  </a>
</template>

<script setup lang="ts">
const props = defineProps<{
  phone?: string | null
  message?: string
}>()

const normalizedPhone = computed(() => (props.phone || '').replace(/[^\d]/g, ''))
const whatsAppHref = computed(() => {
  if (!normalizedPhone.value) return null
  const text = encodeURIComponent(props.message || 'Hola, quiero informacion sobre sus libros.')
  return `https://wa.me/${normalizedPhone.value}?text=${text}`
})
</script>
