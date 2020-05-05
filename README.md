![tests](https://github.com/jeyroik/extas-samples/workflows/PHP%20Composer/badge.svg?branch=master&event=push)
![codecov.io](https://codecov.io/gh/jeyroik/extas-samples/coverage.svg?branch=master)
<a href="https://github.com/phpstan/phpstan"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a> 


# Описание

Расширение для получения репозитория.

# Использование

В `extas.json`:

```json
{
  "extensions": [
    {
      "class": "extas\\components\\extensions\\ExtensionRepositoryGet",
      "interface": "extas\\components\\interfaces\\IExtensionRepositoryGet",
      "subject": "*",
      "methods": ["myRepository"]
    } 
  ]
}
```
В конфиге контейнера:

```php
return [
    'myRepository' => my\repos\Repository::class
];
```

В коде

```php
/**
 * @var extas\interfaces\IItem $someItem
 */
echo get_class($someItem->myRepository()); // my\repos\Repository
```