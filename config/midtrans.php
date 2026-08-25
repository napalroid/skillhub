<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'qris_acquirer' => env('MIDTRANS_QRIS_ACQUIRER', 'gopay'),
    'notification_url' => env('MIDTRANS_NOTIFICATION_URL', rtrim((string) env('APP_URL'), '/').'/midtrans/notification'),
    'ca_bundle' => env('MIDTRANS_CA_BUNDLE', storage_path('certificates/cacert.pem')),
];
