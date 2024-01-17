<?php

/**
 * Collates the required statistical information into fields with titles.
 * @param array $file
 * @return array
 */
function get_statistics(array $file): array {
    # Destructure file array into its elements
    ['records' => $records, 'header' => $header] = $file;

    # Pick out marks from valid records
    $valid_marks = get_all_valid_marks($records);

    $rounded_mean = round(mmmr($valid_marks), 2);
    $mode = mmmr($valid_marks,'mode');
    $range = mmmr($valid_marks, 'range');

    # Count header errors;
    # 1 invalid field counts as 1 error, regardless of how many errors the field has
    $header_error_count = count(get_invalid_fields($header));

    # Count invalid records;
    # 1 invalid record counts as 1 error, regardless of how many invalid fields it has
    $valid_record_count = count($valid_marks);                      # There is 1 valid mark for 1 valid record
    $invalid_record_count = count($records) - $valid_record_count;  # A record is either valid or invalid

    # Create array of fields
    return array_merge(
        [
            build_field('Number of students', $valid_record_count),
            build_field('Mean', $rounded_mean),
            build_field('Mode', $mode),
            build_field('Range', $range),
            build_field('Number of invalid header fields', $header_error_count),
            build_field('Number of invalid student records', $invalid_record_count)
        ],
        get_classifications($valid_marks)
    );
}

/**
 * Counts the number of distinctions, merits, passes and fails in the array of grades
 * and returns them as fields.
 * @param array $valid_marks Array of valid marks
 * @return int[] Array of classification counts
 */
function get_classifications(array $valid_marks): array {
    $classifications = [
        'distinction' => build_field('Distinctions', 0),
        'merit' => build_field('Merits', 0),
        'pass' => build_field('Passes', 0),
        'fail' => build_field('Fails', 0)
    ];

    # Determine the classification of each mark
    foreach ($valid_marks as $mark) {
        switch (true) {
            case $mark >= 70:
                $classification = 'distinction';
                break;
            case $mark >= 60:
                $classification = 'merit';
                break;
            case $mark >= 40:
                $classification = 'pass';
                break;
            default:
                $classification = 'fail';
        }

        # Increment the appropriate classification count
        $classifications[$classification]['content']++;
    }

    return $classifications;
}

/**
 * Extracts all marks from an array of student records where the record has no errors.
 * @param array $records
 * @return array Array of marks
 */
function get_all_valid_marks(array $records): array {
    # Select only those records that have no errors
    $valid_records = get_valid_records($records);

    # Extract the mark from the records
    return array_map(
        function (array $record): string { return $record['mark']['content']; },
        $valid_records
    );
}

/**
 * Picks out valid records.
 * @param array $records
 * @return array Array of valid records
 */
function get_valid_records(array $records): array {
    return array_filter(
        $records,
        function (array $record): bool {
            # Record valid if it has no invalid fields
            return empty(get_invalid_fields($record));
        }
    );
}

/**
 * Picks out the invalid fields of an entity with an 'errors' component.
 * @param array $entity A student record or a header
 * @return array Array of invalid fields
 */
function get_invalid_fields(array $entity): array {
    return array_filter(
        $entity,
        function (array $field): bool {
            return !empty($field['errors']);
        }
    );
}

?>
