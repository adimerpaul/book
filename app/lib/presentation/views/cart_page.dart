import 'package:flutter/material.dart';

class CartPage extends StatelessWidget {
  const CartPage({super.key});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return SafeArea(
      child: ListView(
        padding: const EdgeInsets.fromLTRB(20, 16, 20, 28),
        children: [
          Text('Mi canasto', style: theme.textTheme.headlineMedium),
          const SizedBox(height: 8),
          Text(
            'Este espacio queda listo para agregar los libros seleccionados y confirmar el pedido.',
            style: theme.textTheme.bodyLarge?.copyWith(
              color: const Color(0xFF675D55),
            ),
          ),
          const SizedBox(height: 24),
          Card(
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Proximo paso', style: theme.textTheme.titleLarge),
                  const SizedBox(height: 12),
                  Text(
                    'Cuando conectemos pedidos reales, aqui podras revisar cantidades, direccion de entrega y total.',
                    style: theme.textTheme.bodyLarge,
                  ),
                  const SizedBox(height: 18),
                  FilledButton(
                    onPressed: () {},
                    child: const Text('Preparar flujo de checkout'),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
