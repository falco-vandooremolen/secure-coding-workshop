<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_headers' => ['*'],
    'supports_credentials' => true,
];
