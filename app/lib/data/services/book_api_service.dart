import 'dart:convert';

import 'package:http/http.dart' as http;

import '../../core/config/app_config.dart';
import '../models/book.dart';
import '../models/catalog_filters.dart';
import '../models/hero_banner.dart';

class BookApiService {
  BookApiService({http.Client? client}) : _client = client ?? http.Client();

  final http.Client _client;

  Future<List<Book>> fetchBooks({String search = ''}) async {
    final query = <String, String>{
      'per_page': '24',
      'page': '1',
      if (search.trim().isNotEmpty) 'search': search.trim(),
    };

    final uri = Uri.parse(
      '${AppConfig.apiBaseUrl}/public/libros',
    ).replace(queryParameters: query);

    final response = await _client.get(uri);
    if (response.statusCode < 200 || response.statusCode >= 300) {
      throw Exception('No se pudo recuperar el catalogo.');
    }

    final payload = jsonDecode(response.body) as Map<String, dynamic>;
    final items = (payload['data'] as List? ?? const []);

    return items.whereType<Map<String, dynamic>>().map(Book.fromApi).toList();
  }

  Future<List<HeroBannerItem>> fetchHeroBanners() async {
    final uri = Uri.parse('${AppConfig.apiBaseUrl}/public/hero-sliders');
    final response = await _client.get(uri);
    if (response.statusCode < 200 || response.statusCode >= 300) {
      throw Exception('No se pudo recuperar el carrusel.');
    }

    final payload = jsonDecode(response.body) as Map<String, dynamic>;
    final items = (payload['data'] as List? ?? const []);

    return items
        .whereType<Map<String, dynamic>>()
        .map(HeroBannerItem.fromApi)
        .toList();
  }

  Future<CatalogFilters> fetchCatalogFilters() async {
    final uri = Uri.parse('${AppConfig.apiBaseUrl}/public/catalogo/filtros');
    final response = await _client.get(uri);
    if (response.statusCode < 200 || response.statusCode >= 300) {
      throw Exception('No se pudieron recuperar los filtros.');
    }

    final payload = jsonDecode(response.body) as Map<String, dynamic>;
    return CatalogFilters.fromApi(payload);
  }
}
