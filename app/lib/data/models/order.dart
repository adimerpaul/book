class OrderItem {
  const OrderItem({
    required this.id,
    required this.code,
    required this.status,
    required this.createdAt,
    required this.totalBooks,
  });

  final int id;
  final String code;
  final String status;
  final String createdAt;
  final int totalBooks;
}
