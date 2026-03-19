import '../models/order.dart';
import 'book_local_database.dart';

class OrderRepository {
  OrderRepository({BookLocalDatabase? localDatabase})
    : _localDatabase = localDatabase ?? BookLocalDatabase.instance;

  final BookLocalDatabase _localDatabase;

  Future<List<OrderItem>> getOrders() async {
    return _localDatabase.getOrders();
  }
}
