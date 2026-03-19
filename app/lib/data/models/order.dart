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

  factory OrderItem.fromMap(Map<String, Object?> map) {
    return OrderItem(
      id: (map['id'] as num?)?.toInt() ?? 0,
      code: (map['code'] as String? ?? '').trim(),
      status: (map['status'] as String? ?? '').trim(),
      createdAt: (map['created_at'] as String? ?? '').trim(),
      totalBooks: (map['total_books'] as num?)?.toInt() ?? 0,
    );
  }
}
