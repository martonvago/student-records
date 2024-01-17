<?php

/**
 * Parses and validates the header line.
 * @param string $header_line The first line of a file to process
 * @return array An array of validated fields with content and errors
 */
function parse_header(string $header_line): array {
    # Split line into fields
    [$module_code, $module_name, $tutor_name, $date] = break_line_into_fields($header_line, HEADER_FIELD_NUMBER);

    # Validate fields individually
    return [
        'module_code' => parse_field(
            'Module Code',
            $module_code,
            'module_code_format_validator'
        ),
        'module_name' => parse_text_field(
            'Module Title',
            $module_name
        ),
        'tutor_name' => parse_text_field(
            'Tutor Name',
            $tutor_name
        ),
        'date_marked' => parse_field(
            'Date Marked',
            $date,
            'marking_date_validator'
        )
    ];
}

/**
 * Parses and validates all student records.
 * @param string[] $lines Array of lines, each representing a student ID - mark pair
 * @return array[] An array of validated student records
 */
function parse_records(array $lines): array {

    return array_map(
        function(string $line): array {
            # Split line into fields
            [$student_id, $mark] = break_line_into_fields($line, STUDENT_RECORD_FIELD_NUMBER);

            # Validate fields individually
            return [
                'student_id' => parse_field(
                    'Student ID',
                    $student_id,
                    'student_id_validator'
                ),
                'mark' => parse_field(
                    'Mark',
                    $mark,
                    'mark_validator'
                )
            ];
        },
        $lines
    );
}


/**
 * Breaks a line from a file into the specified number of fields.
 *  - If the line has fewer fields than specified, the array is padded out with empty strings. Determining exactly
 *    which fields are missing is the responsibility of field-specific validator functions further down the chain.
 *  - If the line has more fields than specified, these are dropped.
 * @param string $line
 * @param int $expected_field_number
 * @return string[]
 */
function break_line_into_fields(string $line, int $expected_field_number)
{
    # Split line into fields without whitespace
    $result_fields = array_map(
        'trim',
        explode(',', $line)
    );

    # Add empty strings for missing fields to enable the caller to destructure & validate the array safely
    $field_number = count($result_fields);
    if ($field_number < $expected_field_number) {
        $result_fields = array_merge(
            $result_fields,
            array_fill(0, $expected_field_number - $field_number, '')
        );
    }

    # Drop extra fields
    return array_slice($result_fields, 0, $expected_field_number);
}

?>
