<template>
  <header class="site-header">
    <div class="container header-inner">
      <NuxtLink to="/#inicio" class="brand" aria-label="Ir al inicio de Editores Latinas LTA">
        <span class="brand-mark">EL</span>
        <span class="brand-copy">
          <strong>Editores Latinas LTA</strong>
          <small>Editorial y libreria digital</small>
        </span>
      </NuxtLink>

      <nav class="nav" aria-label="Navegacion principal">
        <NuxtLink v-for="item in items" :key="item.to" :to="item.to">{{ item.label }}</NuxtLink>
      </nav>

      <NuxtLink to="/libros" class="btn btn-primary header-cta">Ver catalogo</NuxtLink>
    </div>
  </header>
</template>

<script setup lang="ts">
const items = [
  { label: 'Inicio', to: '/#inicio' },
  { label: 'Sobre Nosotros', to: '/#sobre-nosotros' },
  { label: 'Mision', to: '/#mision-vision' },
  { label: 'Vision', to: '/#mision-vision' },
  { label: 'Libros', to: '/libros' },
  { label: 'Contacto', to: '/#contacto' }
]
</script>
