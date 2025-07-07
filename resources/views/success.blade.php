@if(Session::get('success'))
<div class="alert alert-primary alert-dismissible" style="font-weight: bold" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <i class="fa fa-times-circle"></i> {{Session::get('success')}}
</div>
@endIf