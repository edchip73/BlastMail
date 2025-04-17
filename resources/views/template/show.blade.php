<x-layouts.app>
    <x-slot name="header">
        <x-h2>{{ __('Templates') }}</x-h2>
    </x-slot>

    <x-card class="space-y-4 w-1">

    <div class="flex justify-between items-center">
        <div><span class="opacity-70">{{ __('Name') }}:</span> {{ $template->name }}</div>
        <x-link-button secondary :href="route('template.index')">{{ __('Back to list') }}</x-link-button>
    </div>   
    
    <div class="p-20 border-2 border-gray-400 rounded flex justify-center">{!! $template->body !!}</div>

</x-card>
</x-layouts.app>

