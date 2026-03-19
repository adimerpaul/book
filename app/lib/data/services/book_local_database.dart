import 'package:path/path.dart' as path;
import 'package:sqflite/sqflite.dart';

import '../models/book.dart';
import '../models/order.dart';

class BookLocalDatabase {
  BookLocalDatabase._();

  static final BookLocalDatabase instance = BookLocalDatabase._();

  Database? _database;

  Future<Database> get database async {
    if (_database != null) {
      return _database!;
    }

    final databasePath = await getDatabasesPath();
    final filePath = path.join(databasePath, 'latinas_editores.db');

    _database = await openDatabase(
      filePath,
      version: 2,
      onCreate: (db, version) async => _createTables(db),
      onUpgrade: (db, oldVersion, newVersion) async {
        await db.execute('DROP TABLE IF EXISTS books');
        await db.execute('DROP TABLE IF EXISTS orders');
        await _createTables(db);
      },
    );

    return _database!;
  }

  Future<List<Book>> getBooks() async {
    final db = await database;
    final rows = await db.query('books', orderBy: 'title COLLATE NOCASE ASC');
    return rows.map(Book.fromMap).toList();
  }

  Future<void> replaceBooks(List<Book> books) async {
    final db = await database;
    await db.transaction((txn) async {
      await txn.delete('books');
      for (final book in books) {
        await txn.insert('books', book.toMap());
      }
    });
  }

  Future<void> updateFavorite(int id, bool isFavorite) async {
    final db = await database;
    await db.update(
      'books',
      {'is_favorite': isFavorite ? 1 : 0},
      where: 'id = ?',
      whereArgs: [id],
    );
  }

  Future<List<OrderItem>> getOrders() async {
    final db = await database;
    final rows = await db.query('orders', orderBy: 'id DESC');
    return rows.map(OrderItem.fromMap).toList();
  }

  Future<void> _createTables(Database db) async {
    await db.execute('''
      CREATE TABLE books(
        id INTEGER PRIMARY KEY,
        slug TEXT NOT NULL,
        title TEXT NOT NULL,
        author TEXT NOT NULL,
        summary TEXT NOT NULL,
        category TEXT NOT NULL,
        subcategory TEXT NOT NULL,
        publisher TEXT NOT NULL,
        pages INTEGER NOT NULL,
        price REAL,
        published_at TEXT NOT NULL,
        cover_url TEXT,
        gallery TEXT NOT NULL,
        is_favorite INTEGER NOT NULL DEFAULT 0
      )
    ''');

    await db.execute('''
      CREATE TABLE orders(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        code TEXT NOT NULL,
        status TEXT NOT NULL,
        created_at TEXT NOT NULL,
        total_books INTEGER NOT NULL DEFAULT 0
      )
    ''');
  }
}
