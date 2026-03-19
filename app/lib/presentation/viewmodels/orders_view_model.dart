import 'package:flutter/foundation.dart';

import '../../data/models/order.dart';
import '../../data/services/order_repository.dart';

class OrdersViewModel extends ChangeNotifier {
  OrdersViewModel({OrderRepository? repository})
      : _repository = repository ?? OrderRepository();

  final OrderRepository _repository;

  bool isLoading = false;
  List<OrderItem> orders = const [];

  Future<void> initialize() async {
    isLoading = true;
    notifyListeners();
    orders = await _repository.getOrders();
    isLoading = false;
    notifyListeners();
  }
}
