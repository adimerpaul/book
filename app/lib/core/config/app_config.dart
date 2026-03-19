import 'package:flutter_dotenv/flutter_dotenv.dart';

class AppConfig {
  AppConfig._();

  static String get appName =>
      dotenv.env['APP_NAME']?.trim().isNotEmpty == true
          ? dotenv.env['APP_NAME']!.trim()
          : 'Latinas Editores';

  static String get apiBaseUrl =>
      dotenv.env['API_BASE_URL']?.trim().isNotEmpty == true
          ? dotenv.env['API_BASE_URL']!.trim()
          : 'http://10.0.2.2:8000/api';
}
