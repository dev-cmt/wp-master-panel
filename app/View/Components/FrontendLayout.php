<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FrontendLayout extends Component
{
    public $title;
    public $seotags;
    public $breadcrumbs;
    public $jsonld;

    // Accept title, seo_tags, and json_ld from the component tag
    public function __construct($title = null, $seotags = null, $breadcrumbs = null, $jsonld = null)
    {
        $this->title = $title;
        $this->seotags = $seotags;
        $this->breadcrumbs = $breadcrumbs;
        $this->jsonld = $jsonld;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('frontend.layouts.master');
    }
}
