<x-layouts.app>
    <x-slot name="header">
        <x-h2>{{ __('Campaign') }}</x-h2>
    </x-slot>

    <x-card class="space-y-4">

            <div class="flex justify-between">
            <x-link-button :href="route('campaign.create')">
                {{ __('Create a new campaign')}}
            </x-link-button>

            <x-form :action="route('campaign.index')" x-data x-ref="form" class="w-3/5 flex space-x-4 items-center" flat>
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
                @foreach ($campaigns as $campaign)
                <tr>
                    <x-table.td>{{ $campaign->id }}</x-table.td>
                    <x-table.td>{{$campaign->name }}</x-table.td>
                   
                    <x-table.td class="flex justify-center space-x-4"> 

                        @unless($campaign->trashed())

                        <div>
                        <x-form :action="route('campaign.destroy', $campaign)" 
                        
                        delete flat onsubmit="return confirm('{{ __('Are you sure?') }}')">

                            <x-secondary-button type="submit">{{ __('Delete') }}</x-secondary-button>
                        </x-form>    
                        </div>
                        @else
                        <div>
                            <x-form :action="route('campaign.restore', $campaign)" 
                            
                            patch flat onsubmit="return confirm('{{ __('Are you sure?') }}')">
    
                                <x-secondary-button danger type="submit">{{ __('Restore') }}</x-secondary-button>
                            </x-form>    
                            </div>
                                               
                        @endunless

                    </x-table.td>
                       
                </tr>

                @endforeach

            </x-slot>

        </x-table>

        {{ $campaigns->links() }}

      </x-card>
</x-layouts.app>