<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TextSectionComponent extends Component
{

    /**
     * label for when the switch is checked
     *
     * @var string
     */
    public $hdl;

    /**
     * Create a new component instance.
     * @param string $hdl
     * @return void
     */
    public function __construct(string $hdl)
    {
        $this->hdl = $hdl;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.text-section');
    }
}
