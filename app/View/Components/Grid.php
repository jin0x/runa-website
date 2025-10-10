<?php

namespace App\View\Components;

use Roots\Acorn\View\Component;

class Grid extends Component
{
    /**
     * The section additional classes.
     *
     * @var string|null
     */
    public ?string $classes;

    /**
     * The grid columns.
     *
     * @var string
     */
    public string $columns;

    /**
     * The grid gap size.
     *
     * @var string
     */
    public $gapsize;

    /**
     * The grid row gap size.
     *
     * @var string|null
     */
    public ?string $rowgapsize;

    /**
     * The grid column gap size.
     *
     * @var string|null
     */
    public ?string $colgapsize;

    /**
     * The grid layout.
     *
     * @var array
     */
    public $grid = [
        '2' => 'w-full grid lg:grid-cols-2',
        '3' => 'w-full grid md:grid-cols-2 lg:grid-cols-3',
        '4' => 'w-full grid md:grid-cols-2 lg:grid-cols-4',
        '5' => 'w-full grid lg:grid-cols-5',
        '6' => 'w-full grid lg:grid-cols-3 xl:grid-cols-6',
    ];

    /**
     * The grid gap.
     *
     * @var array
     */
    public $gap = [
        '2xs' => 'gap-1',
        'xs'  => 'gap-2',
        'sm'  => 'gap-3',
        'md'  => 'gap-4',
        'lg'  => 'gap-6',
        'xl'  => 'gap-8',
        '2xl' => 'gap-10',
    ];

    /**
     * The grid row gap.
     *
     * @var array
     */
    public $rowgap = [
        '2xs' => 'gap-y-1',
        'xs'  => 'gap-y-2',
        'sm'  => 'gap-y-3',
        'md'  => 'gap-y-4',
        'lg'  => 'gap-y-6',
        'xl'  => 'gap-y-8',
        '2xl' => 'gap-y-10',
        '3xl' => 'gap-y-12',
        '4xl' => 'gap-y-16',
    ];

    /**
     * The grid row gap.
     *
     * @var array
     */
    public $colgap = [
        '2xs' => 'gap-x-1',
        'xs'  => 'gap-x-2',
        'sm'  => 'gap-x-3',
        'md'  => 'gap-x-4',
        'lg'  => 'gap-x-6',
        'xl'  => 'gap-x-8',
        '2xl' => 'gap-x-10',
        '3xl' => 'gap-x-12',
        '4xl' => 'gap-x-16',
    ];

    /**
     * Create the component instance.
     *
     * @param string      $columns
     * @param string      $gapsize
     * @param string|null $rowgapsize
     * @param string|null $colgapsize
     * @param string|null $classes
     */
    public function __construct(
        string $columns = '2',
        string $gapsize = 'lg',
        string $rowgapsize = null,
        string $colgapsize = null,
        string $classes = null,
    ) {
        $this->columns             = $this->grid[ $columns ];
        $this->gapsize             = $gapsize ? $this->gap[ $gapsize ] : null;
        $this->rowgapsize          = $rowgapsize ? $this->rowgap[ $rowgapsize ] : null;
        $this->colgapsize          = $colgapsize ? $this->colgap[ $colgapsize ] : null;
        $this->classes             = $classes;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render(): string|\Illuminate\View\View
    {
        return $this->view('components.grid');
    }
}
