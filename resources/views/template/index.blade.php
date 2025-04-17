<x-layouts.app>
    <x-slot name="header">
        <x-h2>{{ __('Templates') }}</x-h2>
    </x-slot>

    <x-card class="space-y-4">

            <div class="flex justify-between">
            <x-link-button :href="route('template.create')">
                {{ __('Create a new template')}}
            </x-link-button>

            <x-form :action="route('template.index')" x-data x-ref="form" class="w-3/5 flex space-x-4 items-center" flat>
                <label for="show_trash" class="inline-flex items-center">
                    <input id="show_trash" type="checkbox" value="1" @click="$refs.form.submit()" @if ($withTrashed) checked @endif
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" 
                    name="withTrashed">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Show Deleted Records') }}</span>
                </label>
                <x-text-input name="search" :placeholder="__('Search')" :value="$search"/>

                
            </x-form>

        </div>

        <x-table :headers="['#', __('Name'), __('Actions')]">

            <x-slot name="body">
                @foreach ($templates as $template)
                <tr>
                    <x-table.td>{{ $template->id }}</x-table.td>
                    <x-table.td>{{$template->name }}</x-table.td>
                   
                    <x-table.td class="flex justify-center space-x-4"> 

                        <x-link-button secondary :href="route('template.show', $template)">{{ __('Preview') }}</x-link-button>
                        <x-link-button secondary :href="route('template.edit', $template)">{{ __('Edit') }}</x-link-button>

                        @unless($template->trashed())

                        <div>
                        <x-form :action="route('template.destroy', $template)" 
                        
                        delete flat onsubmit="return confirm('{{ __('Are you sure?') }}')">

                            <x-secondary-button type="submit">{{ __('Delete') }}</x-secondary-button>
                        </x-form>    
                        </div>
                        @else

                        <span class="rounded-radius w-fit border border-red-700 bg-red-700 px-2 py-1 text-xs font-medium text-on-red-700 dark:border-red-600 dark:bg-red-600 dark:text-on-red-100">Deleted</span>
                        
                        @endunless

                    </x-table.td>
                       
                </tr>

                @endforeach

            </x-slot>

        </x-table>

        {{ $templates->links() }}

      </x-card>
</x-layouts.app>