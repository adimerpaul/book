class HeroBannerItem {
  const HeroBannerItem({
    required this.id,
    required this.eyebrow,
    required this.title,
    required this.description,
    required this.badge,
    required this.theme,
    required this.primaryCtaLabel,
    required this.coverUrls,
  });

  final int id;
  final String eyebrow;
  final String title;
  final String description;
  final String badge;
  final String theme;
  final String primaryCtaLabel;
  final List<String> coverUrls;

  factory HeroBannerItem.fromApi(Map<String, dynamic> json) {
    return HeroBannerItem(
      id: (json['id'] as num?)?.toInt() ?? 0,
      eyebrow: (json['eyebrow'] as String? ?? '').trim(),
      title: (json['title'] as String? ?? 'Coleccion destacada').trim(),
      description: (json['description'] as String? ?? '').trim(),
      badge: (json['badge'] as String? ?? '').trim(),
      theme: (json['theme'] as String? ?? 'terracota').trim(),
      primaryCtaLabel: (json['primary_cta_label'] as String? ?? 'Ver libros')
          .trim(),
      coverUrls: ((json['covers'] as List?) ?? const [])
          .whereType<Map<String, dynamic>>()
          .map((item) => item['portada_url'] as String?)
          .whereType<String>()
          .where((value) => value.trim().isNotEmpty)
          .toList(),
    );
  }
}
