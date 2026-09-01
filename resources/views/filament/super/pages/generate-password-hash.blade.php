<x-filament-panels::page>
    <x-filament::section>
        <form wire:submit="generate" class="space-y-4">
            {{ $this->form }}

            <x-filament::button type="submit" icon="heroicon-o-key" style="margin-top: 1rem;">
                Generate Hash
            </x-filament::button>
        </form>
    </x-filament::section>

    @if($hashedPassword)
        <x-filament::section class="mt-6">
            <x-slot name="heading">
                Hashed Password
            </x-slot>

            <div
                x-data="{ copied: false }"
                @click="
                    navigator.clipboard.writeText('{{ $hashedPassword }}');
                    copied = true;
                    setTimeout(() => copied = false, 1500);
                "
                class="group relative flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 font-mono text-sm cursor-pointer transition hover:border-primary-400 dark:border-white/10 dark:bg-white/5"
            >
                <code class="flex-1 break-all select-all text-gray-700 dark:text-gray-200">{{ $hashedPassword }}</code>

                <x-filament::icon
                    x-show="!copied"
                    icon="heroicon-o-clipboard-document"
                    class="h-5 w-5 shrink-0 text-gray-400 transition group-hover:text-primary-500"
                />
                <x-filament::icon
                    x-show="copied"
                    icon="heroicon-o-check"
                    class="h-5 w-5 shrink-0 text-success-500"
                    x-cloak
                />
            </div>

            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                Click anywhere on the box to copy.
            </p>
        </x-filament::section>
    @endif
</x-filament-panels::page>
