<?php

namespace App\View\Components;

use App\Enums\FlexDirection;
use App\Enums\FlexGap;
use App\Enums\FlexJustify;
use App\Enums\FlexAlign;
use Roots\Acorn\View\Component;

class Flex extends Component {

    /**
     * The section additional classes.
     *
     * @var string|null
     */
    public ?string $classes;

    /**
     * The flex direction.
     *
     * @var FlexDirection
     */
    public FlexDirection $direction;

    /**
     * The flex gap size.
     *
     * @var FlexGap
     */
    public FlexGap $gapsize;

    /**
     * The flex justify content.
     *
     * @var FlexJustify|null
     */
    public ?FlexJustify $justify;

    /**
     * The flex align items.
     *
     * @var FlexAlign|null
     */
    public ?FlexAlign $align;

    /**
     * The flex wrap.
     *
     * @var string|null
     */
    public ?string $wrap;

    /**
     * The flex wrap options.
     *
     * @var array
     */
    public $wrapOptions = [
        'wrap' => 'flex-wrap',
        'nowrap' => 'flex-nowrap',
        'wrap-reverse' => 'flex-wrap-reverse',
    ];

    /**
     * Create the component instance.
     *
     * @param string|null $direction
     * @param string|null $gapsize
     * @param string|null $justify
     * @param string|null $align
     * @param string|null $wrap
     * @param string|null $classes
     */
    public function __construct(
        ?string $direction = null,
        ?string $gapsize = null,
        ?string $justify = null,
        ?string $align = null,
        ?string $wrap = null,
        ?string $classes = null,
    ) {
        // Set defaults - column direction and medium gap
        $this->direction = match ($direction) {
            'row' => FlexDirection::ROW,
            'row-reverse' => FlexDirection::ROW_REVERSE,
            'col', 'column' => FlexDirection::COLUMN,
            'col-reverse', 'column-reverse' => FlexDirection::COLUMN_REVERSE,
            default => FlexDirection::COLUMN,
        };

        $this->gapsize = match ($gapsize) {
            '2xs' => FlexGap::XXSMALL,
            'xs' => FlexGap::XSMALL,
            'sm' => FlexGap::SMALL,
            'md' => FlexGap::MEDIUM,
            'lg' => FlexGap::LARGE,
            'xl' => FlexGap::XLARGE,
            '2xl' => FlexGap::XXLARGE,
            '3xl' => FlexGap::XXXLARGE,
            '4xl' => FlexGap::XXXXLARGE,
            default => FlexGap::MEDIUM,
        };

        $this->justify = match ($justify) {
            'start' => FlexJustify::START,
            'end' => FlexJustify::END,
            'center' => FlexJustify::CENTER,
            'between' => FlexJustify::BETWEEN,
            'around' => FlexJustify::AROUND,
            'evenly' => FlexJustify::EVENLY,
            default => null,
        };

        $this->align = match ($align) {
            'start' => FlexAlign::START,
            'end' => FlexAlign::END,
            'center' => FlexAlign::CENTER,
            'baseline' => FlexAlign::BASELINE,
            'stretch' => FlexAlign::STRETCH,
            default => null,
        };

        $this->wrap = $wrap ? $this->wrapOptions[$wrap] ?? null : null;
        $this->classes = $classes;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render(): string|\Illuminate\View\View {
        return $this->view('components.flex');
    }
}