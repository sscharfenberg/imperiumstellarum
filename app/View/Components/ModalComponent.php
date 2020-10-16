<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ModalComponent extends Component
{

    /**
     * The title of the modal
     *
     * @var string
     */
    public $title;

    /**
     * The "data-modal" attribute
     *
     * @var string
     */
    public $refId;

    /**
     * Create a new component instance.
     * @param string $title
     * @param string $refId
     * @return void
     */
    public function __construct(string $title, string $refId)
    {
        $this->title = $title;
        $this->refId = $refId;
    }



    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
