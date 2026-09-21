@if(Session::get('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg shadow-sm" role="alert">
        <div class="flex items-start">
            <div class="flex-shrink-0 pt-0.5">
                <i class="fa fa-check-circle text-green-600 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-green-800">{{ Session::get('success') }}</p>
                @if(Session::has('download_contrat_url'))
                    <div class="mt-3">
                        <a href="{{ Session::get('download_contrat_url') }}" target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                            <i class="fa fa-download"></i> {{ Session::get('download_contrat_libelle', 'Télécharger le Contrat (PDF)') }}
                        </a>
                    </div>
                @endif
            </div>
            <button type="button" class="ml-3 flex-shrink-0 inline-flex text-green-400 hover:text-green-600 focus:outline-none"
                    onclick="this.parentElement.parentElement.remove()">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
@endif
