<?php

namespace App\View\Components;

use Roots\Acorn\View\Component;
use Illuminate\View\View;

class Slider extends Component {

    /**
     * The slider type.
     *
     * @var string
     */
    public string $type;

    /**
     * The slider additional classes.
     *
     * @var string|null
     */
    public ?string $classes;

    /**
     * Check if slider has navigation
     *
     * @var bool
     */
    public bool $navigation;

    /**
     * Check if slider has pagination
     *
     * @var bool
     */
    public bool $pagination;

    /**
     * Check if slider is infinite
     *
     * @var bool
     */
    public bool $loop;

    /**
     * Autoplay Delay
     *
     * @var int
     */
    public int $autoplayDelay;

    /**
     * Space Between
     *
     * @var int
     */
    public int $spaceBetween;

    /**
     * Slides per view
     *
     * @var int
     */
    public int $slidesPerView;

    /**
     * Mobile Slides per view
     *
     * @var int
     */
    public int $mobileSlidesPerView;

    /**
     * Tablet Slides per view
     *
     * @var int
     */
    public int $tabletSlidesPerView;

    /**
     * Desktop Slides per view
     *
     * @var int
     */
    public int $desktopSlidesPerView;

    /**
     * The slider types.
     *
     * @var array
     */
    public array $types = [
        'primary' => 'slider',
    ];

    /**
     * Create the component instance.
     *
     * @param string $type
     * @param string $classes
     * @param bool   $navigation
     * @param bool   $pagination
     * @param bool   $loop
     * @param int    $spaceBetween
     * @param int    $autoplayDelay
     * @param int    $slidesPerView
     * @param int    $mobileSlidesPerView
     * @param int    $tabletSlidesPerView
     * @param int    $desktopSlidesPerView
     */
    public function __construct(
        string $type = 'default',
        string $classes = '',
        bool $navigation = false,
        bool $pagination = false,
        bool $loop = false,
        int $spaceBetween = 10,
        int $autoplayDelay = 4000,
        int $slidesPerView = 1,
        int $mobileSlidesPerView = 1,
        int $tabletSlidesPerView = 1,
        int $desktopSlidesPerView = 1,
    ) {
        $this->type                 = $this->types[$type] ?? $this->types['primary'];
        $this->classes              = $classes;
        $this->navigation           = $navigation;
        $this->pagination           = $pagination;
        $this->loop                 = $loop;
        $this->spaceBetween         = $spaceBetween;
        $this->autoplayDelay        = $autoplayDelay;
        $this->slidesPerView        = $slidesPerView;
        $this->mobileSlidesPerView  = $mobileSlidesPerView;
        $this->tabletSlidesPerView  = $tabletSlidesPerView;
        $this->desktopSlidesPerView = $desktopSlidesPerView;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return string|\Illuminate\View\View
     */
    public function render(): string|View {
        return $this->view('components.slider');
    }
}
