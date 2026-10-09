<div>
    <x-wirekit::modal wire:model="isOpen" ariaLabel="Book Provider">
        <x-wirekit::card>
            <x-wirekit::card.header>
                <x-wirekit::heading size="lg">Book {{ $provider ? $provider->name : 'Provider' }}</x-wirekit::heading>
                <x-wirekit::text variant="muted">Request a service.</x-wirekit::text>
            </x-wirekit::card.header>

            <x-wirekit::card.body>
                @if(session()->has('success'))
                    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                <x-wirekit::form wire:submit="submit" class="space-y-4">
                    <x-wirekit::combobox
                        name="serviceSlug"
                        label="Service"
                        placeholder="Select a service..."
                        wire:model="serviceSlug"
                        :options="$servicesOptions"
                    />

                    <x-wirekit::combobox
                        name="serviceAreaId"
                        label="Area"
                        placeholder="Select an area..."
                        wire:model="serviceAreaId"
                        :options="$areasOptions"
                    />

                    <x-wirekit::input 
                        type="datetime-local" 
                        name="scheduledAt" 
                        label="Scheduled Time (Optional)" 
                        wire:model="scheduledAt" 
                    />

                    <div class="flex justify-end gap-2 pt-4">
                        <x-wirekit::button type="button" intent="neutral" wire:click="$set('isOpen', false)">Cancel</x-wirekit::button>
                        <x-wirekit::button type="submit" intent="primary">Request Booking</x-wirekit::button>
                    </div>
                </x-wirekit::form>
            </x-wirekit::card.body>
        </x-wirekit::card>
    </x-wirekit::modal>
</div>
