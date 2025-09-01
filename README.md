> [!WARNING]
> Do not use this plugin in production

<h1 align="center">Contao Row Wizard</h1>
<p align="center">
    <a href="https://github.com/zoglo/contao-row-wizard"><img src="https://img.shields.io/github/v/release/zoglo/contao-row-wizard" alt="github version"/></a>
    <a href="https://packagist.org/packages/zoglo/contao-row-wizard"><img src="https://img.shields.io/packagist/dt/zoglo/contao-row-wizard?color=f47c00" alt="amount of downloads"/></a>
    <a href="https://packagist.org/packages/zoglo/contao-row-wizard"><img src="https://img.shields.io/packagist/dependency-v/zoglo/contao-row-wizard/php?color=474A8A" alt="minimum php version"></a>
</p>

## Description

This bundle adds a widget that allows adding multiple rows, with each row containing multiple widgets arranged as columns. The data can be stored as a serialized array in the database.

## Installation

### Via composer

```
composer require zoglo/contao-row-wizard
```

## Configuration

```php
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Doctrine\DBAL\Platforms\AbstractMySQLPlatform;
use Zoglo\RowWizardBundle\EventListener\ColumnWizardListener;

$GLOBALS['TL_DCA']['tl_content']['fields']['columnWizardOne'] = [
    'label' => ['columnWizardOne', 'And some random description'], // Or a &$GLOBALS['TL_LANG'] pointer
    'inputType' => 'rowWizard',
    'fields' => [
        'type' => [
            'label' => ['Type'], // Or a &$GLOBALS['TL_LANG'] pointer
            'inputType' => 'select',
            'options' => ['foo', 'bar', 'baz', 'quux'],
            'eval' => [
                'includeBlankOption' => true,
                'chosen' => true,
            ],
        ],
        'checkbox' => [
            'label' => ['Checkbox'], // Or a &$GLOBALS['TL_LANG'] pointer
            'inputType' => 'checkbox',
        ],
        'textarea' => [
            'label' => ['Textarea'], // Or a &$GLOBALS['TL_LANG'] pointer
            'inputType' => 'textarea',
        ],
        'text' => [
            'label' => ['Text'], // Or a &$GLOBALS['TL_LANG'] pointer
            'inputType' => 'text',
        ],
    ],
    'eval' => [
        'tl_class' => 'clr',
        'actions' => [ // actions to be shown. Default: 'copy', 'delete' // 'edit' does not work yet
            'copy',
            'delete',
            //'enable', // Enable / Disable
        ],
        //'sortable' => false, // disables sorting the rows
        'min' => 2, // minimum amount of rows
        'max' => 5, // maximum amount of rows
    ],
    'sql' => [
        'type' => 'blob',
        'length' => AbstractMySQLPlatform::LENGTH_LIMIT_BLOB,
        'notnull' => false,
    ],
];

```

**Output**

![Rendered example of the row wizard based on the configuration](/docs/images/rowWizard.jpg)

## Examples

In some cases, you may not want to save any value if there is only one row and the first value is empty.
You can implement your own callback function for the save callback like this:

```php
#[AsCallback(table: 'tl_content', target: 'fields.columnWizardOne.save')]
class ContentTextSaveCallback
{
    public function __invoke($value, DataContainer $dc)
    {
        if ('' === $value) {
            return $value;
        }

        if (0 === \count($values = StringUtil::deserialize($value, true))) {
            return '';
        }

        // Do not reset if there is more than one row
        if (1 !== \count($values)) {
            return $value;
        }

        if (($values[0][array_key_first($values[0])] ?? '') === '') {
            return '';
        }

        return $value;
    }
}
```

## Known limitation

The following fields do not work:

- any eval `rte` (tinymce, ace)
- color picker
- filetree

If you need those working or any other special features, consider using a more advanced wizard such as [contao-multicolumnwizard-bundle](https://github.com/menatwork/contao-multicolumnwizard-bundle) instead.
