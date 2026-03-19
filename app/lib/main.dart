import 'package:flutter/widgets.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';

import 'app.dart';

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();

  const environment = String.fromEnvironment(
    'APP_ENV',
    defaultValue: 'development',
  );
  final fileName = environment == 'production' ? '.env.production' : '.env';

  await dotenv.load(fileName: fileName);

  runApp(const LatinasEditoresApp());
}
