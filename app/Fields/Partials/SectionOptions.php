<?php

namespace App\Fields\Partials;

use App\Enums\SectionSize;
use App\Enums\ThemeVariant;
use App\Enums\ArchPosition;
use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class SectionOptions extends Partial
{
    private static ?array $nextConfig = null;
    private ?array $config = null;

    /**
     * Configure the next instance with specific options.
     *
     * @param array $config Configuration array with 'themes' and/or 'sizes' keys
     * @return string The class name for use with addPartial()
     */
    public static function withConfig(array $config): string
    {
        self::$nextConfig = $config;
        return self::class;
    }

    /**
     * Override make to inject configuration.
     */
    public static function make($composer): self
    {
        $instance = new self($composer);
        if (self::$nextConfig) {
            $instance->config = self::$nextConfig;
            self::$nextConfig = null; // Reset for next use
        }
        return $instance;
    }

    /**
     * The partial field group.
     *
     * @return \StoutLogic\AcfBuilder\FieldsBuilder
     */
    public function fields()
    {
        $sectionOptions = Builder::make('sectionOptions');

        $sectionOptions
            ->addSelect('section_size', [
                'label' => 'Section Size',
                'instructions' => 'Choose the vertical padding for this section',
                'choices' => $this->getSizeChoices(),
                'default_value' => SectionSize::MEDIUM->value,
                'wrapper' => [
                    'width' => '50',
                ],
            ])
            ->addSelect('theme', [
                'label' => 'Section Theme',
                'instructions' => 'Choose the color theme for this section',
                'choices' => $this->getThemeChoices(),
                'default_value' => ThemeVariant::LIGHT,
                'wrapper' => [
                    'width' => '50',
                ],
            ])
            ->addSelect('arch_position', [
                'label' => 'Arch Divider',
                'instructions' => 'Add an arch divider that extends the section background color',
                'choices' => [
                    ArchPosition::NONE->value => 'None',
                    ArchPosition::OUTER->value => 'Outer (positioned at top)',
                    ArchPosition::INNER->value => 'Inner (positioned at top)',
                ],
                'default_value' => ArchPosition::NONE->value,
                'wrapper' => [
                    'width' => '50',
                ],
            ]);

        return $sectionOptions;
    }

    /**
     * Get available size choices based on configuration.
     */
    private function getSizeChoices(): array
    {
        $allSizes = [
            SectionSize::NONE->value => 'None (No Padding)',
            SectionSize::XSMALL->value => 'Extra Small',
            SectionSize::SMALL->value => 'Small',
            SectionSize::MEDIUM->value => 'Medium (Default)',
            SectionSize::LARGE->value => 'Large',
            SectionSize::XLARGE->value => 'Extra Large',
        ];

        if (!$this->config || !isset($this->config['sizes'])) {
            return $allSizes; // Default: all sizes
        }

        return array_intersect_key($allSizes, array_flip($this->config['sizes']));
    }

    /**
     * Get available theme choices based on configuration.
     */
    private function getThemeChoices(): array
    {
        $allThemes = [
            ThemeVariant::LIGHT => 'Light',
            ThemeVariant::DARK => 'Dark',
            ThemeVariant::GREEN => 'Green',
            ThemeVariant::PURPLE => 'Purple',
            ThemeVariant::GRADIENT => 'Gradient',
            ThemeVariant::CYAN => 'Cyan',
            ThemeVariant::YELLOW => 'Yellow',
        ];

        if (!$this->config || !isset($this->config['themes'])) {
            return $allThemes; // Default: all themes
        }

        return array_intersect_key($allThemes, array_flip($this->config['themes']));
    }
}
