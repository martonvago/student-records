<?php

# Constants appearing in validation rules
define('SUBJECT_CODES', ['PP', 'P1', 'DT']);
define('TERM_CODES', ['T1', 'T2', 'T3']);
define('MODULE_CODE_LENGTH', 8);
define('MODULE_CODE_SECTION_LENGTHS', [2, 4, 2]);
define('HEADER_FIELD_NUMBER', 4);
define('MODULE_YEAR_CODE_LENGTH', 4);
define('STUDENT_RECORD_FIELD_NUMBER', 2);
define('STUDENT_ID_LENGTH', 8);
define('MARK_RANGE', ['MIN' => 0, 'MAX' => 100]);
define('DATE_FORMAT', 'd/m/Y');


# Field-level errors with error messages
define('VALIDATION_ERRORS', [
    'EMPTY' => 'No value provided',
    'UNPRINTABLE' => 'Unprintable characters included',
    'SUBJECT_CODE' => 'Invalid subject code',
    'YEAR' => 'Invalid year',
    'TERM_CODE' => 'Invalid term code',
    'MARKING_DATE' => 'Invalid marking date',
    'STUDENT_ID' => 'Invalid student ID',
    'MODULE_MARK' => 'Invalid module mark'
]);

# File-level errors with error messages
define('FILE_ERRORS', [
    'NOT_FOUND' => 'File could not be found',
    'UNOPENABLE' => 'File could not be opened',
    'EMPTY' => 'File is empty',
    'NOT_TXT' => 'File is not a .txt file'
]);

?>
