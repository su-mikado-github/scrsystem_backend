<?php

namespace App\View\Components;

use Illuminate\View\Component;

use App\Models\Menu;

class VirticalMenu extends Component
{
    public $id;

    public $category;

    public $item_key;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id, $category=null, $itemKey=null)
    {
        //
        $this->id = $id;
        $this->category = $category;
        $this->item_key = $itemKey;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $menus = Menu::categoryBy($this->category ?? '*')->orderBy('display_order')->get();

        return view('components.virtical-menu')
            ->with('menus', $menus)
        ;
    }
}
