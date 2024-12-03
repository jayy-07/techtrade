<?php
define('ENVIRONMENT', 'local');

$config = [
    'local' => [
        'base_url' => 'http://localhost/techtrade',
        'paystack_secret_key' => 'sk_test_105e7a4c7c4684f6c903be9310f5b5df56375e7d',
        'paystack_public_key' => 'pk_test_ed5890a96470b5ae8e82f8a6531f88d2f9c8c4b3'
    ],
    'production' => [
        'base_url' => 'http://169.239.251.102:4442/~joel.kodji',
        'paystack_secret_key' => 'sk_test_105e7a4c7c4684f6c903be9310f5b5df56375e7d',
        'paystack_public_key' => 'pk_test_ed5890a96470b5ae8e82f8a6531f88d2f9c8c4b3'
    ]
];

define('BASE_URL', $config[ENVIRONMENT]['base_url']);
define('PAYSTACK_SECRET_KEY', $config[ENVIRONMENT]['paystack_secret_key']);
define('PAYSTACK_PUBLIC_KEY', $config[ENVIRONMENT]['paystack_public_key']); 