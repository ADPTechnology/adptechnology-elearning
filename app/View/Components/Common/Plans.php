<?php

namespace App\View\Components\Common;

use App\Models\Plan;
use Auth;
use Illuminate\View\Component;

class Plans extends Component
{
    public $plans;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {

        $query = Plan::with([
            'file' => fn($q) => $q->where('category', 'plans')
        ]);

        if (Auth::check()) {

            $user = Auth::user();

            $this->plans = $query
                ->withCount([
                    'cart' => fn($q) => $q->where('user_id', $user->id),
                ])
                ->get();
        } else {
            $this->plans = $query->get();
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.plans');
    }
}
