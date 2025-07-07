@if($errors->any())
<div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    @foreach ($errors->all() as $error)
        <i class="fa fa-times-circle"></i> {{ $error }}
    @endforeach
</div>
@endIf