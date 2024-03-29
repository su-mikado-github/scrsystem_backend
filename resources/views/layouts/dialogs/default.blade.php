@push('dialogs')
<div class="modal fade px-3" id="{!! $id !!}" style="z-index:3000;" tabindex="-1" aria-labelledby="{!! $id !!}_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-bottom-0 py-1">
                <h6 class="modal-title">@yield($id . 'dialog-title', $title ?? '')</h6>
                <button type="button" class="btn-close p-0" style="font-size:80%;" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body border-top-0 py-0">
                @yield($id . 'dialog-body', $slot ?? '')
            </div>
            <div class="modal-footer border-top-0 d-block">
                @yield($id . 'dialog-footer', $footer ?? '')
            </div>
        </div>
    </div>
</div>
@endpush
