<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SuccessComponent extends Component
{

    /**
     * The size of the success animation
     *
     * @var string
     */
    public $size;

    /**
     * Create a new component instance.
     * @param string $size
     * @return void
     */
    public function __construct(string $size)
    {
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.success');
    }
}
