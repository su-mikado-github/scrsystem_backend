@if(session('success'))
<div class="alert alert-success {!! ($closable ? 'alert-dismissible' : '') !!} fade show" role="alert">
    {{ session('success') }}
    @if($closable ?? false)<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>@endif
</div>
@endif
@if(session('warning'))
<div class="alert alert-warning {!! ($closable ? 'alert-dismissible' : '') !!} fade show" role="alert">
    {{ session('warning') }}
    @if($closable ?? false)<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>@endif
</div>
@endif
@if(session('error'))
<div class="alert alert-danger {!! ($closable ? 'alert-dismissible' : '') !!} fade show" role="alert">
    {{ session('error') }}
    @if($closable ?? false)<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>@endif
</div>
@endif
