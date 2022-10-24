![tests](https://github.com/jeyroik/extas-repositories-get/workflows/PHP%20Composer/badge.svg?branch=master&event=push)
![codecov.io](https://codecov.io/gh/jeyroik/extas-repositories-get/coverage.svg?branch=master)
<a href="https://github.com/phpstan/phpstan"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a> 
<a href="https://codeclimate.com/github/jeyroik/extas-repositories-get/maintainability"><img src="https://api.codeclimate.com/v1/badges/62260857ba00ea43a0dd/maintainability" /></a>

# Описание

DEPRECATED

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