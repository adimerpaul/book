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

export type Book = {
  id: number
  title: string
  author: string
  description: string
  format: string
  accent: string
  tone: string
}

export type Benefit = {
  title: string
  description: string
}

export const heroSlides: HeroSlide[] = [
  {
    id: 1,
    eyebrow: 'Editorial contemporánea',
    title: 'Libros que conectan conocimiento, memoria y futuro.',
    description:
      'En Editores Latinas LTA reunimos obras que inspiran pensamiento crítico, sensibilidad cultural y formación permanente.',
    badge: 'Colecciones con identidad',
    primaryCta: 'Ver libros',
    secondaryCta: 'Contáctanos',
    theme: 'heritage'
  },
  {
    id: 2,
    eyebrow: 'Catálogo curado',
    title: 'Una librería digital con mirada cultural y vocación comercial.',
    description:
      'Promovemos autores, colecciones y títulos pensados para lectores, instituciones, centros educativos y amantes del libro bien editado.',
    badge: 'Novedades y ediciones destacadas',
    primaryCta: 'Explorar catálogo',
    secondaryCta: 'Solicitar información',
    theme: 'catalog'
  },
  {
    id: 3,
    eyebrow: 'Lectura que transforma',
    title: 'Impulsamos la circulación de ideas que dejan huella.',
    description:
      'Cada publicación es una invitación a descubrir voces, ampliar horizontes y fortalecer el vínculo entre lectura, cultura y comunidad.',
    badge: 'Promoción de lectura y formación',
    primaryCta: 'Descubrir colecciones',
    secondaryCta: 'Hablar con el equipo',
    theme: 'community'
  }
]

export const books: Book[] = [
  {
    id: 1,
    title: 'Cartografías del Fuego',
    author: 'Mariela Suárez',
    description:
      'Una travesía narrativa sobre memoria, territorio y las huellas emocionales que definen nuestras ciudades.',
    format: 'Novela contemporánea',
    accent: 'Territorio',
    tone: 'crimson'
  },
  {
    id: 2,
    title: 'Bibliotecas para el Porvenir',
    author: 'Esteban Lora',
    description:
      'Ensayo claro y visionario sobre lectura pública, acceso al libro y el papel cultural de las bibliotecas.',
    format: 'Ensayo cultural',
    accent: 'Ensayo',
    tone: 'sand'
  },
  {
    id: 3,
    title: 'La Casa de las Palabras',
    author: 'Juliana Ferrer',
    description:
      'Historias breves llenas de intimidad, identidad y belleza verbal para lectores que buscan literatura con pulso.',
    format: 'Cuento literario',
    accent: 'Narrativa',
    tone: 'plum'
  },
  {
    id: 4,
    title: 'Aulas que Leen',
    author: 'Carlos Medina',
    description:
      'Herramientas y reflexiones para mediadores, docentes y proyectos educativos que quieren formar nuevos lectores.',
    format: 'Educación y mediación',
    accent: 'Educación',
    tone: 'ink'
  },
  {
    id: 5,
    title: 'Rituales del Papel',
    author: 'Valentina Ordoñez',
    description:
      'Un recorrido sensible por el objeto libro, la edición cuidada y el valor estético de la lectura impresa.',
    format: 'Arte editorial',
    accent: 'Diseño',
    tone: 'gold'
  },
  {
    id: 6,
    title: 'Semillas de Horizonte',
    author: 'Tomás Villalba',
    description:
      'Crónicas y perfiles sobre iniciativas culturales que transforman comunidades a través de la palabra escrita.',
    format: 'Crónica cultural',
    accent: 'Cultura',
    tone: 'forest'
  }
]

export const benefits: Benefit[] = [
  {
    title: 'Variedad de títulos',
    description:
      'Un catálogo pensado para lectores exigentes, instituciones educativas y proyectos culturales.'
  },
  {
    title: 'Compromiso con la cultura',
    description:
      'Promovemos libros que enriquecen la conversación pública y fortalecen la circulación de ideas.'
  },
  {
    title: 'Atención personalizada',
    description:
      'Acompañamos consultas comerciales, recomendaciones de lectura y necesidades de compra institucional.'
  },
  {
    title: 'Promoción de la lectura',
    description:
      'Concebimos cada publicación como una oportunidad para formar lectores y ampliar comunidades lectoras.'
  }
]
