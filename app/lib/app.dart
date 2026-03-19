import 'package:flutter/material.dart';

import 'core/theme/app_theme.dart';
import 'presentation/views/main_shell_page.dart';

class LatinasEditoresApp extends StatelessWidget {
  const LatinasEditoresApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Latinas Editores',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.build(),
      home: const MainShellPage(),
    );
  }
}
