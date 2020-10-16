<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LoadingComponent extends Component
{

    /**
     * The size of the loading spinner
     *
     * @var string
     */
    public $size;

    /**
     * Create a new component instance.
     * @param int $size
     * @return void
     */
    public function __construct(int $size)
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
        return view('components.loading');
    }
}
