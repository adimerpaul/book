<template>
  <header class="site-header">
    <div class="container header-inner">
      <a href="#inicio" class="brand" aria-label="Ir al inicio de Editores Latinas LTA">
        <span class="brand-mark">EL</span>
        <span class="brand-copy">
          <strong>Editores Latinas LTA</strong>
          <small>Editorial y librería digital</small>
        </span>
      </a>

      <nav class="nav" aria-label="Navegación principal">
        <a v-for="item in items" :key="item.href" :href="item.href">{{ item.label }}</a>
      </nav>

      <a href="#libros" class="btn btn-primary header-cta">Ver catálogo</a>
    </div>
  </header>
</template>

<script setup lang="ts">
const items = [
  { label: 'Inicio', href: '#inicio' },
  { label: 'Sobre Nosotros', href: '#sobre-nosotros' },
  { label: 'Misión', href: '#mision-vision' },
  { label: 'Visión', href: '#mision-vision' },
  { label: 'Nuestros Libros', href: '#libros' },
  { label: 'Contacto', href: '#contacto' }
]
</script>
