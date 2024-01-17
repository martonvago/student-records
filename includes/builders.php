<?php
/**
 * Simple builder to create a file with the desired structure.
 * @param string $path_to_file
 * @param array|null $header
 * @param array|null $records
 * @param string|null $error
 * @return array
 */
function build_file(string $path_to_file, ?array $header, ?array $records, ?string $error = null): array {
    # Save only the filename and extension
    $name = pathinfo($path_to_file, PATHINFO_BASENAME);

    return [
        'name' => $name,
        'header' => $header,
        'records' => $records,
        'error' => $error
    ];
}

/**
 * Simple builder to create a field with the desired structure.
 * @param string $title
 * @param string $content
 * @param string[] $errors
 * @return array
 */
function build_field(string $title, string $content, array $errors = []): array {
    return [
        'content' => $content,
        'title' => $title,
        'errors' => $errors
    ];
}

?>