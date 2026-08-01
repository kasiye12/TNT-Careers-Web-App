<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatsCard extends Component
{
    public $title;
    public $value;
    public $icon;
    public $color;

    public function __construct($title, $value, $icon, $color = 'sky')
    {
        $this->title = $title;
        $this->value = $value;
        $this->icon = $icon;
        $this->color = $color;
    }

    public function render()
    {
        return view('components.stats-card');
    }
}
