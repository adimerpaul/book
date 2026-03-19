export type HeroSlide = {
  id: number
  eyebrow: string
  title: string
  description: string
  badge: string
  primaryCta: string
  secondaryCta: string
  theme: 'heritage' | 'catalog' | 'community'
}

export type Benefit = {
  title: string
  description: string
}

export const heroSlides: HeroSlide[] = [
  {
    id: 1,
    eyebrow: 'Editorial contemporanea',
    title: 'Libros que conectan conocimiento, memoria y futuro.',
    description:
      'En Editores Latinas LTA reunimos obras que inspiran pensamiento critico, sensibilidad cultural y formacion permanente.',
    badge: 'Colecciones con identidad',
    primaryCta: 'Ver libros',
    secondaryCta: 'Contactanos',
    theme: 'heritage'
  },
  {
    id: 2,
    eyebrow: 'Catalogo curado',
    title: 'Una libreria digital con mirada cultural y vocacion comercial.',
    description:
      'Promovemos autores, colecciones y titulos pensados para lectores, instituciones, centros educativos y amantes del libro bien editado.',
    badge: 'Novedades y ediciones destacadas',
    primaryCta: 'Explorar catalogo',
    secondaryCta: 'Solicitar informacion',
    theme: 'catalog'
  },
  {
    id: 3,
    eyebrow: 'Lectura que transforma',
    title: 'Impulsamos la circulacion de ideas que dejan huella.',
    description:
      'Cada publicacion es una invitacion a descubrir voces, ampliar horizontes y fortalecer el vinculo entre lectura, cultura y comunidad.',
    badge: 'Promocion de lectura y formacion',
    primaryCta: 'Descubrir colecciones',
    secondaryCta: 'Hablar con el equipo',
    theme: 'community'
  }
]

export const benefits: Benefit[] = [
  {
    title: 'Variedad de titulos',
    description:
      'Un catalogo pensado para lectores exigentes, instituciones educativas y proyectos culturales.'
  },
  {
    title: 'Compromiso con la cultura',
    description:
      'Promovemos libros que enriquecen la conversacion publica y fortalecen la circulacion de ideas.'
  },
  {
    title: 'Atencion personalizada',
    description:
      'Acompanamos consultas comerciales, recomendaciones de lectura y necesidades de compra institucional.'
  },
  {
    title: 'Promocion de la lectura',
    description:
      'Concebimos cada publicacion como una oportunidad para formar lectores y ampliar comunidades lectoras.'
  }
]
