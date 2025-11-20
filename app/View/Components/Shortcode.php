<?php

namespace App\View\Components;

use Roots\Acorn\View\Component;

class Shortcode extends Component {

    /**
     * The shortcode to be rendered.
     *
     * @var string
     */
    public string $shortcode;

    /**
     * The shortcode attributes (renamed to avoid conflict).
     *
     * @var mixed
     */
    public $shortcodeAttributes;

    /**
     * The wrapper class for the shortcode.
     *
     * @var string|null
     */
    public ?string $wrapper_class;

    /**
     * Create the component instance.
     *
     * @param string      $shortcode
     * @param array       $shortcodeAttributes
     * @param string|null $wrapper_class
     */
    public function __construct(
        string $shortcode,
        $shortcodeAttributes = [],
        string $wrapper_class = null
    ) {
        $this->shortcode           = $shortcode;
        $this->shortcodeAttributes = $shortcodeAttributes;
        $this->wrapper_class       = $wrapper_class;
    }

    /**
     * Render the shortcode with its attributes.
     *
     * @return string
     */
    public function render(): string {
        // Convert the shortcode attributes array to a string
        $attributes_string = implode( ' ', array_map(
            static function ( $value, $key ) {
                return $key . '="' . esc_attr( $value ) . '"';
            },
            $this->shortcodeAttributes,
            array_keys( $this->shortcodeAttributes )
        ) );

        // Render the shortcode
        $shortcode_output = do_shortcode( '[' . $this->shortcode . ' ' . $attributes_string . ']' );

        // Return the wrapped output if wrapper class is set
        if ( $this->wrapper_class ) {
            return '<div class="' . esc_attr( $this->wrapper_class ) . '">' . $shortcode_output . '</div>';
        }

        return $shortcode_output;
    }
}
