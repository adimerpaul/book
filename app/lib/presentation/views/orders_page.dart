import 'package:flutter/material.dart';

import '../viewmodels/orders_view_model.dart';

class OrdersPage extends StatefulWidget {
  const OrdersPage({super.key});

  @override
  State<OrdersPage> createState() => _OrdersPageState();
}

class _OrdersPageState extends State<OrdersPage> {
  late final OrdersViewModel _viewModel;

  @override
  void initState() {
    super.initState();
    _viewModel = OrdersViewModel()..initialize();
  }

  @override
  void dispose() {
    _viewModel.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return AnimatedBuilder(
      animation: _viewModel,
      builder: (context, _) {
        return SafeArea(
          child: ListView(
            padding: const EdgeInsets.fromLTRB(20, 16, 20, 28),
            children: [
              Text('Mis pedidos', style: theme.textTheme.headlineMedium),
              const SizedBox(height: 8),
              Text(
                'Aqui veras el estado de tus pedidos y el historial reciente.',
                style: theme.textTheme.bodyLarge?.copyWith(
                  color: const Color(0xFF675D55),
                ),
              ),
              const SizedBox(height: 24),
              if (_viewModel.isLoading)
                const Center(child: CircularProgressIndicator())
              else if (_viewModel.orders.isEmpty)
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(20),
                    child: Text(
                      'Aun no tienes pedidos guardados. Cuando registremos compras, apareceran aqui desde SQLite.',
                      style: theme.textTheme.bodyLarge,
                    ),
                  ),
                )
              else
                ..._viewModel.orders.map(
                  (order) => Card(
                    margin: const EdgeInsets.only(bottom: 14),
                    child: Padding(
                      padding: const EdgeInsets.all(18),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                order.code,
                                style: theme.textTheme.titleMedium,
                              ),
                              Container(
                                padding: const EdgeInsets.symmetric(
                                  horizontal: 10,
                                  vertical: 6,
                                ),
                                decoration: BoxDecoration(
                                  color: const Color(0xFFEADBC9),
                                  borderRadius: BorderRadius.circular(999),
                                ),
                                child: Text(order.status),
                              ),
                            ],
                          ),
                          const SizedBox(height: 12),
                          Text('Fecha: ${order.createdAt}'),
                          const SizedBox(height: 6),
                          Text('Libros en el pedido: ${order.totalBooks}'),
                        ],
                      ),
                    ),
                  ),
                ),
            ],
          ),
        );
      },
    );
  }
}
