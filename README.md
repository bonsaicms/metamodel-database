# Metamodel Database for Bonsai CMS

## Installation

You can install the package via composer:

```bash
composer require bonsaicms/metamodel-database
```

### Configuration

You can publish the config file with:

```bash
php artisan vendor:publish --tag="bonsaicms-metamodel-database-config"
```

This is the contents of the published config file:

```php
return [
    'bind' => [
        'schemaManager' => true,
    ],
    'observeModels' => [
        'entity' => true,
        'attribute' => true,
        'relationship' => true,
    ],
];
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
