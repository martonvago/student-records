<?php

/**
 * Parses a field by validating it against the specified validator functions and adding applicable error messages.
 * The non_empty_validator is applied to all fields.
 * @param string $title
 * @param string $content
 * @param callable $validator Validator function with signature (string) -> string[]
 * @return array An array representing a validated field
 */
function parse_field(string $title, string $content, callable $validator): array {
    # All fields must contain a value
    $validators = ['non_empty_validator', $validator];

    # Call each validator function on the field to find errors and flatten errors into a 1D array
    $errors = array_flatmap(
        function (callable $validator) use ($content): array { return $validator($content); },
        $validators
    );

    return build_field($title, $content, $errors);
}

/**
 * Parses a text field by adding a title and applicable error messages.
 * @param string $title
 * @param string $field
 * @return array
 */
function parse_text_field(string $title, string $field): array {
    return parse_field($title, $field, 'printable_validator');
}

?>
