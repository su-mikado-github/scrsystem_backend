<?php

namespace App\View\Components;

use Illuminate\View\Component;

use App\Models\Menu;

class HorizontalTab extends Component
{
    public $id;

    public $category;

    public $item_key;

    public $query;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id, $category=null, $itemKey=null, $query=null)
    {
        //
        $this->id = $id;
        $this->category = $category;
        $this->item_key = $itemKey;
        $this->query = $query;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $menus = Menu::categoryBy($this->category ?? '*')->orderBy('display_order')->get();

        return view('components.horizontal-tab')
            ->with('menus', $menus)
        ;
    }
}
