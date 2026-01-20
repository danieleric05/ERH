@if(Session::get('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg" role="alert">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fa fa-check-circle text-green-600 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-green-800">{{ Session::get('success') }}</p>
            </div>
            <button type="button" class="ml-3 flex-shrink-0 inline-flex text-green-400 hover:text-green-600 focus:outline-none"
                    onclick="this.parentElement.parentElement.remove()">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
@endif
