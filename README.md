> [!WARNING]
> Do not use this plugin in production

<h1 align="center">Contao Simple Column Wizard</h1>
<p align="center">
    <a href="https://github.com/zoglo/contao-simple-column-wizard"><img src="https://img.shields.io/github/v/release/zoglo/contao-simple-column-wizard" alt="github version"/></a>
    <a href="https://packagist.org/packages/zoglo/contao-simple-column-wizard"><img src="https://img.shields.io/packagist/dt/zoglo/contao-simple-column-wizard?color=f47c00" alt="amount of downloads"/></a>
    <a href="https://packagist.org/packages/zoglo/contao-simple-column-wizard"><img src="https://img.shields.io/packagist/dependency-v/zoglo/contao-simple-column-wizard/php?color=474A8A" alt="minimum php version"></a>
</p>

## Description

This bundle adds a widget that allows adding multiple rows, with each row containing multiple widgets arranged as columns. The data can be stored as a serialized array in the database.

## Installation

### Via composer

```
composer require zoglo/contao-simple-column-wizard
```

## Configuration

```php
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Doctrine\DBAL\Platforms\AbstractMySQLPlatform;
use Zoglo\ContaoSimpleColumnWizard\EventListener\ColumnWizardListener;

$GLOBALS['TL_DCA']['tl_content']['fields']['columnWizardOne'] = [
    'label' => ['columnWizardOne', 'And some random description'], // Or a &$GLOBALS['TL_LANG'] pointer
    'inputType' => 'simpleColumnWizard',
    'eval' => [
        'tl_class' => 'clr',
        //'dragAndDrop' => true, // Not working yet
        'columnFields' => [
            'type' => [
                'label' => 'Type', // Or a &$GLOBALS['TL_LANG'] pointer
                'inputType' => 'select',
                'options' => ['foo', 'bar', 'baz', 'quux'],
                'eval' => [
                    'includeBlankOption' => true,
                    'chosen' => true,
                ],
            ],
            'checkbox' => [
                'label' => 'Checkbox', // Or a &$GLOBALS['TL_LANG'] pointer
                'inputType' => 'checkbox',
            ],
            'textarea' => [
                'label' => 'Textarea', // Or a &$GLOBALS['TL_LANG'] pointer
                'inputType' => 'textarea',
            ],
            'text' => [
                'label' => 'Text', // Or a &$GLOBALS['TL_LANG'] pointer
                'inputType' => 'text',
            ],
        ],
    ],
    'save_callback' => [
        // A callback to use when you want to reset the row based on the first value being empty
        [ColumnWizardListener::class, 'clearEmptyRowWithEmptyFirstValue'],
    ],
    'sql' => [
        'type' => 'blob',
        'length' => AbstractMySQLPlatform::LENGTH_LIMIT_BLOB,
        'notnull' => false,
    ],
];

```

**Output**

![Rendered example of the simple column wizard based on the configuration](/docs/images/simpleColumnWizard.jpg)
