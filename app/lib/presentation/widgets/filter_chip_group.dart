import 'package:flutter/material.dart';

class FilterChipGroup extends StatelessWidget {
  const FilterChipGroup({
    super.key,
    required this.title,
    required this.options,
    required this.selected,
    required this.onSelected,
  });

  final String title;
  final List<String> options;
  final String selected;
  final ValueChanged<String> onSelected;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          title,
          style: Theme.of(context).textTheme.titleMedium,
        ),
        const SizedBox(height: 10),
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: Row(
            children: options
                .map(
                  (option) => Padding(
                    padding: const EdgeInsets.only(right: 8),
                    child: ChoiceChip(
                      label: Text(option),
                      selected: selected == option,
                      onSelected: (_) => onSelected(option),
                    ),
                  ),
                )
                .toList(),
          ),
        ),
      ],
    );
  }
}
