<?php

return [
    'basePath' => getenv('BASE_PATH') ?: $_SERVER['DOCUMENT_ROOT'],
    'titleizeIgnore' => ['at', 'by', 'for', 'in', 'of', 'on', 'out', 'to', 'the'],
];
