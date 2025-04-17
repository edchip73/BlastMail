<x-layouts.app>
    <x-slot name="header">
        <x-h2>{{ __('Email List') }} > {{ $emailList->title }} > {{ __('Subscribers') }} </x-h2>
    </x-slot>

    <x-card class="space-y-4">

            <div class="flex justify-between">
            <x-link-button :href="route('subscribers.create', $emailList)">
                {{ __('Add a new subscribers')}}
            </x-link-button>

            <x-form :action="route('subscribers.index', $emailList)" class="w-2/5" x-data x-ref="form">
                <label for="show_trash" class="inline-flex items-center">
                    <input id="show_trash" type="checkbox"  value="1" @click="$refs.form.submit()" @if ($showTrash) checked @endif
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" 
                    name="showTrash">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Show Deleted Records') }}</span>
                </label>
                <x-text-input name="search" :placeholder="__('Search')" :value="$search"/>

                
            </x-form>

        </div>

        <x-table :headers="['#', __('Name'), __('Email'), __('Actions')]">

            <x-slot name="body">
                @foreach ($subscribers as $subscriber)
                <tr>
                    <x-table.td>{{ $subscriber->id }}</x-table.td>
                    <x-table.td>{{$subscriber->name }}</x-table.td>
                    <x-table.td>{{ $subscriber->email }}</x-table.td>
                    <x-table.td>

                        @unless($subscriber->trashed())

                        <x-form :action="route('subscribers.destroy', [$emailList, $subscriber])" 
                        
                        delete flat onsubmit="return confirm('{{ __('Are you sure?') }}')">

                            <x-secondary-button type="submit">Delete</x-secondary-button>
                        </x-form>    
                        @else
                        <span class="rounded-radius w-fit border border-red-700 bg-red-700 px-2 py-1 text-xs font-medium text-on-red-700 dark:border-red-600 dark:bg-red-600 dark:text-on-red-100">Deleted</span>
                        @endunless
                    </x-table.td>
                       
                </tr>

                @endforeach

            </x-slot>
        </x-table>

        {{ $subscribers->links() }}

      </x-card>
</x-layouts.app>