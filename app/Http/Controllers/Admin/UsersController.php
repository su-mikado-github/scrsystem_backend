<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\AffiliationDetailTypes;
use App\Flags;
use App\SortTypes;

use App\Models\User;

class UsersController extends Controller {
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

        $filename = sprintf('admin_users-%s.csv', uniqid());
        $path = storage_path(sprintf('app/download_files/%s', $filename));

        $fp = fopen($path, 'w');
        try {
            // CSV列ヘッダーの出力
            $header = [ '氏名', '所属', '学年', '年齢', 'メールアドレス', '電話番号' ];
            mb_convert_variables('SJIS', 'UTF-8', $header);
            fputcsv($fp, $header);

            // CSV列データの出力
            foreach($users as $user) {
                $full_name = collect()
                    ->when(isset($user->last_name), function($collection) use($user) { return $collection->push($user->last_name); })
                    ->when(isset($user->first_name), function($collection) use($user) { return $collection->push($user->first_name); })
                    ->join(' ')
                ;
                $affiliation = collect()
                    ->when(isset($user->affiliation_id), function($collection) use($user) { return $collection->push($user->affiliation->name); })
                    ->when(isset($user->affiliation_detail_id), function($collection) use($user) { return $collection->push($user->affiliation_detail->name); })
                    ->join(' ')
                ;
                $school_year = (op($user->affiliation)->detail_type == AffiliationDetailTypes::INTERNAL ? op($user->school_year)->name : null) ?? '';
                $record = [
                    $full_name,
                    $affiliation,
                    $school_year, ($user->age ?? ''),
                    ($user->email ?? ''),
                    ($user->telephone_no ?? '')
                ];
                mb_convert_variables('SJIS', 'UTF-8', $record);
                fputcsv($fp, $record);
            }
        }
        catch (\Exception $ex) {
            logger()->error($ex);
            return redirect()->route('admin.users')
                ->with('error', __('messages.io_error.users_csv_write'))
            ;
        }
        finally {
            fclose($fp);
        }

        return response()->download($path, sprintf('利用者一覧_%s.csv', date('Ymd-Hms')), [
            'Content-Type: text/csv'
        ]);
    }
}
