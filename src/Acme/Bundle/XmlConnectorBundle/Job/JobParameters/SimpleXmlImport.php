<?php

namespace Acme\Bundle\XmlConnectorBundle\Job\JobParameters;

use Akeneo\Component\Batch\Job\JobInterface;
use Akeneo\Component\Batch\Job\JobParameters\ConstraintCollectionProviderInterface;
use Akeneo\Component\Batch\Job\JobParameters\DefaultValuesProviderInterface;
use Akeneo\Component\Localization\Localizer\LocalizerInterface;
use Pim\Bundle\ImportExportBundle\JobParameters\FormConfigurationProviderInterface;
use Pim\Component\Catalog\Validator\Constraints\FileExtension;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class SimpleXmlImport implements
    DefaultValuesProviderInterface,
    ConstraintCollectionProviderInterface,
    FormConfigurationProviderInterface
{
    /** @var ConstraintCollectionProviderInterface */
    protected $constraintCollectionProvider;

    /** @var DefaultValuesProviderInterface */
    protected $defaultValuesProvider;

    /** @var FormConfigurationProviderInterface */
    protected $formConfiguration;

    /** @var array */
    protected $decimalSeparators;

    /** @var array */
    protected $dateFormats;

    /**
     * @param array $decimalSeparators
     * @param array $dateFormats
     */
    public function __construct(array $decimalSeparators, array $dateFormats)
    {
        $this->decimalSeparators = $decimalSeparators;
        $this->dateFormats = $dateFormats;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultValues()
    {
        return [
            'filePath'           => null,
            'uploadAllowed'      => true,
            'dateFormat'         => LocalizerInterface::DEFAULT_DATE_FORMAT,
            'decimalSeparator'   => LocalizerInterface::DEFAULT_DECIMAL_SEPARATOR,
            'enabled'            => true,
            'categoriesColumn'   => 'categories',
            'familyColumn'       => 'family',
            'groupsColumn'       => 'groups',
            'enabledComparison'  => true,
            'realTimeVersioning' => true,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getConstraintCollection()
    {
        return new Collection(
            [
                'fields' => [
                    'filePath' => [
                        new NotBlank(['groups' => ['Execution', 'UploadExecution']]),
                        new FileExtension(
                            [
                                'allowedExtensions' => ['xml', 'zip'],
                                'groups'            => ['Execution', 'UploadExecution']
                            ]
                        )
                    ],
                    'uploadAllowed' => [
                        new Type('bool'),
                        new IsTrue(['groups' => 'UploadExecution']),
                    ],
                    'decimalSeparator' => [
                        new NotBlank()
                    ],
                    'dateFormat' => [
                        new NotBlank()
                    ],
                    'enabled' => [
                        new Type('bool')
                    ],
                    'categoriesColumn' => [
                        new NotBlank()
                    ],
                    'familyColumn' => [
                        new NotBlank()
                    ],
                    'groupsColumn' => [
                        new NotBlank()
                    ],
                    'enabledComparison' => [
                        new Type('bool')
                    ],
                    'realTimeVersioning' => [
                        new Type('bool')
                    ],
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFormConfiguration()
    {
        return [
            'filePath' => [
                'options' => [
                    'label' => 'pim_connector.import.filePath.label',
                    'help'  => 'pim_connector.import.filePath.help'
                ]
            ],
            'decimalSeparator' => [
                'type'    => 'choice',
                'options' => [
                    'choices'  => $this->decimalSeparators,
                    'required' => true,
                    'select2'  => true,
                    'label'    => 'pim_connector.export.decimalSeparator.label',
                    'help'     => 'pim_connector.export.decimalSeparator.help'
                ]
            ],
            'dateFormat' => [
                'type'    => 'choice',
                'options' => [
                    'choices'  => $this->dateFormats,
                    'required' => true,
                    'select2'  => true,
                    'label'    => 'pim_connector.export.dateFormat.label',
                    'help'     => 'pim_connector.export.dateFormat.help',
                ]
            ],
            'enabled' => [
                'type'    => 'switch',
                'options' => [
                    'label' => 'pim_connector.import.enabled.label',
                    'help'  => 'pim_connector.import.enabled.help'
                ]
            ],
            'categoriesColumn' => [
                'options' => [
                    'label' => 'pim_connector.import.categoriesColumn.label',
                    'help'  => 'pim_connector.import.categoriesColumn.help'
                ]
            ],
            'familyColumn' => [
                'options' => [
                    'label' => 'pim_connector.import.familyColumn.label',
                    'help'  => 'pim_connector.import.familyColumn.help'
                ]
            ],
            'groupsColumn' => [
                'options' => [
                    'label' => 'pim_connector.import.groupsColumn.label',
                    'help'  => 'pim_connector.import.groupsColumn.help'
                ]
            ],
            'enabledComparison' => [
                'type'    => 'switch',
                'options' => [
                    'label' => 'pim_connector.import.enabledComparison.label',
                    'help'  => 'pim_connector.import.enabledComparison.help'
                ]
            ],
            'realTimeVersioning' => [
                'type'    => 'switch',
                'options' => [
                    'label' => 'pim_connector.import.realTimeVersioning.label',
                    'help'  => 'pim_connector.import.realTimeVersioning.help'
                ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supports(JobInterface $job)
    {
        return $job->getName() === 'xml_product_import';
    }
}
