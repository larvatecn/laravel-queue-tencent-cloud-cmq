# laravel-tencent-cloud-cmq-queue

This is a queue adapter for the QCloud CMQ

## Installation

```bash
composer require larva/laravel-tencent-cloud-cmq-queue
```

## for Laravel

This service provider must be registered.

```php
// config/app.php

'providers' => [
    '...',
    Larva\Queue\TencentCloudCMQ\CMQServiceProvider::class,
];
```

edit the config file: config/queue.php

add config

```php
        'cmq' => [
            'driver' => 'cmq',
            'secret_Id' => env('CMQ_SECRET_ID', 'your-secret_Id'),
            'secret_Key' => env('CMQ_SECRET_KEY', 'your-secret_Key'),
            'endpoint' => 'https://cmq-queue-bj.api.qcloud.com',
            'queue' => 'default'
        ],
```

change default to cmq

```php
    'default' => 'cmq'
```

## Use

see [Laravel wiki](https://laravel.com/docs/5.7/queues)
