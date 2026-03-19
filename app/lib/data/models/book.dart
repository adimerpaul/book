class Book {
  const Book({
    required this.id,
    required this.slug,
    required this.title,
    required this.author,
    required this.summary,
    required this.category,
    required this.subcategory,
    required this.publisher,
    required this.pages,
    required this.publishedAt,
    required this.coverUrl,
    required this.gallery,
    this.isFavorite = false,
  });

  final int id;
  final String slug;
  final String title;
  final String author;
  final String summary;
  final String category;
  final String subcategory;
  final String publisher;
  final int pages;
  final String publishedAt;
  final String? coverUrl;
  final List<String> gallery;
  final bool isFavorite;

  factory Book.fromApi(Map<String, dynamic> json) {
    return Book(
      id: (json['id'] as num?)?.toInt() ?? 0,
      slug: (json['slug'] as String? ?? '').trim(),
      title: (json['titulo'] as String? ?? 'Libro sin titulo').trim(),
      author: (json['autor'] as String? ?? 'Autor sin registrar').trim(),
      summary: (json['resumen'] as String? ?? '').trim(),
      category: (json['genero'] as String? ?? 'General').trim(),
      subcategory: (json['subgenero'] as String? ?? '').trim(),
      publisher: (json['editorial'] as String? ?? '').trim(),
      pages: (json['paginas'] as num?)?.toInt() ?? 0,
      publishedAt: (json['fecha_publicacion'] as String? ?? '').trim(),
      coverUrl: (json['portada_url'] as String?)?.trim(),
      gallery: ((json['fotografias'] as List?) ?? const [])
          .whereType<String>()
          .toList(),
    );
  }

  factory Book.fromMap(Map<String, Object?> map) {
    return Book(
      id: (map['id'] as num?)?.toInt() ?? 0,
      slug: (map['slug'] as String? ?? '').trim(),
      title: (map['title'] as String? ?? 'Libro sin titulo').trim(),
      author: (map['author'] as String? ?? 'Autor sin registrar').trim(),
      summary: (map['summary'] as String? ?? '').trim(),
      category: (map['category'] as String? ?? 'General').trim(),
      subcategory: (map['subcategory'] as String? ?? '').trim(),
      publisher: (map['publisher'] as String? ?? '').trim(),
      pages: (map['pages'] as num?)?.toInt() ?? 0,
      publishedAt: (map['published_at'] as String? ?? '').trim(),
      coverUrl: (map['cover_url'] as String?)?.trim(),
      gallery: (map['gallery'] as String? ?? '')
          .split('|')
          .where((value) => value.trim().isNotEmpty)
          .toList(),
      isFavorite: ((map['is_favorite'] as num?)?.toInt() ?? 0) == 1,
    );
  }

  Map<String, Object?> toMap() {
    return {
      'id': id,
      'slug': slug,
      'title': title,
      'author': author,
      'summary': summary,
      'category': category,
      'subcategory': subcategory,
      'publisher': publisher,
      'pages': pages,
      'published_at': publishedAt,
      'cover_url': coverUrl,
      'gallery': gallery.join('|'),
      'is_favorite': isFavorite ? 1 : 0,
    };
  }

  Book copyWith({bool? isFavorite}) {
    return Book(
      id: id,
      slug: slug,
      title: title,
      author: author,
      summary: summary,
      category: category,
      subcategory: subcategory,
      publisher: publisher,
      pages: pages,
      publishedAt: publishedAt,
      coverUrl: coverUrl,
      gallery: gallery,
      isFavorite: isFavorite ?? this.isFavorite,
    );
  }
}
