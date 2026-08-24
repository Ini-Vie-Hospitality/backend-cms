<?php

return [
    'text_provider' => env('CONCIERGE_TEXT_PROVIDER', 'deepseek'),
    'text_model' => env('CONCIERGE_DEEPSEEK_MODEL', 'deepseek-v4-flash'),
    'embedding_provider' => env('CONCIERGE_EMBEDDING_PROVIDER', 'ollama'),
    'embedding_model' => env('CONCIERGE_EMBEDDING_MODEL', 'bge-m3:567m'),
    'embedding_dimensions' => 1024,
    'similarity_threshold' => (float) env('CONCIERGE_SIMILARITY_THRESHOLD', 0.55),
    'result_limit' => 5,
];
