<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ConfirmDeleteModal extends Component
{
    public $name;

    public $actionUrl;

    public $title;

    public $confirmText;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name,
        $actionUrl,
        $title = 'Are you sure you want to delete this?',
        $confirmText = null
    ) {
        $this->name = $name;
        $this->actionUrl = $actionUrl;
        $this->title = $title;
        $this->confirmText = $confirmText;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.confirm-delete-modal');
    }
}
