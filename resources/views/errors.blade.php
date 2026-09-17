@if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg" role="alert">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fa fa-exclamation-circle text-red-600 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <ul class="list-disc list-inside text-sm text-red-800">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="ml-3 flex-shrink-0 inline-flex text-red-400 hover:text-red-600 focus:outline-none"
                    onclick="this.parentElement.parentElement.remove()">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
@endif
