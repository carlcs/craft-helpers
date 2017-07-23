<?php

return [
    'basePath' => getenv('BASE_PATH') ?: $_SERVER['DOCUMENT_ROOT'],
    'highlightFormat' => '<mark>\1</mark>',
    'titleizeIgnore' => ['at', 'by', 'for', 'in', 'of', 'on', 'out', 'to', 'the'],
];
