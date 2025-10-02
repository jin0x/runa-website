<?php

namespace App\Fields\Partials;

use App\Enums\ThemeVariant;
use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class CardOptions extends Partial
{
    private static ?array $nextConfig = null;
    private ?array $config = null;

    /**
     * Configure the next instance with specific options.
     *
     * @param array $config Configuration array with 'colors' key
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
        $cardOptions = Builder::make('cardOptions');

        $cardOptions
            ->addSelect('card_color', [
                'label' => 'Card Color',
                'instructions' => 'Choose the color for the cards',
                'choices' => $this->getColorChoices(),
                'default_value' => ThemeVariant::CYAN,
                'wrapper' => [
                    'width' => '50',
                ],
            ]);

        return $cardOptions;
    }

    /**
     * Get available color choices based on configuration.
     */
    private function getColorChoices(): array
    {
        $cardColors = [
            ThemeVariant::PURPLE => 'Purple',
            ThemeVariant::CYAN => 'Cyan',
            ThemeVariant::YELLOW => 'Yellow',
            ThemeVariant::GREEN => 'Green',
        ];

        if (!$this->config || !isset($this->config['colors'])) {
            return $cardColors; // Default: all card colors
        }

        return array_intersect_key($cardColors, array_flip($this->config['colors']));
    }
}