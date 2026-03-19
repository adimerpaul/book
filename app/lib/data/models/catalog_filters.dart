class CatalogFilters {
  const CatalogFilters({required this.categories, required this.authors});

  final List<CategoryFilter> categories;
  final List<AuthorFilter> authors;

  factory CatalogFilters.fromApi(Map<String, dynamic> json) {
    final data = json['data'] as Map<String, dynamic>? ?? const {};

    return CatalogFilters(
      categories: ((data['categorias'] as List?) ?? const [])
          .whereType<Map<String, dynamic>>()
          .map(CategoryFilter.fromApi)
          .toList(),
      authors: ((data['autores'] as List?) ?? const [])
          .whereType<Map<String, dynamic>>()
          .map(AuthorFilter.fromApi)
          .toList(),
    );
  }
}

class CategoryFilter {
  const CategoryFilter({required this.name, required this.total});

  final String name;
  final int total;

  factory CategoryFilter.fromApi(Map<String, dynamic> json) {
    return CategoryFilter(
      name: (json['nombre'] as String? ?? '').trim(),
      total: (json['total'] as num?)?.toInt() ?? 0,
    );
  }
}

class AuthorFilter {
  const AuthorFilter({
    required this.id,
    required this.name,
    required this.photoUrl,
    required this.total,
  });

  final int id;
  final String name;
  final String? photoUrl;
  final int total;

  factory AuthorFilter.fromApi(Map<String, dynamic> json) {
    return AuthorFilter(
      id: (json['id'] as num?)?.toInt() ?? 0,
      name: (json['nombre'] as String? ?? '').trim(),
      photoUrl: (json['fotografia_url'] as String?)?.trim(),
      total: (json['total'] as num?)?.toInt() ?? 0,
    );
  }
}
