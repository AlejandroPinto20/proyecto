<?php

return [
    'url' => rtrim(env('OLLAMA_URL', 'http://127.0.0.1:11434'), '/'),
    'model' => env('OLLAMA_MODEL', 'gemma3:1b'),
    'timeout' => (int) env('OLLAMA_TIMEOUT', 45),
];
