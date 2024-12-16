<?php

namespace App\View\Components\Common;

use App\Models\Advertising;
use Illuminate\View\Component;

class Advertisements extends Component
{
    public $advertisements;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $now = now('America/Lima')->toDateString();

        $this->advertisements = Advertising::whereHas('coupon', function ($q) use ($now) {
            $q->where('expired_date', '>=', $now);
        })
            ->with([
                'file' => fn($q) => $q->where('category', 'advertisements'),
                'plan',
                'coupon'
            ])
            ->where('active', 'S')
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.advertisements');
    }
}
