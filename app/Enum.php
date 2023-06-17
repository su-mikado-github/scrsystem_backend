<?php
namespace App;

use \Exception;
use \ReflectionClass;

use Carbon\Carbon;

/**
 * 列挙型スーパークラス
 */
abstract class Enum
{
    private static $enums = [];

    final private static function build($class) {
        if (!isset(self::$enums[$class])) {
            $values = [];
            $ref = new ReflectionClass($class);
            $consts = $ref->getConstants();
            foreach ($consts as $key => $value) {
                $values[$key] = new $class($value, $key);
            }
            self::$enums[$class] = $values;
        }
        return self::$enums[$class];
    }

    final public static function values(array $excludes=[]) {
        $enum = self::build(get_called_class());
        return array_values(array_filter($enum, function($value, $name) use($excludes) {
            return (!in_array($name, $excludes) && !in_array($value, $excludes));
        }, ARRAY_FILTER_USE_BOTH));
    }

    final public static function to_hash(array $excludes=[]) {
        $enum = self::build(get_called_class());
        return array_filter($enum, function($value, $name) use($excludes) {
            return (!in_array($name, $excludes) && !in_array($value, $excludes));
        }, ARRAY_FILTER_USE_BOTH);
    }

    final public static function to_json(array $excludes=[]) {
        $called_class = get_called_class();
        $enum = self::build($called_class);
        $filteredEnum = array_filter($enum, function($value, $name) use($excludes) {
            return (!in_array($name, $excludes) && !in_array($value, $excludes));
        }, ARRAY_FILTER_USE_BOTH);
        $result = [];
        foreach ($filteredEnum as $item) {
            $result[$item->name] = [ 'id' => $item->id, 'option' => $called_class::$options[$item->name] ?? [] ];
        }
        return $result;
    }

    final public static function id_list(array $excludes=[]) {
        $enum = self::build(get_called_class());
        $values = array_filter($enum, function($value, $name) use($excludes) {
            return (!in_array($name, $excludes) && !in_array($value, $excludes));
        }, ARRAY_FILTER_USE_BOTH);
        return array_values(array_map(function($value) { return $value->id; }, $values));
    }

    final public static function ids($separater=',', array $excludes=[]) {
        $enum = self::build(get_called_class());
        $values = array_filter($enum, function($value, $name) use($excludes) {
            return (!in_array($name, $excludes) && !in_array($value, $excludes));
        }, ARRAY_FILTER_USE_BOTH);
        return implode($separater, array_values(array_map(function($value) { return $value->id; }, $values)));
    }

    final public static function name_list(array $excludes=[]) {
        $enum = self::build(get_called_class());
        $values = array_filter($enum, function($value, $name) use($excludes) {
            return (!in_array($name, $excludes) && !in_array($value, $excludes));
        }, ARRAY_FILTER_USE_BOTH);
        return array_values(array_map(function($value) { return $value->name; }, $values));
    }

    final public static function of($id, $default_value=null) {
        $values = self::build(get_called_class());
        foreach ($values as $enum) {
            if ($enum->id == $id || $enum->name === $id) {
                return $enum;
            }
        }
        return $default_value;
    }

    final public static function __callStatic($label, $args) {
        $values = self::build(get_called_class());
        return $values[$label];
    }

    protected function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;

        $class = get_called_class();
        if (isset($class::$options[$name]) && is_array($class::$options[$name])) {
            foreach ($class::$options[$name] as $key => $value) {
                if (in_array($key, ['id','name'])) {
                    throw new Exception("'id' or 'name' is reserved of property name on Enum object.");
                }
                $this->{$key} = $value;
            }
        }
    }

    public function __toString() {
        return $this->name;
    }
}

/**
 * 列挙型：フラグ
 */
final class Flags extends Enum {
    const OFF = 0;
    const ON = 1;

    static $options = [
        'OFF' => [ 'ja'=>'OFF' ],
        'ON' => [ 'ja'=>'ON' ],
    ];

}

/**
 * 列挙型：曜日
 */
final class Weekdays extends Enum {
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 7;

    static $options = [
        'MONDAY' => [ 'ja'=>'月' ],
        'TUESDAY' => [ 'ja'=>'火' ],
        'WEDNESDAY' => [ 'ja'=>'水' ],
        'THURSDAY' => [ 'ja'=>'木' ],
        'FRIDAY' => [ 'ja'=>'金' ],
        'SATURDAY' => [ 'ja'=>'土' ],
        'SUNDAY' => [ 'ja'=>'日' ],
    ];

    public static function fromDate($date, Weekdays $default=null) {
        if ($date instanceof Carbon) {
            $weekday = $date->dayOfWeek;
            if ($weekday == 0) {
                return self::SUNDAY();
            }
            else {
                return self::of($weekday);
            }
        }
        else if (is_string($date) && preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date)) {
            $time = strtotime($date);
            $weekday = date('w', $time);
            if ($weekday == 0) {
                return self::SUNDAY();
            }
            else {
                return self::of($weekday);
            }
        }
        else if (is_numeric($date)) {
            $weekday = date('w', $date);
            if ($weekday == 0) {
                return self::SUNDAY();
            }
            else {
                return self::of($weekday);
            }
        }
        else {
            return $default;
        }
    }
}

/**
 * 列挙型：性別
 */
final class Genders extends Enum {
    const MALE = 1;
    const FEMALE = 2;

    static $options = [
        'MALE' => [ 'ja'=>'男性' ],
        'FEMALE' => [ 'ja'=>'女性' ],
    ];
}

/**
 * 列挙型：予約種別
 */
final class ReserveTypes extends Enum {
    const LUNCHBOX = 1;
    const VISIT_SOCCER = 2;
    const VISIT_NO_SOCCER = 3;

    static $options = [
        'LUNCHBOX' => [ 'ja'=>'お弁当', 'title'=>'お弁当' ],
        'VISIT_SOCCER' => [ 'ja'=>'来店(サッカー部)', 'title'=>'来店' ],
        'VISIT_NO_SOCCER' => [ 'ja'=>'来店(サッカー部以外)', 'title'=>'来店' ],
    ];
}

/**
 * 列挙型：所属詳細分類
 */
final class AffiliationDetailTypes extends Enum {
    const INTERNAL = 1;
    const EXTERNAL = 2;

    static $options = [
        'INTERNAL' => [ 'ja'=>'学部内' ],
        'EXTERNAL' => [ 'ja'=>'学部外' ],
    ];
}

/**
 * 列挙型：料理種類
 */
final class DishTypes extends Enum {
    const DINING_HALL = 1;
    const LUNCHBOX = 2;
    const BOUT_LUNCHBOX = 3;

    static $options = [
        'DINING_HALL' => [ 'ja'=>'食堂', 'key'=>'dining_hall', 'column_value'=>'食堂', 'menu_count'=>7 ],
        'LUNCHBOX' => [ 'ja'=>'お弁当(通常)', 'key'=>'lunchbox', 'column_value'=>'弁当', 'menu_count'=>8 ],
        'BOUT_LUNCHBOX' => [ 'ja'=>'お弁当(試合)', 'key'=>'bout_lunchbox', 'column_value'=>'試合用弁当', 'menu_count'=>8 ],
    ];

    public static function key_of($value, $default=null) {
        foreach (self::values() as $enum) {
            if ($enum->key == $value) {
                return $enum;
            }
        }
        return $default;
    }

    public static function column_value_of($value, $default=null) {
        foreach (self::values() as $enum) {
            if ($enum->column_value == $value) {
                return $enum;
            }
        }
        return $default;
    }
}

/**
 * 列挙型：メニューアイテム種類
 */
final class MenuItemTypes extends Enum {
    const SEPARATER = 0;
    const INSIDE_LINK = 1;
    const OUTSIDE_LINK = 2;
    const ACTION = 3;
    const SUB_MENUS = 4;

    static $options = [
        'SEPARATER' => [ 'ja'=>'セパレータ' ],
        'INSIDE_LINK' => [ 'ja'=>'サイト内リンク' ],
        'OUTSIDE_LINK' => [ 'ja'=>'サイト外リンク' ],
        'ACTION' => [ 'ja'=>'アクション処理' ],
        'SUB_MENUS' => [ 'ja'=>'別メニュー展開' ],
    ];
}

final class SortTypes extends Enum {
    const ASC = 1;
    const DESC = 2;

    static $options = [
        'ASC' => [ 'ja'=>'昇順', 'sql_order_by'=>'asc' ],
        'DESC' => [ 'ja'=>'降順', 'sql_order_by'=>'desc' ],
    ];
}

