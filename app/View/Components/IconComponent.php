<?php

namespace App\View\Components;

use Illuminate\View\Component;

class IconComponent extends Component
{
    /**
     * The name of the icon
     *
     * @var string
     */
    public $name;

    /**
     * The size of the icon
     *
     * @var string
     */
    public $size;

    /**
     * Create a new component instance.
     * @param string $name
     * @param string $size
     *
     * @return void
     */
    public function __construct(string $name, string $size)
    {
        $this->name = $name;
        $this->size = $size;
    }

    /**
     * Determine the additional class for the icon
     *
     * @return string
     */
    public function sizeClass()
    {
        if ($this->size === '0') return ' tiny';
        if ($this->size === '1') return ' small';
        if ($this->size === '3') return ' large';
        if ($this->size === '4') return ' xlarge';
        return '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('components.icon');
    }
}
