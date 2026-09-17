{{-- Search Form + taille de page --}}
<div class="mb-6 flex justify-end items-center gap-3">
    <form method="GET">
        <select name="per_page" onchange="this.form.submit()" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
            @foreach([25, 50, 100, 200] as $n)
                <option value="{{ $n }}" @selected((int) request('per_page', 50) === $n)>{{ $n }} / page</option>
            @endforeach
        </select>
        @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
        @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
        @if(request('dir'))<input type="hidden" name="dir" value="{{ request('dir') }}">@endif
    </form>

    <form method="GET" class="relative flex items-center max-w-lg w-full"
          x-data="travailleurSearch(@js(request('search', '')))" @click.outside="open = false" autocomplete="off">
        <input type="text" name="search" x-model="query" @input.debounce.300ms="search()" @focus="if(suggestions.length) open = true"
               placeholder="Rechercher par nom, prénom, matricule..."
               autocomplete="off"
               class="w-full px-4 py-2 border border-slate-300 rounded-l-lg focus:ring-primary-accent focus:border-primary-accent transition-shadow">
        <button type="submit" class="px-4 py-2 bg-primary-accent text-white font-semibold rounded-r-lg hover:bg-plastica-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent transition-colors">
            <i class="fa fa-search"></i>
        </button>

        <div x-show="open" x-cloak
             class="absolute top-full left-0 right-10 mt-1 bg-white border border-slate-200 rounded-lg shadow-lg-soft z-20 max-h-72 overflow-y-auto">
            <template x-for="s in suggestions" :key="s.id">
                <button type="button" @click="select(s)"
                        class="w-full text-left px-4 py-2 hover:bg-slate-50 border-b border-slate-100 last:border-0 flex items-center justify-between">
                    <span x-text="s.nom + ' ' + s.prenom"></span>
                    <span class="text-xs text-text-secondary font-mono" x-text="s.matricule"></span>
                </button>
            </template>
            <div x-show="suggestions.length === 0" class="px-4 py-2 text-sm text-text-secondary">Aucun résultat</div>
        </div>
    </form>
</div>

@once
<script>
function travailleurSearch(initialQuery) {
    return {
        query: initialQuery || '',
        suggestions: [],
        open: false,
        search() {
            if (this.query.length < 2) { this.suggestions = []; this.open = false; return; }
            fetch('{{ route("travailleurs.autocomplete") }}?q=' + encodeURIComponent(this.query))
                .then(r => r.json())
                .then(data => { this.suggestions = data; this.open = true; })
                .catch(() => { this.suggestions = []; this.open = false; });
        },
        select(s) {
            this.query = (s.nom + ' ' + s.prenom).trim();
            this.open = false;
            this.$el.closest('form').submit();
        }
    }
}
</script>
@endonce
