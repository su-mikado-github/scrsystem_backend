@extends('layouts.dining_hall')

@section('main')
<div class="px-3 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">チェックイン状況</h3>
    <div class="row">
    @foreach($dining_hall_reserves as $reserve)
        @eval($reserve_user = $reserve->user)
        @eval($is_checkin = isset($reserve->checkin_dt))
        <div class="col-6 text-nowrap text-center pe-3">
            <div class="border {!! $is_checkin ? 'scrs-bg-main' : 'scrs-border-main' !!} py-4">
                <ruby>
                    <rb class="display-6 {!! $is_checkin ? '' : 'text-body' !!} fw-bold me-2">{{ $reserve_user->last_name }}</rb>
                    <rt class="{!! $is_checkin ? '' : 'text-body' !!} pb-2">{{ $reserve_user->last_name_kana }}</rt>
                </ruby>
                <ruby>
                    <rb class="display-6 {!! $is_checkin ? '' : 'text-body' !!} fw-bold">{{ $reserve_user->first_name }}</rb>
                    <rt class="{!! $is_checkin ? '' : 'text-body' !!} pb-2">{{ $reserve_user->first_name_kana }}</rt>
                </ruby>
            </div>
        </div>
    @endforeach
    </div>
</div>

<br>
<div class="px-3 py-4 scrs-sheet-normal">
    <h3 class="text-center mb-4">お弁当の受け取り状況</h3>
    <div class="row">
    @foreach($lunchbox_reserves as $reserve)
        @eval($reserve_user = $reserve->user)
        @eval($is_checkin = isset($reserve->checkin_dt))
        <div class="col-6 text-nowrap text-center ps-3">
            <div class="border {!! $is_checkin ? 'scrs-bg-main' : 'scrs-border-main' !!} py-4">
                <ruby>
                    <rb class="display-6 {!! $is_checkin ? '' : 'text-body' !!} fw-bold mt-2 me-2">{{ $reserve_user->last_name }}</rb>
                    <rt class="{!! $is_checkin ? '' : 'text-body' !!} pb-2">{{ $reserve_user->last_name_kana }}</rt>
                </ruby>
                <ruby>
                    <rb class="display-6 {!! $is_checkin ? '' : 'text-body' !!} fw-bold">{{ $reserve_user->first_name }}</rb>
                    <rt class="{!! $is_checkin ? '' : 'text-body' !!} pb-2">{{ $reserve_user->first_name_kana }}</rt>
                </ruby>
            </div>
        </div>
    @endforeach
    </div>
</div>

@endsection
