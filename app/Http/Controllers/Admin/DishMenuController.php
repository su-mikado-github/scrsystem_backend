<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\DishMenusController;

use App\Flags;
use App\DishTypes;

use App\Models\Calendar;
use App\Models\DishMenu;

class DishMenuController extends Controller {
    //
    public function index(Request $request, $dish_type_key, $date) {
        $dish_type = DishTypes::key_of($dish_type_key);
        $today = Carbon::parse($date);

        $calendar = Calendar::enabled()->dateBy($date)->first();
        if (!$calendar) {
            $url = implode('?', [ route([ DishMenusController::class, 'index' ], [ 'dish_type_key'=>$dish_type_key ]), sprintf('year_month=%s', $today->format('Y-m')) ]);
            return response($url)
                ->with('error', __('messages.not_found.calendar'))
            ;
        }

        $dish_menus = $calendar->dish_menus()->dishTypeBy($dish_type->id)->where('is_delete', Flags::OFF)->orderBy('display_order')->get();

        //
        return view('pages.admin.dish_menu.index')
            ->with('date', $today)
            ->with('dish_menus', $dish_menus)
            ->with('dish_type', $dish_type)
        ;
    }

    public function put(Request $request, $dish_type_key, $date) {
        $user = $this->user();

        $dish_type = DishTypes::key_of($dish_type_key);
        $today = Carbon::parse($date);

        $calendar = Calendar::enabled()->dateBy($date)->first();
        if (!$calendar) {
            $url = implode('?', [ route([ DishMenusController::class, 'index' ], [ 'dish_type_key'=>$dish_type_key ]), sprintf('year_month=%s', $today->format('Y-m')) ]);
            return response($url)
                ->with('error', __('messages.not_found.calendar'))
            ;
        }

        $rules = [
            'dish_menu_id.*' => [ 'required', 'integer', 'exists:dish_menus,id' ],
            'name.*' => [ 'required', 'max:256' ],
            'energy.*' => [ 'required', 'numeric', 'min:0.0' ],
            'carbohydrates.*' => [ 'required', 'numeric', 'min:0.0' ],
            'protein.*' => [ 'required', 'numeric', 'min:0.0' ],
            'lipid.*' => [ 'required', 'numeric', 'min:0.0' ],
            'dietary_fiber.*' => [ 'required', 'numeric', 'min:0.0' ],
        ];
        $messages = [
            'dish_menu_id.*.required' => '料理メニューIDが指定されていません。',
            'dish_menu_id.*.integer' => '料理メニューIDが不正です。',
            'dish_menu_id.*.exists' => '料理メニューIDが正しくありません。',
            'name.*.required' => '料理メニュー名称は必須です。',
            'name.*.max' => '料理メニュー名称は256桁以内にしてください。',
            'energy.*.required' => 'エネルギーの指定は必須です。',
            'energy.*.numeric' => 'エネルギーには数値を指定してください。',
            'energy.*.min' => 'エネルギーにマイナスは指定できません。',
            'carbohydrates.*.required' => '炭水化物の指定は必須です。',
            'carbohydrates.*.numeric' => '炭水化物には数値を指定してください。',
            'carbohydrates.*.min' => '炭水化物にマイナスは指定できません。',
            'protein.*.required' => 'タンパク質の指定は必須です。',
            'protein.*.numeric' => 'タンパク質には数値を指定してください。',
            'protein.*.min' => 'タンパク質にマイナスは指定できません。',
            'lipid.*.required' => '脂質の指定は必須です。',
            'lipid.*.numeric' => '脂質には数値を指定してください。',
            'lipid.*.min' => '脂質にマイナスは指定できません。',
            'dietary_fiber.*.required' => '食物繊維の指定は必須です。',
            'dietary_fiber.*.numeric' => '食物繊維には数値を指定してください。',
            'dietary_fiber.*.min' => '食物繊維にマイナスは指定できません。',
        ];
        $this->try_validate($request->all(), $rules, $messages);

        return $this->trans(function() use($request, $dish_type, $today, $user) {
            $dish_menu_ids = $request->input('dish_menu_id');
            $names = $request->input('name');
            $energys = $request->input('energy');
            $carbohydratess = $request->input('carbohydrates');
            $proteins = $request->input('protein');
            $lipids = $request->input('lipid');
            $dietary_fibers = $request->input('dietary_fiber');

            $dish_menu_map = DishMenu::whereIn('id', $dish_menu_ids)->orderBy('display_order')->get()->groupBy('id');
            foreach ($dish_menu_ids as $i => $dish_menu_id) {
                $dish_menu = $dish_menu_map[$dish_menu_id][0];
                $name = $names[$i];
                $energy = $energys[$i];
                $carbohydrates = $carbohydratess[$i];
                $protein = $proteins[$i];
                $lipid = $lipids[$i];
                $dietary_fiber = $dietary_fibers[$i];

                $dish_menu->name = $name;
                $dish_menu->energy = $energy;
                $dish_menu->carbohydrates = $carbohydrates;
                $dish_menu->protein = $protein;
                $dish_menu->lipid = $lipid;
                $dish_menu->dietary_fiber = $dietary_fiber;
                $this->save($dish_menu, $user);
            }

            $url = implode('?', [ route('admin.dish_menus'), http_build_query([ 'year_month'=>$today->format('Y-m') ]) ]);
            return redirect($url)
                ->with('success', __('messages.success.dish_menu.put'))
            ;
        });
    }
}
