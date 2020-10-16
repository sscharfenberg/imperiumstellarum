<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SwitchComponent extends Component
{

    /**
     * Is the checkbox checked
     *
     * @var boolean
     */
    public $isChecked;

    /**
     * refId is the name and id of the input
     *
     * @var string
     */
    public $refId;

    /**
     * label for when the switch is checked
     *
     * @var string
     */
    public $labelOn;

    /**
     * label for when the switch is not checked
     *
     * @var string
     */
    public $labelOff;

    /**
     * label for when the switch is not checked
     *
     * @var string
     */
    public $data;

    /**
     * Create a new component instance.
     * @param bool $isChecked
     * @param string $refId
     * @param string $labelOn
     * @param string $labelOff
     * @param string $data
     *
     * @return void
     */
    public function __construct(bool $isChecked, string $refId, string $labelOn, string $labelOff, string $data)
    {
        $this->isChecked = $isChecked;
        $this->refId = $refId;
        $this->labelOn = $labelOn;
        $this->labelOff = $labelOff;
        $this->data = $data;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.switch');
    }
}
