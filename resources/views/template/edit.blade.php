<x-layouts.app>
    <x-slot name="header">
        <x-h2>{{ __('Templates')}} > {{ $template->name }} > {{ __('Update')}}</x-h2>
    </x-slot>
    <x-card>
        <x-form :action="route('template.update', $template)" put >
        @csrf
           
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" name="name" :value="old('name', $template->name)" autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />

                </div>

                <div>
                    <x-input-label for="body" :value="__('Body')" />
                    <x-input.richtext name="body" :value="old('body', $template->body)" />
                    <x-input-error :messages="$errors->get('body')" class="mt-2" />

                </div>
            

            <div class="flex items-center space-x-4">
                <x-link-button secondary :href="route('template.index', $template)">
                    {{ __('Cancel') }}
                </x-link-button>
                

                <x-button type="submit">
                    {{__('Save')}}
                </x-button>
            </div>
        </x-form>
    </x-card>
</x-layouts.app>