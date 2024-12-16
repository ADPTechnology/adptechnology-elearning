<?php

namespace App\View\Components\Common;

use Illuminate\View\Component;

class WhatsappChat extends Component
{

    public $config;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.whatsapp-chat');
    }
}
