<?php

/**
 * Parses and validates all files in a folder.
 * @param string $path_to_folder Path to directory with the files to process
 * @return array Array of validated files
 */
function parse_files_in_folder(string $path_to_folder): array {
    $files = get_files_in_folder($path_to_folder);

    return array_map('parse_file', $files);
}

/**
 * Returns the paths to all files in the specified folder.
 * If the folder does not exist or cannot be opened it returns an empty array.
 * @param string $path_to_folder Path to the folder
 * @return string[] An array of file paths
 */
function get_files_in_folder(string $path_to_folder): array {
    $files = [];

    # Check if supplied string is the path to a folder
    if (!is_dir($path_to_folder)) {
        return $files;
    }

    $handle = opendir($path_to_folder);

    # Check if folder was opened successfully
    if (!$handle) {
        return $files;
    }

    # Collect file paths
    while ($file_name = readdir($handle)) {
        $path_to_file = "$path_to_folder/$file_name";
        if (is_file($path_to_file)) {
            $files[] = $path_to_file;
        }
    }

    closedir($handle);

    return $files;
}

/**
 * Parses and validates the contents of a file. Records any file-level errors.
 * @param string $path_to_file Path to file to process
 * @return array An array containing the file name, validated header fields,
 * student records and any file-level error
 */
function parse_file(string $path_to_file): array {

    # Check if supplied string is the path to a file
    if (!is_file($path_to_file)) {
        return build_file($path_to_file, null, null, 'NOT_FOUND');
    }

    # Check if file is a text file
    if (pathinfo($path_to_file, PATHINFO_EXTENSION) !== 'txt') {
        return build_file($path_to_file, null, null, 'NOT_TXT');
    }

    $handle = fopen($path_to_file, 'r');

    # Check if file was opened successfully
    if (!$handle) {
        return build_file($path_to_file, null, null, 'UNOPENABLE');
    }

    # Read the raw data into an array line by line
    $lines = [];
    while (!feof($handle)) {
        # Discard whitespace
        $line = trim(fgets($handle));

        # Do not include lines which are only whitespace
        if ($line) {
            $lines[] = $line;
        }
    }

    fclose($handle);

    # Files with no entries are not processed further
    if (empty($lines)) {
        return build_file($path_to_file, null, null, 'EMPTY');
    }

    # Processing the first line as header
    $header_fields = parse_header($lines[0]);
    # Processing the remaining lines as the body of the data
    $records = parse_records(array_slice($lines, 1));

    return build_file($path_to_file, $header_fields, $records);
}

?>
