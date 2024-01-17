<?php

/**
 * Basic validator logic underlying the individual validator functions.
 * @param bool $valid A boolean expression that has to evaluate to true for the field to be valid
 * @param string $error_key A key in the VALIDATION_ERRORS constant
 * @return string[] An array of validation errors
 */
function validate(bool $valid, string $error_key): array {
    return $valid
        ? []
        : [$error_key];
}

/**
 * Checks if module code is of the right format and generates appropriate error messages otherwise.
 * @param string $module_code
 * @return string[]
 */
function module_code_format_validator(string $module_code): array {
    # Split module code into sections
    [$subject, $year, $term] = str_split_custom($module_code, MODULE_CODE_SECTION_LENGTHS);

    # Split year into sections representing the start and end of the academic year
    [$start, $end] = str_split_custom($year, [2, 2]);

    $year_valid = ctype_digit($year)                    # check if numeric
        && strlen($year) === MODULE_YEAR_CODE_LENGTH    # check if correct length
        && ($start + 1) % 100 == $end;                  # check if module ends the year after it begins

    # Generate errors
    return array_merge(
        validate(in_array($subject, SUBJECT_CODES), 'SUBJECT_CODE'),
        validate($year_valid, 'YEAR'),
        validate(in_array($term, TERM_CODES), 'TERM_CODE')
    );
}


/**
 * Checks if the field is empty and generates an error message otherwise.
 * @param string $field
 * @return string[]
 */
function non_empty_validator(string $field): array {
    return validate(
        trim($field) !== '',
        'EMPTY'
    );
}

/**
 * Checks if the field contains only printable characters and generates an error message otherwise.
 * @param string $field
 * @return string[]
 */
function printable_validator(string $field): array {
    return validate(
        ctype_print($field) || $field === '',       # a separate error is generated for empty fields
        'UNPRINTABLE'
    );
}

/**
 * Checks if the marking date is valid and in the correct format, and generates an error message otherwise.
 * @param string $date
 * @return string[]
 */
function marking_date_validator(string $date): array {
    # Parse the date according to the specified format
    $parsed_date = date_parse_from_format(DATE_FORMAT, $date);

    return validate(
        $parsed_date['error_count'] + $parsed_date['warning_count'] === 0,
        'MARKING_DATE'
    );
}

/**
 * Checks if student ID is valid and generates an error message otherwise.
 * @param string $student_id
 * @return string[]
 */
function student_id_validator(string $student_id): array {
    return validate(
        ctype_digit($student_id) && strlen($student_id) === STUDENT_ID_LENGTH,
        'STUDENT_ID'
    );
}

/**
 * Checks if module mark is valid and generates an error message otherwise.
 * @param string $mark
 * @return string[]
 */
function mark_validator(string $mark): array {
    return validate(
        ctype_digit($mark)
        && $mark >= MARK_RANGE['MIN']
        && $mark <= MARK_RANGE['MAX'],
        'MODULE_MARK'
    );
}

?>
