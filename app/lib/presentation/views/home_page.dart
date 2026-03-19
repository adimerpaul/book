import 'dart:async';

import 'package:flutter/material.dart';

import '../viewmodels/home_view_model.dart';
import '../widgets/book_card.dart';
import '../widgets/filter_chip_group.dart';
import '../widgets/hero_carousel.dart';
import '../widgets/section_header.dart';

class HomePage extends StatefulWidget {
  const HomePage({super.key});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  late final HomeViewModel _viewModel;
  final TextEditingController _controller = TextEditingController();
  Timer? _searchDebounce;

  @override
  void initState() {
    super.initState();
    _viewModel = HomeViewModel()..initialize();
  }

  @override
  void dispose() {
    _searchDebounce?.cancel();
    _controller.dispose();
    _viewModel.dispose();
    super.dispose();
  }

  void _onSearchChanged(String value) {
    _searchDebounce?.cancel();
    _searchDebounce = Timer(const Duration(milliseconds: 250), () {
      _viewModel.updateSearch(value);
    });
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return AnimatedBuilder(
      animation: _viewModel,
      builder: (context, _) {
        return SafeArea(
          child: RefreshIndicator(
            onRefresh: _viewModel.initialize,
            child: ListView(
              padding: const EdgeInsets.fromLTRB(20, 12, 20, 28),
              children: [
                Text(
                  'Encuentra tu proximo libro',
                  style: theme.textTheme.headlineMedium,
                ),
                const SizedBox(height: 8),
                Text(
                  'Busca por titulo, autor o categoria y arma tu pedido desde la app.',
                  style: theme.textTheme.bodyLarge?.copyWith(
                    color: const Color(0xFF675D55),
                  ),
                ),
                const SizedBox(height: 20),
                TextField(
                  controller: _controller,
                  onChanged: _onSearchChanged,
                  decoration: const InputDecoration(
                    hintText: 'Buscar libros, autores o categorias',
                    prefixIcon: Icon(Icons.search),
                  ),
                ),
                const SizedBox(height: 22),
                HeroCarousel(items: _viewModel.banners),
                const SizedBox(height: 24),
                SectionHeader(
                  title: 'Favoritos para pedir',
                  subtitle: 'Una mezcla de recomendados, novedades y libros guardados.',
                  actionLabel: '${_viewModel.featuredBooks.length} destacados',
                ),
                const SizedBox(height: 14),
                FilterChipGroup(
                  title: 'Categorias',
                  options: _viewModel.categories,
                  selected: _viewModel.selectedCategory,
                  onSelected: _viewModel.selectCategory,
                ),
                const SizedBox(height: 14),
                FilterChipGroup(
                  title: 'Autores',
                  options: _viewModel.authors,
                  selected: _viewModel.selectedAuthor,
                  onSelected: _viewModel.selectAuthor,
                ),
                const SizedBox(height: 18),
                if (_viewModel.isLoading)
                  const Padding(
                    padding: EdgeInsets.symmetric(vertical: 48),
                    child: Center(child: CircularProgressIndicator()),
                  )
                else if (_viewModel.errorMessage.isNotEmpty &&
                    _viewModel.books.isEmpty)
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 36),
                    child: Text(
                      _viewModel.errorMessage,
                      style: theme.textTheme.bodyLarge,
                    ),
                  )
                else if (_viewModel.books.isEmpty)
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 36),
                    child: Text(
                      'No encontramos libros con ese filtro. Prueba otra busqueda.',
                      style: theme.textTheme.bodyLarge,
                    ),
                  )
                else
                  ..._viewModel.books.map(
                    (book) => Padding(
                      padding: const EdgeInsets.only(bottom: 14),
                      child: BookCard(
                        book: book,
                        onFavoriteToggle: () => _viewModel.toggleFavorite(book),
                      ),
                    ),
                  ),
              ],
            ),
          ),
        );
      },
    );
  }
}
