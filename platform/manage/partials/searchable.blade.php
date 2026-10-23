{{-- -------------------
     Global search panel. Opens on the slash key or from the topbar and
     searches contacts and call history.
------------------- --}}
<div x-data="{
        open: false,
        query: '',
        results: [],
        searching: false,
        async run() {
            if (this.query.trim().length < 2) { this.results = []; return; }
            this.searching = true;
            try {
                const res = await fetch('{{ route('manage.ajax.search') }}?q=' + encodeURIComponent(this.query));
                this.results = await res.json();
            } catch (e) { this.results = []; }
            this.searching = false;
        }
     }"
     @open-search.window="open = true; $nextTick(() => $refs.box.focus())"
     @keydown.window.slash.prevent="if (!['INPUT','TEXTAREA'].includes(document.activeElement.tagName)) { open = true; $nextTick(() => $refs.box.focus()); }"
     @keydown.escape.window="open = false"
     x-show="open" x-cloak
     class="fixed inset-0 z-[9998] bg-overlay/70 flex items-start justify-center pt-24 px-4">

    <div class="w-full max-w-2xl bg-surface border-t-4 border-primary rounded-card overflow-hidden" @click.outside="open = false">

        <div class="flex items-center gap-3 px-4 border-b border-line">
            <i class="fas fa-magnifying-glass text-ink-muted"></i>
            <input x-ref="box" type="search" x-model="query" @input.debounce.300ms="run()"
                   placeholder="Contact name, phone number or company"
                   class="flex-1 h-14 border-0 focus:ring-0 focus:outline-none text-sm">
            <button @click="open = false" class="text-ink-muted hover:text-primary" aria-label="Close">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <div class="max-h-96 overflow-y-auto">

            <p x-show="searching" x-cloak class="px-4 py-6 text-center text-sm text-ink-muted">Searching</p>

            <p x-show="!searching && query.length > 1 && results.length === 0" x-cloak
               class="px-4 py-8 text-center text-sm text-ink-muted">Nothing found for that.</p>

            <template x-for="(row, i) in results" :key="i">
                <a :href="row.url" class="flex items-center gap-3 px-4 py-3 border-b border-line hover:bg-steel transition">
                    <span class="nm-badge nm-badge--outline shrink-0" x-text="row.group"></span>
                    <span class="flex-1 min-w-0">
                        <span class="block text-sm font-semibold text-ink truncate" x-text="row.title"></span>
                        <span class="block text-xs text-ink-muted" x-text="row.meta"></span>
                    </span>
                    <i class="fas fa-arrow-right text-xs text-ink-muted"></i>
                </a>
            </template>

        </div>
    </div>
</div>
