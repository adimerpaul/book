export type BookListItem = {
  id: number
  slug: string
  titulo: string
  autor: string | null
  resumen: string | null
  genero: string | null
  subgenero: string | null
  editorial: string | null
  paginas: number | null
  fecha_publicacion: string | null
  portada_url: string | null
  fotografias: string[]
}

export type BookDetail = {
  id: number
  slug: string
  titulo: string
  autor: {
    nombre: string | null
    fotografia_url: string | null
  }
  resumen: string | null
  contenido: string | null
  reconocimiento: string | null
  genero: string | null
  subgenero: string | null
  editorial: string | null
  idioma: string | null
  pais: string | null
  paginas: number | null
  fecha_publicacion: string | null
  drive_indice_url: string | null
  portada_url: string | null
  fotografias: string[]
}

export type PaginatedBooksResponse = {
  data: BookListItem[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number | null
    to: number | null
  }
  links: {
    prev: string | null
    next: string | null
  }
}

export type BookDetailResponse = {
  data: BookDetail
}
