<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CheckboxComponent extends Component
{
    /**
     * Is the checkbox checked
     *
     * @var boolean
     */
    public $selected;

    /**
     * refId is the name and id of the input
     *
     * @var string
     */
    public $refId;

    /**
     * checkbox has error?
     *
     * @var boolean
     */
    public $error;


    /**
     * Create a new component instance.
     * @param bool $selected
     * @param string $refId
     * @param bool $error
     * @return void
     */
    public function __construct(bool $selected, string $refId, bool $error)
    {
        $this->selected = $selected;
        $this->refId = $refId;
        $this->error = $error;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('components.checkbox');
    }
}
