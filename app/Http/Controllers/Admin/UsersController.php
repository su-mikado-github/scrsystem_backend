<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\AffiliationDetailTypes;
use App\Flags;
use App\ReserveTypes;
use App\SortTypes;

use App\Traits\CsvBuilder;

use App\Models\User;

class UsersController extends Controller {
    use CsvBuilder;

    //
    public function index(Request $request) {
        $full_name_sort = $request->input('full_name_sort', SortTypes::ASC);
        $affiliation_sort = $request->input('affiliation_sort', SortTypes::ASC);
        $affiliation_detail_sort = $request->input('affiliation_detail_sort', SortTypes::ASC);
        $school_year_sort = $request->input('school_year_sort', SortTypes::ASC);
        $sort_orders = collect(explode(',', $request->input('sort_orders', 'full_name,affiliation,affiliation_detail,school_year')));
        $users_query = User::with([ 'affiliation', 'affiliation_detail', 'school_year' ])
            ->where('users.is_delete', Flags::OFF)
            ->where('users.is_admin', Flags::OFF)
            ->orderBy('users.is_initial_setting', 'desc')
        ;
        $users_query = $sort_orders
            ->reduce(function($query, $sort_order) use($full_name_sort, $affiliation_sort, $affiliation_detail_sort, $school_year_sort) {
                if ($sort_order == 'full_name') {
                    return $query->fullNameOrder($full_name_sort);
                }
                else if ($sort_order == 'affiliation') {
                    return $query->affiliationOrder($affiliation_sort);
                }
                else if ($sort_order == 'affiliation_detail') {
                    return $query->affiliationDetailOrder($affiliation_detail_sort);
                }
                else if ($sort_order == 'school_year') {
                    return $query->schoolYearOrder($school_year_sort);
                }
                return $query;
            }, $users_query);

        $users = $users_query->selectRaw('users.*, affiliations.display_order as affiliation_display_oder, affiliation_details.display_order as affiliation_detail_display_order, school_years.display_order as school_year_display_order')
            ->distinct()
            ->get();

        return view('pages.admin.users.index')
            ->with('users', $users)
            ->with('full_name_sort', $full_name_sort)
            ->with('affiliation_sort', $affiliation_sort)
            ->with('affiliation_detail_sort', $affiliation_detail_sort)
            ->with('school_year_sort', $school_year_sort)
            ->with('sort_orders', $sort_orders)
        ;
    }

    public function download(Request $request) {
        $full_name_sort = $request->input('full_name_sort', SortTypes::ASC);
        $affiliation_sort = $request->input('affiliation_sort', SortTypes::ASC);
        $affiliation_detail_sort = $request->input('affiliation_detail_sort', SortTypes::ASC);
        $school_year_sort = $request->input('school_year_sort', SortTypes::ASC);
        $sort_orders = collect(explode(',', $request->input('sort_orders', 'full_name,affiliation,affiliation_detail,school_year')));
        $users_query = User::with([ 'affiliation', 'affiliation_detail', 'school_year', 'reserves'=>function($query) { return $query->where('is_delete', Flags::OFF); } ])
            ->where('users.is_delete', Flags::OFF)
            ->where('users.is_admin', Flags::OFF)
            ->orderBy('users.is_initial_setting', 'desc')
        ;
        $users_query = $sort_orders
            ->reduce(function($query, $sort_order) use($full_name_sort, $affiliation_sort, $affiliation_detail_sort, $school_year_sort) {
                if ($sort_order == 'full_name') {
                    return $query->fullNameOrder($full_name_sort);
                }
                else if ($sort_order == 'affiliation') {
                    return $query->AffiliationOrder($affiliation_sort);
                }
                else if ($sort_order == 'affiliation_detail') {
                    return $query->AffiliationDetailOrder($affiliation_detail_sort);
                }
                else if ($sort_order == 'school_year') {
                    return $query->SchoolYearOrder($school_year_sort);
                }
                return $query;
            }, $users_query);

        $users = $users_query->selectRaw('users.*, affiliations.display_order as affiliation_display_oder, affiliation_details.display_order as affiliation_detail_display_order, school_years.display_order as school_year_display_order')
            ->distinct()
            ->get();

        $output_dir = storage_path('app/download_files');
        if (!file_exists($output_dir)) {
            mkdir($output_dir, 0777, true);
        }

        $filename = sprintf('admin_users-%s.csv', uniqid());
        $path = storage_path(sprintf('app/download_files/%s', $filename));

        $state = (object)[ 'users_index' => 0 ];
        $this->stateful_write_csv($path, $state, [ '氏名', '所属１', '所属２', '学年', '年齢', 'メールアドレス', '電話番号', '予約日', '予約時間', '食堂（人数）', '弁当（個数）', '利用状況' ], function($state, $index, callable $writer) use($users) {
            if ($users->count() <= $state->users_index) {
                return false;
            }

            $user = $users[$state->users_index];

            $full_name = collect()
                ->when(isset($user->last_name), function($collection) use($user) { return $collection->push($user->last_name); })
                ->when(isset($user->first_name), function($collection) use($user) { return $collection->push($user->first_name); })
                ->join(' ')
            ;
            $affiliation_name = (isset($user->affiliation_id) ? $user->affiliation->name : '');
            $affiliation_detail_name = (isset($user->affiliation_id) ? $user->affiliation_detail->name : '');
            $school_year = (op($user->affiliation)->detail_type == AffiliationDetailTypes::INTERNAL ? op($user->school_year)->name : null) ?? '';

            if ($user->reserves->count() == 0) {
                $writer([
                    $full_name,
                    $affiliation_name,
                    $affiliation_detail_name,
                    $school_year,
                    ($user->age ?? ''),
                    ($user->email ?? ''),
                    ($user->telephone_no ?? ''),
                    '',
                    '',
                    '',
                    '',
                    '',
                ]);
                $index ++;
            }
            else {
                foreach ($user->reserves as $reserve) {
                    $status = '予約中';
                    if (isset($reserve->checkin_dt)) {
                        $status = 'チェックイン';
                    }
                    else if (isset($reserve->cancel_dt)) {
                        $status = 'キャンセル';
                    }
                    $writer([
                        $full_name,
                        $affiliation_name,
                        $affiliation_detail_name,
                        $school_year,
                        ($user->age ?? ''),
                        ($user->email ?? ''),
                        ($user->telephone_no ?? ''),
                        $reserve->date->format('Y/m/d'),
                        time_to_hhmm($reserve->time),
                        ($reserve->type != ReserveTypes::LUNCHBOX ? '' : $reserve->reserve_count),
                        ($reserve->type == ReserveTypes::LUNCHBOX ? $reserve->reserve_count : ''),
                        $status,
                    ]);
                    $index ++;
                }
            }

            $state->users_index ++;
            return [ $state, $index ];
        });

        return response()->download($path, sprintf('利用者一覧_%s.csv', date('Ymd-His')), [
            'Content-Type: text/csv'
        ]);
    }
}
