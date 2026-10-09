<div>
    <x-wirekit::section class="bg-gray-50 py-12">
        <x-wirekit::container class="max-w-4xl">
            <h1 class="text-3xl font-heading font-bold mb-8">My Bookings</h1>

            @if(session()->has('success'))
                <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($bookings->isEmpty())
                <x-wirekit::empty-state 
                    title="No bookings yet" 
                    description="You haven't made or received any bookings."
                    icon="calendar"
                />
            @else
                <div class="space-y-4">
                    @foreach($bookings as $booking)
                        <x-wirekit::card>
                            <x-wirekit::card.body class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <x-wirekit::badge intent="{{ $booking->status === 'completed' ? 'success' : 'neutral' }}">
                                            {{ ucfirst($booking->status) }}
                                        </x-wirekit::badge>
                                        <span class="text-sm text-gray-500">{{ $booking->created_at->format('M j, Y g:i A') }}</span>
                                    </div>
                                    <h3 class="font-bold text-lg text-gray-900">{{ $booking->service_slug }}</h3>
                                    
                                    <div class="text-sm text-gray-600 mt-1">
                                        @if(Auth::id() === $booking->client_id)
                                            Provider: <a href="{{ route('provider.profile', $booking->provider_id) }}" class="text-blue-600 hover:underline">{{ $booking->provider->name }}</a>
                                        @else
                                            Client: <span class="font-medium">{{ $booking->client->name }}</span>
                                        @endif
                                    </div>
                                    
                                    @if($booking->scheduled_at)
                                        <div class="text-sm text-gray-600 mt-1">
                                            Scheduled for: <span class="font-medium">{{ \Carbon\Carbon::parse($booking->scheduled_at)->format('M j, Y g:i A') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="shrink-0 flex items-center gap-2">
                                    @if(in_array($booking->status, ['requested', 'accepted']))
                                        <x-wirekit::button wire:click="markCompleted({{ $booking->id }})" intent="success" size="sm">
                                            Mark Completed
                                        </x-wirekit::button>
                                    @endif

                                    @if($booking->status === 'completed' && Auth::id() === $booking->client_id && !$booking->review()->exists())
                                        <x-wirekit::button wire:click="leaveReview({{ $booking->id }})" intent="primary" size="sm">
                                            Leave Review
                                        </x-wirekit::button>
                                    @endif
                                </div>
                            </x-wirekit::card.body>
                        </x-wirekit::card>
                    @endforeach
                </div>
            @endif
        </x-wirekit::container>
    </x-wirekit::section>

    <livewire:review.create-modal />
</div>
