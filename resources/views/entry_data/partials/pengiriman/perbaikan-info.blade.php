<div
    class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 md:gap-0 font-semibold text-sm min-h-15.5 shrink-0 p-2">
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-10 w-full md:w-auto">
        <span class="text-xs md:text-sm text-(--blue) font-medium">
            Halaman {{ $perbaikan->currentPage() }}
            dari {{ $perbaikan->lastPage() }}
        </span>

        <h1 class="text-sm md:text-base font-semibold text-(--blue)">
            Stock ROD Ready: {{ $perbaikan->total() }}
        </h1>
    </div>

    <div class="flex items-center gap-3 md:gap-5 self-end md:self-auto">
        <a href="{{ $perbaikan->previousPageUrl() ?? '#' }}"
            class="w-10 h-10 md:w-15 md:h-15 bg-white p-2 md:p-3 border-2 border-gray-300 rounded-lg md:rounded-xl flex items-center justify-center transition
                                {{ $perbaikan->onFirstPage() ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 active:bg-gray-100' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="var(--blue)" class="w-5 h-5 md:w-6 md:h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </a>

        <a href="{{ $perbaikan->nextPageUrl() ?? '#' }}"
            class="w-10 h-10 md:w-15 md:h-15 bg-white p-2 md:p-3 border-2 border-gray-300 rounded-lg md:rounded-xl flex items-center justify-center transition
                                {{ $perbaikan->hasMorePages() ? 'hover:bg-gray-50 active:bg-gray-100' : 'opacity-50 cursor-not-allowed' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="var(--blue)" class="w-5 h-5 md:w-6 md:h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </a>
    </div>
</div>
