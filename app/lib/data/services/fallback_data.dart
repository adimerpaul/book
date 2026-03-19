import '../models/book.dart';
import '../models/hero_banner.dart';
import '../models/order.dart';

class FallbackData {
  FallbackData._();

  static const List<Book> books = [
    Book(
      id: 1,
      slug: 'biblioteca-del-viento',
      title: 'Biblioteca del viento',
      author: 'Lucia Andrade',
      summary: 'Una novela breve sobre memoria, barrio y lectura compartida.',
      category: 'Narrativa',
      subcategory: 'Novela',
      publisher: 'Latinas Editores',
      pages: 212,
      publishedAt: '2025-10-12',
      coverUrl: null,
      gallery: [],
      isFavorite: true,
    ),
    Book(
      id: 2,
      slug: 'cartas-para-la-escuela',
      title: 'Cartas para la escuela',
      author: 'Mariela Suarez',
      summary: 'Ensayos accesibles para docentes, lectores y mediadores.',
      category: 'Educacion',
      subcategory: 'Ensayo',
      publisher: 'Latinas Editores',
      pages: 176,
      publishedAt: '2024-07-03',
      coverUrl: null,
      gallery: [],
      isFavorite: true,
    ),
    Book(
      id: 3,
      slug: 'el-mapa-de-las-orillas',
      title: 'El mapa de las orillas',
      author: 'Carlos Medina',
      summary: 'Poesia contemporanea con una voz intimista y visual.',
      category: 'Poesia',
      subcategory: 'Coleccion de autor',
      publisher: 'Latinas Editores',
      pages: 98,
      publishedAt: '2025-01-20',
      coverUrl: null,
      gallery: [],
    ),
    Book(
      id: 4,
      slug: 'lecturas-para-el-aula',
      title: 'Lecturas para el aula',
      author: 'Ana Beltran',
      summary: 'Seleccion comentada para fomentar lectura en secundaria.',
      category: 'Educacion',
      subcategory: 'Pedagogia',
      publisher: 'Latinas Editores',
      pages: 240,
      publishedAt: '2023-11-11',
      coverUrl: null,
      gallery: [],
    ),
    Book(
      id: 5,
      slug: 'ciudad-hecha-de-papel',
      title: 'Ciudad hecha de papel',
      author: 'Lucia Andrade',
      summary: 'Relatos breves que mezclan vida urbana y sensibilidad editorial.',
      category: 'Narrativa',
      subcategory: 'Cuento',
      publisher: 'Latinas Editores',
      pages: 144,
      publishedAt: '2024-02-18',
      coverUrl: null,
      gallery: [],
    ),
    Book(
      id: 6,
      slug: 'atlas-de-autoras',
      title: 'Atlas de autoras',
      author: 'Paola Rios',
      summary: 'Una guia de lectura para descubrir nuevas voces latinoamericanas.',
      category: 'Critica',
      subcategory: 'Referencia',
      publisher: 'Latinas Editores',
      pages: 198,
      publishedAt: '2024-09-09',
      coverUrl: null,
      gallery: [],
    ),
  ];

  static const List<HeroBannerItem> banners = [
    HeroBannerItem(
      id: 1,
      eyebrow: 'Destacado de la semana',
      title: 'Historias que merecen quedarse contigo',
      description: 'Encuentra novedades editoriales, recomendaciones y libros para pedir en pocos pasos.',
      badge: 'Nuevos ingresos',
      theme: 'terracota',
      primaryCtaLabel: 'Explorar catalogo',
    ),
    HeroBannerItem(
      id: 2,
      eyebrow: 'Coleccion educativa',
      title: 'Libros pensados para lectura, aula y comunidad',
      description: 'Filtra por autor, categoria o interes y prepara tu siguiente pedido desde el celular.',
      badge: 'Mas consultados',
      theme: 'oliva',
      primaryCtaLabel: 'Ver recomendados',
    ),
  ];

  static const List<OrderItem> orders = [
    OrderItem(
      id: 1,
      code: 'PED-2401',
      status: 'En preparacion',
      createdAt: '19 Mar 2026',
      totalBooks: 3,
    ),
    OrderItem(
      id: 2,
      code: 'PED-2398',
      status: 'Entregado',
      createdAt: '15 Mar 2026',
      totalBooks: 2,
    ),
  ];
}
