import '../models/order.dart';
import 'fallback_data.dart';

class OrderRepository {
  Future<List<OrderItem>> getOrders() async {
    await Future<void>.delayed(const Duration(milliseconds: 150));
    return FallbackData.orders;
  }
}
