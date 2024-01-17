<?php

/**
 * Returns the HTML for a file, whether valid or invalid.
 * @param array $file
 * @return string
 */
function display_file(array $file): string {
    $name = $file['name'];

    # Generate appropriate HTML for the body of a file according to whether it's valid
    $file_body = $file['error']
        ? display_invalid_file_body($file)
        : display_valid_file_body($file);

    return "<article class='file'>
            <h2>$name</h2>
            $file_body
        </article>";
}

/**
 * Returns the HTML for the body of an invalid file.
 * @param array $file
 * @return string
 */
function display_invalid_file_body(array $file): string {
    # Format file error
    return '<p class="error">' . FILE_ERRORS[$file['error']] . '</p>';
}

/**
 * Returns the HTML for the body of a valid file.
 * @param array $file
 * @return string
 */
function display_valid_file_body(array $file): string {
    ['header' => $header, 'records' => $records] = $file;

    # Format header
    $header_element = display_section(
        'File header',
        $header,
        'display_field'
    );

    # Format statistics
    $statistics = get_statistics($file);
    $statistics_element = display_section(
        'File statistics',
        $statistics,
        'display_field'
    );

    # Format all records
    $all_records_element = display_section(
        'All student records',
        $records,
        'display_record'
    );

    # Format valid records
    $valid_records = get_valid_records($records);
    $valid_records_element = display_section(
        'Valid student records',
        $valid_records,
        'display_record'
    );

    return $header_element .
            $statistics_element .
            $all_records_element .
            $valid_records_element;
}

/**
 * Returns the HTML for one of the main file sections.
 * @param string $title The title of the section
 * @param array $children The elements to be displayed in the section
 * @param callable $make_child_element A function returning the HTML for one child element
 * @return string
 */
function display_section(string $title, array $children, callable $make_child_element): string {
    $child_elements = join(
        array_map(
            $make_child_element,
            $children
        )
    );

    return "<section class='file-section'>
            <h3>$title</h3>
            $child_elements
        </section>";
}

/**
 * Returns the HTML for a student record.
 * @param array $record
 * @return string
 */
function display_record(array $record): string {
    ['student_id' => $student_id, 'mark' => $mark] = $record;
    return '<div class="student">' .
            display_field($student_id) .
            display_field($mark) .
        '</div>';
}

/**
 * Returns the HTML for a field with its title, content and any error messages.
 * @param array $field
 * @return string
 */
function display_field(array $field): string {
    ['title' => $title, 'content' => $content, 'errors' => $errors] = $field;

    # Include the content, even if empty
    $content_element = '<p>' . htmlentities($content) . '</p>';

    # Include the errors if any
    if ($errors) {
        # Get the error messages corresponding to the errors and join them into a list for display
        $error_messages = join(
            ', ',
            array_map(
                function (string $error_key): string {
                    return VALIDATION_ERRORS[$error_key];
                },
                $field['errors']
            )
        );
        $error_messages_element = "<p class='error'>ERROR: $error_messages</p>";
    } else {
        $error_messages_element = null;
    }

    return "<section class='field'>
            <h4>$title:</h4>
            $content_element
            $error_messages_element
        </section>";
}

?>
