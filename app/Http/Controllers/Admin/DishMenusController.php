<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Flags;
use App\DishTypes;

use App\Traits\CsvBuilder;

use App\Models\MonthCalendar;
use App\Models\Calendar;
use App\Models\DishMenu;

class DishMenusController extends Controller {
    use CsvBuilder;

    private $before_date;

    protected function check_header(array $record, array &$errors) {
        $error = false;
        if (($record[0] ?? null) != '日付') {
            $errors[] = '列ヘッダーの1カラム名が「日付」になっていません。';
            $error = true;
        }
        if (($record[1] ?? null) != '種類') {
            $errors[] = '列ヘッダーの1カラム名が「種類」になっていません。';
            $error = true;
        }
        for ($i=0; $i<8; $i++) {
            $column_index = $i * 6 + 2;
            if (($record[$column_index] ?? null) != '名称') {
                $errors[] = '列ヘッダーの1カラム名が「名称」になっていません。';
                $error = true;
            }
            if (($record[$column_index+1] ?? null) != 'エネルギー') {
                $errors[] = '列ヘッダーの1カラム名が「エネルギー」になっていません。';
                $error = true;
            }
            if (($record[$column_index+2] ?? null) != '炭水化物') {
                $errors[] = '列ヘッダーの1カラム名が「炭水化物」になっていません。';
                $error = true;
            }
            if (($record[$column_index+3] ?? null) != 'タンパク質') {
                $errors[] = '列ヘッダーの1カラム名が「タンパク質」になっていません。';
                $error = true;
            }
            if (($record[$column_index+4] ?? null) != '脂質') {
                $errors[] = '列ヘッダーの1カラム名が「脂質」になっていません。';
                $error = true;
            }
            if (($record[$column_index+5] ?? null) != '食物繊維') {
                $errors[] = '列ヘッダーの1カラム名が「食物繊維」になっていません。';
                $error = true;
            }
        }
        return !$error;
    }

    protected function get_date(array $record, array &$errors) {
        if (preg_match('/^[0-9]{4}[\/][0-9]{2}[\/][0-9]{2}$/i', $record[0]) === false) {
            $errors[] = '日付はYYYY/MM/DD形式で指定してください。';
        }
        $yyyymmdd = explode('/', $record[0]);
        $calendar = Calendar::where('year', $yyyymmdd[0] ?? 0)->where('month', $yyyymmdd[1] ?? 0)->where('day', $yyyymmdd[2] ?? 0)->first();
        if (!$calendar) {
            $errors[] = '正しい日付ではありません。';
        }

        if (count($errors) == 0) {
            return $calendar;
        }
        else {
            return false;
        }
    }

    protected function get_dish_type(array $record, array &$errors) {
        $dish_type = DishTypes::column_value_of($record[1] ?? null);
        if (!$dish_type) {
            $errors[] = '種類は「食堂」「弁当」「試合用弁当」のいずれかを指定してください。';
        }

        if (count($errors) == 0) {
            return $dish_type;
        }
        else {
            return false;
        }
    }

    protected function get_dish_menus(array $record, array &$errors, DishTypes $dish_type) {
        $menus = [];
        for ($i=0; $i<$dish_type->menu_count; $i++) {
            $column_index = $i * 6 + 2;

            $error = false;
            $name = @$record[$column_index] ?? null;
            if (!isset($name)) {
                $errors[] = sprintf('%d番目のメニューの名称が指定されていません。', ($i+1));
                $error = true;
            }

            $energy = @$record[$column_index+1] ?? null;
            if (!isset($energy)) {
                $errors[] = sprintf('%d番目のメニューのエネルギーが指定されていません。', ($i+1));
                $error = true;
            }
            else if (!is_numeric($energy)) {
                $errors[] = sprintf('%d番目のメニューのエネルギーが数値ではありません。', ($i+1));
                $error = true;
            }

            $carbohydrates = @$record[$column_index+2];
            if (!isset($carbohydrates)) {
                $errors[] = sprintf('%d番目のメニューの炭水化物が指定されていません。', ($i+1));
                $error = true;
            }
            else if (!is_numeric($carbohydrates)) {
                $errors[] = sprintf('%d番目のメニューの炭水化物が数値ではありません。', ($i+1));
                $error = true;
            }

            $protein = @$record[$column_index+3];
            if (!isset($protein)) {
                $errors[] = sprintf('%d番目のメニューのタンパク質が指定されていません。', ($i+1));
                $error = true;
            }
            else if (!is_numeric($carbohydrates)) {
                $errors[] = sprintf('%d番目のメニューのタンパク質が数値ではありません。', ($i+1));
                $error = true;
            }

            $lipid = @$record[$column_index+4];
            if (!isset($lipid)) {
                $errors[] = sprintf('%d番目のメニューの脂質が指定されていません。', ($i+1));
                $error = true;
            }
            else if (!is_numeric($lipid)) {
                $errors[] = sprintf('%d番目のメニューの脂質が数値ではありません。', ($i+1));
                $error = true;
            }

            $dietary_fiber = @$record[$column_index+5];
            if (!isset($dietary_fiber)) {
                $errors[] = sprintf('%d番目のメニューの食物繊維が指定されていません。', ($i+1));
                $error = true;
            }
            else if (!is_numeric($dietary_fiber)) {
                $errors[] = sprintf('%d番目のメニューの食物繊維が数値ではありません。', ($i+1));
                $error = true;
            }

            if ($error) {
                $menus[] = false;
            }
            else {
                $menus[] = compact('name', 'energy', 'carbohydrates', 'protein', 'lipid', 'dietary_fiber');
            }
        }
        return $menus;
    }

    protected function compless($path, array $files) {
        $zip = new \ZipArchive();
        $zip->open($path, \ZipArchive::CREATE|\ZipArchive::OVERWRITE);
        try {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $zip->addFile($file);
                }
            }
        }
        finally {
            $zip->close();
        }
    }

    protected function archive_upload_file($upload_file_path) {
        $archive_dir_path = storage_path('app/archive_files');
        if (!file_exists($archive_dir_path)) {
            mkdir($archive_dir_path);
        }

        $mtime = filemtime($upload_file_path);

        $archive_basename = date('Ymd-His', $mtime) . '.zip';
        $archive_file_path = sprintf('%s/%s', $archive_dir_path, $archive_basename);

        $this->compless($archive_file_path, [ $upload_file_path ]);
        unlink($upload_file_path);
    }

    //
    public function index(Request $request, $dish_type_key=null) {
        //
        $dish_type = DishTypes::key_of($dish_type_key, DishTypes::DINING_HALL());

        $year_month = $request->query('year_month', today()->format('Y-m'));
        $date = sprintf('%s-01', $year_month);

        $month_calendar = MonthCalendar::where('date', '<=', $date)->where('last_date', '>=', $date)->first();
        abort_if(!$month_calendar, 404, __('messages.not_found.month_calendar'));

        $calendars = Calendar::with([ 'daily_dish_menus'=>function($query) use($dish_type) {
            $query->where('dish_type', $dish_type->id);
        } ])->yearMonthBy($month_calendar->year, $month_calendar->month)->orderBy('day')->get();

        return view('pages.admin.dish_menus.index')
            ->with('dish_type', $dish_type)
            ->with('year_month', $year_month)
            ->with('month_calendar', $month_calendar)
            ->with('calendars', $calendars)
        ;
    }

    public function post_upload(Request $request, $dish_type_key) {
        $user = $this->user();

        logger()->debug($request->input('file_path'));

        $file_path = $request->input('file_path');
        abort_if(!$file_path, 400, __('messages.invalidate.upload.dish_menu'));

        $path = storage_path(sprintf('app/%s', $file_path));
        abort_if(!file_exists($path), 400, __('messages.invalidate.upload.dish_menu'));

        $fp = fopen($path, 'r');
        abort_if(!$fp, 412, __('messages.io_error.dish_menu_csv_read'));

        $errors = collect();
        try {
            $i = 0;
            while (!feof($fp)) {
                $record_error = [];

                $record = fgetcsv($fp);
                if ($record) {
                    mb_convert_variables('UTF-8', 'SJIS-win', $record);

                    if ($i == 0) {
                        $this->check_header($record, $record_error);
                    }
                    else {
                        $date = $this->get_date($record, $record_error);

                        $dish_type = $this->get_dish_type($record, $record_error);
                        if ($dish_type) {
                            $menus = $this->get_dish_menus($record, $record_error, $dish_type);

                            if ($date) {
                                $this->trans(function() use($user, $date, $dish_type, $menus) {
                                    //登録済みデータは削除する
                                    $date->dish_menus()->dishTypeBy($dish_type)->delete();

                                    //料理メニューの登録
                                    foreach ($menus as $index => $menu) {
                                        if ($menu) {
                                            $dish_menu = new DishMenu();
                                            $dish_menu->calendar_id = $date->id;
                                            $dish_menu->dish_type = $dish_type->id;
                                            $dish_menu->display_order = $index + 1;
                                            $dish_menu->name = $menu['name'];
                                            $dish_menu->energy = $menu['energy'];
                                            $dish_menu->carbohydrates = $menu['carbohydrates'];
                                            $dish_menu->protein = $menu['protein'];
                                            $dish_menu->lipid = $menu['lipid'];
                                            $dish_menu->dietary_fiber = $menu['dietary_fiber'];
                                            $dish_menu->is_delete = Flags::OFF;
                                            $this->save($dish_menu, $user);
                                            // logger()->debug(sprintf('%d行目(日付=%s 種類=%s): 保存完了', ($index+1), $date->date, $dish_type->column_value));
                                        }
                                    }
                                });
                            }
                        }
                    }
                }
                if (count($record_error) > 0) {
                    logger()->warning($record_error);
                    $errors->push((object)[ 'line_no'=>($i+1), 'causes'=>$record_error ]);
                }

                $i ++;
            }
            logger()->info(sprintf('レコード数: %d', $i));
        }
        catch (\Exception $ex) {
            logger()->error($ex);
        }
        finally {
            fclose($fp);
        }

        if ($errors->count() > 0) {
            unlink($path);
            return view('pages.admin.dish_menus.post_upload')
                ->with('errors', $errors)
            ;
        }

        $this->archive_upload_file($path);

        return redirect()->action([ self::class, 'index' ], [ 'dish_type_key'=>$dish_type_key ])
            ->with('success', 'アップロードが完了しました。')
        ;
    }

    public function get_download(Request $request, $dish_type_key) {
        //
        $today = today();
        $year = $request->input('year', $today->year);
        $month = $request->input('month', $today->month);
        $dish_type = DishTypes::key_of($dish_type_key);

        logger()->debug(compact('year','month','dish_type'));

        $calendar_ids = Calendar::enabled()->yearMonthBy($year, $month)->pluck('id');
        $dish_menus = DishMenu::withCasts([ 'date'=>'date' ])->join('calendars', 'calendars.id', '=', 'dish_menus.calendar_id')
            ->where('dish_menus.dish_type', $dish_type->id)
            ->whereIn('dish_menus.calendar_id', $calendar_ids)
            ->orderBy('calendars.date')
            ->orderBy('dish_menus.display_order')
            ->selectRaw('dish_menus.*, calendars.date as date')
            ->get()
        ;

        $filename = sprintf('dish_menus-%s.csv', uniqid());
        $path = storage_path(sprintf('app/download_files/%s', $filename));

        $header = ($dish_type->id == DishTypes::DINING_HALL ? [
            '日付', '種類',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            // '', '', '', '', '', '',
        ] : [
            '日付', '種類',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
            '名称', 'エネルギー', '炭水化物', 'タンパク質', '脂質', '食物繊維',
        ]);
        $this->write_csv($path, $header, function($index) use($dish_menus, $dish_type) {
            $row_count = ($dish_type->id == DishTypes::DINING_HALL ? 8 : 9);
            $row_index = $index * $row_count;

            if ($dish_menus->count() <= $row_index + $row_count) {
                return false;
            }

            $record = [
                $dish_menus[$row_index]->date->format('Y/m/d'),
                $dish_type->column_value,
            ];
            for ($i=0; $i<$row_count; $i++) {
                $dish_menu = $dish_menus[$row_index+$i];
                $record[] = $dish_menu->name;
                $record[] = $dish_menu->energy;
                $record[] = $dish_menu->carbohydrates;
                $record[] = $dish_menu->protein;
                $record[] = $dish_menu->lipid;
                $record[] = $dish_menu->dietary_fiber;
            }
            return $record;
        });

        return response()->download($path, sprintf('メニュー一覧_%s.csv', date('Ymd-Hms')), [
            'Content-Type: text/csv'
        ]);
    }
}
