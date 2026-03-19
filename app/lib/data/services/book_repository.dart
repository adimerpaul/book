import '../models/book.dart';
import '../models/hero_banner.dart';
import 'book_api_service.dart';
import 'book_local_database.dart';
import 'fallback_data.dart';

class BookRepository {
  BookRepository({
    BookApiService? apiService,
    BookLocalDatabase? localDatabase,
  })  : _apiService = apiService ?? BookApiService(),
        _localDatabase = localDatabase ?? BookLocalDatabase.instance;

  final BookApiService _apiService;
  final BookLocalDatabase _localDatabase;

  Future<List<Book>> getBooks({String search = ''}) async {
    try {
      final remoteBooks = await _apiService.fetchBooks(search: search);
      if (remoteBooks.isNotEmpty) {
        await _preserveFavoritesAndCache(remoteBooks);
        return _applySearch(remoteBooks, search);
      }
    } catch (_) {
      // La app sigue funcionando con cache local o datos de respaldo.
    }

    final localBooks = await _localDatabase.getBooks();
    if (localBooks.isNotEmpty) {
      return _applySearch(localBooks, search);
    }

    await _localDatabase.replaceBooks(FallbackData.books);
    return _applySearch(FallbackData.books, search);
  }

  Future<List<HeroBannerItem>> getHeroBanners() async {
    try {
      final remoteItems = await _apiService.fetchHeroBanners();
      if (remoteItems.isNotEmpty) {
        return remoteItems;
      }
    } catch (_) {
      // El home puede renderizar banners locales.
    }

    return FallbackData.banners;
  }

  Future<void> toggleFavorite(Book book) async {
    await _localDatabase.updateFavorite(book.id, !book.isFavorite);
  }

  Future<void> _preserveFavoritesAndCache(List<Book> remoteBooks) async {
    final localBooks = await _localDatabase.getBooks();
    final favoritesById = {
      for (final book in localBooks) book.id: book.isFavorite,
    };

    final merged = remoteBooks
        .map((book) => book.copyWith(isFavorite: favoritesById[book.id] ?? false))
        .toList();

    await _localDatabase.replaceBooks(merged);
  }

  List<Book> _applySearch(List<Book> books, String search) {
    final normalized = search.trim().toLowerCase();
    if (normalized.isEmpty) {
      return books;
    }

    return books.where((book) {
      return book.title.toLowerCase().contains(normalized) ||
          book.author.toLowerCase().contains(normalized) ||
          book.category.toLowerCase().contains(normalized) ||
          book.subcategory.toLowerCase().contains(normalized);
    }).toList();
  }
}
