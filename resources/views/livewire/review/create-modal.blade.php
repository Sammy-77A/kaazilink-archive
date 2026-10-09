<div>
    <x-wirekit::modal wire:model="isOpen" ariaLabel="Leave a Review">
        <x-wirekit::card>
            <x-wirekit::card.header>
                <x-wirekit::heading size="lg">Leave a Review</x-wirekit::heading>
                <x-wirekit::text variant="muted">Rate your experience with {{ $booking ? $booking->provider->name : 'the provider' }}.</x-wirekit::text>
            </x-wirekit::card.header>

            <x-wirekit::card.body>
                @if(session()->has('error'))
                    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif
                <x-wirekit::form wire:submit="submit" class="space-y-4">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                        <select wire:model="rating" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3">3 - Average</option>
                            <option value="2">2 - Poor</option>
                            <option value="1">1 - Terrible</option>
                        </select>
                        @error('rating') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <x-wirekit::textarea 
                        name="comment" 
                        label="Comment (Optional)" 
                        placeholder="Share details of your own experience..."
                        wire:model="comment" 
                        rows="4"
                    />

                    <div class="flex justify-end gap-2 pt-4">
                        <x-wirekit::button type="button" intent="neutral" wire:click="$set('isOpen', false)">Cancel</x-wirekit::button>
                        <x-wirekit::button type="submit" intent="primary">Submit Review</x-wirekit::button>
                    </div>
                </x-wirekit::form>
            </x-wirekit::card.body>
        </x-wirekit::card>
    </x-wirekit::modal>
</div>
