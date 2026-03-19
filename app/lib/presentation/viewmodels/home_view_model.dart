import 'package:flutter/foundation.dart';

import '../../data/models/book.dart';
import '../../data/models/hero_banner.dart';
import '../../data/services/book_repository.dart';

class HomeViewModel extends ChangeNotifier {
  HomeViewModel({BookRepository? repository})
      : _repository = repository ?? BookRepository();

  final BookRepository _repository;

  bool isLoading = false;
  String errorMessage = '';
  String search = '';
  String selectedCategory = 'Todos';
  String selectedAuthor = 'Todos';
  List<Book> _allBooks = const [];
  List<Book> books = const [];
  List<HeroBannerItem> banners = const [];

  Future<void> initialize() async {
    isLoading = true;
    errorMessage = '';
    notifyListeners();

    try {
      final results = await Future.wait([
        _repository.getBooks(),
        _repository.getHeroBanners(),
      ]);

      _allBooks = results[0] as List<Book>;
      banners = results[1] as List<HeroBannerItem>;
      _applyFilters();
    } catch (_) {
      errorMessage = 'No pudimos cargar el catalogo en este momento.';
      books = const [];
      banners = const [];
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }

  List<String> get categories {
    final values = _allBooks.map((book) => book.category).toSet().toList()..sort();
    return ['Todos', ...values];
  }

  List<String> get authors {
    final values = _allBooks.map((book) => book.author).toSet().toList()..sort();
    return ['Todos', ...values];
  }

  List<Book> get featuredBooks => books.where((book) => book.isFavorite).toList();

  void updateSearch(String value) {
    search = value;
    _applyFilters();
    notifyListeners();
  }

  void selectCategory(String value) {
    selectedCategory = value;
    _applyFilters();
    notifyListeners();
  }

  void selectAuthor(String value) {
    selectedAuthor = value;
    _applyFilters();
    notifyListeners();
  }

  Future<void> toggleFavorite(Book book) async {
    final updated = book.copyWith(isFavorite: !book.isFavorite);
    _allBooks = _allBooks
        .map((item) => item.id == book.id ? updated : item)
        .toList();
    _applyFilters();
    notifyListeners();
    await _repository.toggleFavorite(book);
  }

  void _applyFilters() {
    final normalizedSearch = search.trim().toLowerCase();
    books = _allBooks.where((book) {
      final matchesSearch = normalizedSearch.isEmpty ||
          book.title.toLowerCase().contains(normalizedSearch) ||
          book.author.toLowerCase().contains(normalizedSearch) ||
          book.category.toLowerCase().contains(normalizedSearch) ||
          book.subcategory.toLowerCase().contains(normalizedSearch);
      final matchesCategory =
          selectedCategory == 'Todos' || book.category == selectedCategory;
      final matchesAuthor =
          selectedAuthor == 'Todos' || book.author == selectedAuthor;

      return matchesSearch && matchesCategory && matchesAuthor;
    }).toList();
  }
}
