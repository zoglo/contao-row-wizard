<?php

declare(strict_types=1);

namespace Zoglo\SimpleColumnWizardBundle\Widget;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\StringUtil;
use Contao\System;
use Contao\Widget;

class SimpleColumnWizard extends Widget
{
    protected $blnSubmitInput = true;

    protected $strTemplate = 'be_widget_scw';

    protected array $arrColumnFields = [];

    private int|null $min = null;

    private int|null $max = null;

    private bool $sortable = true;

    private array $actions = ['copy', 'delete'];

    private bool $reverseSortable;

    public function __construct($arrAttributes = null)
    {
        parent::__construct($arrAttributes);

        $this->preserveTags = true;
        $this->decodeEntities = true;
        $this->reverseSortable = \in_array(version_compare(ContaoCoreBundle::getVersion(), '5.3.999', '<'), [0, false, null], true);

        foreach ($this->arrOptions as $arrOption) {
            $this->arrColumnFields[] = $arrOption['value'];
        }
    }

    /**
     * Add specific attributes.
     */
    public function __set($strKey, $varValue): void
    {
        switch ($strKey) {
            case 'mandatory':
                if ($varValue) {
                    $this->arrAttributes['required'] = 'required';
                } else {
                    unset($this->arrAttributes['required']);
                }
                parent::__set($strKey, $varValue);
                break;

            case 'size':
                if ($this->multiple) {
                    $this->arrAttributes['size'] = $varValue;
                }
                break;

            case 'multiple':
                if ($varValue) {
                    $this->arrAttributes['multiple'] = 'multiple';
                }
                break;

            case 'options':
                $this->arrOptions = StringUtil::deserialize($varValue);
                break;

            case 'columnFields':
                $this->arrColumnFields = StringUtil::deserialize($varValue);
                break;

            case 'maxlength':
                if ($varValue > 0) {
                    $this->arrAttributes['maxlength'] = $varValue;
                }
                break;

            case 'min':
                $this->min = $varValue ?? null;
                break;

            case 'max':
                $this->max = $varValue ?? null;
                break;

            case 'sortable':
                $this->sortable = $varValue ?? false;
                break;

            case 'actions':
                if (is_array($varValue)) {
                    $this->actions = $varValue;
                }
                break;

            default:
                parent::__set($strKey, $varValue);
                break;
        }
    }

    public function validate(): void
    {
        $varValue = $this->getPost($this->strName);

        if ($this->hasErrors()) {
            $this->class = 'error';
        }

        $this->varValue = $varValue;
    }

    /**
     * @return string
     */
    public function generate()
    {
        // Make sure there is at least an empty array
        if (!\is_array($this->varValue) || $this->varValue === []) {
            $this->varValue = [['']];
        }

        // Populate the rows if the initial count has not been reached
        if ($this->min !== null) {
            $rowCount = count($this->varValue);

            while ($rowCount < $this->min) {
                $this->varValue[] = [''];
                $rowCount++;
            }
        }

        $labels = $rows = [];

        for ($i = 0, $c = \count($this->varValue); $i < $c; ++$i) {
            $columns = [];
            $labels = [];

            foreach ($this->arrColumnFields as $key => $options) {
                $widget = $this->prepareWidget($key, $options, $i);

                if (null !== $widget) {
                    if ('be_widget' === $widget->template) {
                        $labels[] = $widget->label ?? '';
                        $widget->label = null;
                        $widget->template = $this->strTemplate;
                    } else {
                        $labels[] = '';
                    }

                    $columns[] = $widget->parse();
                }
            }

            $rows[$i] = [
                'columns' => $columns,
                'controls' => [
                    'enable' => $this->varValue[$i]['enable'] ?? false,
                    'edit' => ($this->varValue[$i]['id'] ?? 0) > 0,
                ],
            ];
        }

        return System::getContainer()->get('twig')->render('@Contao/widget/simple_column_wizard.html.twig', [
            'id' => $this->strId,
            'labels' => $labels,
            'rows' => $rows,
            'min_rows' => $this->min,
            'max_rows' => $this->max,
            'sortable' => $this->sortable,
            'reverseSortable' => $this->reverseSortable,
            'actions' => $this->actions,
        ]);
    }

    private function prepareWidget(string $type, array $options, int $increment): Widget|null
    {
        if (
            !isset($options['inputType'])
            || !class_exists($widgetClass = $GLOBALS['BE_FFL'][$options['inputType']])
        ) {
            return null;
        }

        $data = $widgetClass::getAttributesFromDca($options, $type);

        $data['name'] = $this->strId . '[' . $increment . '][' . $data['name'] . ']';

        if (\in_array($data['type'] ?? null, ['checkbox', 'label'])) {
            $data['id'] = $data['name'];
        } else {
            $data['id'] .= '_' . $increment;
        }

        if (isset($this->varValue[$increment][$type])) {
            $data['value'] = $this->varValue[$increment][$type];
        }

        $widget = new $widgetClass($data);

        /*$blnFileTree = false;

        // Create custom FileTree Picker
        if ('fileTree' === $options['inputType'])
        {
            $strFilePicker = $objWidget->parse();

            $blnFileTree = true;
        }

        $strFields .= vsprintf('<td>%s</td>', [
            $blnFileTree ? $strFilePicker : $objWidget->parse(),
        ]);*/

        return $widget;
    }
}
