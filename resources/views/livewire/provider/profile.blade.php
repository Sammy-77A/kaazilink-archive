<div>
    <x-wirekit::section class="bg-[var(--color-wk-bg-subtle)] border-b border-[var(--color-wk-border-subtle)]">
        <x-wirekit::container>
            <div class="py-8">
                <x-wirekit::breadcrumb class="mb-6">
                    <x-wirekit::link href="/" variant="subtle">Home</x-wirekit::link>
                    <x-wirekit::link href="/browse" variant="subtle">Providers</x-wirekit::link>
                    <span class="text-wk-text font-medium">{{ $provider->name }}</span>
                </x-wirekit::breadcrumb>
                
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    <x-wirekit::avatar src="https://ui-avatars.com/api/?name={{ urlencode($provider->name) }}&background=006B3F&color=fff&size=128" size="xl" class="shrink-0" />
                    <div>
                        <h1 class="text-4xl font-heading font-bold text-wk-text mb-2">{{ $provider->name }}</h1>
                        <p class="text-lg text-wk-text-subtle mb-4">Professional Services in Kenya.</p>
                        <div class="flex flex-wrap gap-4 items-center">
                            <x-wirekit::badge intent="warning">★ 4.8 (120 reviews)</x-wirekit::badge>
                            <x-wirekit::badge intent="success">✓ Verified by Kaazilink</x-wirekit::badge>
                            <span class="text-sm font-medium text-wk-accent">From KES 1,500 / visit</span>
                        </div>
                    </div>
                    <div class="md:ml-auto">
                        @auth
                            <x-wirekit::button size="lg" intent="primary" wire:click="bookNow({{ $provider->id }})">Book Now</x-wirekit::button>
                        @else
                            <x-wirekit::button size="lg" intent="primary" href="{{ route('login') }}">Login to Book</x-wirekit::button>
                        @endauth
                    </div>
                </div>
            </div>
        </x-wirekit::container>
    </x-wirekit::section>

    <x-wirekit::section>
        <x-wirekit::container>
            <div class="flex flex-col md:flex-row gap-8">
                <div class="flex-1">
                    <h2 class="text-2xl font-heading font-semibold mb-6">About the Provider</h2>
                    <x-wirekit::prose class="mb-12">
                        <p>Welcome to the profile of {{ $provider->name }}. We offer reliable, fast, and affordable services for both households and businesses.</p>
                    </x-wirekit::prose>

                    <h2 class="text-2xl font-heading font-semibold mb-6">Services Offered</h2>
                    <x-wirekit::grid cols="1 sm:2" class="gap-4">
                        @forelse($services as $service)
                            <x-wirekit::card>
                                <x-wirekit::card.body>
                                    <h3 class="font-heading font-semibold text-lg">{{ $service['name'] }}</h3>
                                    <p class="font-medium text-wk-accent mt-2">{{ $service['price_range'] }}</p>
                                </x-wirekit::card.body>
                            </x-wirekit::card>
                        @empty
                            <p class="text-wk-text-subtle col-span-full">No specific services listed yet.</p>
                        @endforelse
                    </x-wirekit::grid>
                    @if(count($mapMarkers) > 0)
                        <div class="mt-12">
                            <h2 class="text-2xl font-heading font-semibold mb-6">Service Areas</h2>
                            <x-wirekit::map 
                                :markers="$mapMarkers" 
                                styleUrl="https://tiles.openfreemap.org/styles/positron"
                                :zoom="11"
                            />
                        </div>
                    @endif
                </div>

                <div class="w-full md:w-80 shrink-0">
                    <x-wirekit::card>
                        <x-wirekit::card.header>
                            <h3 class="font-heading font-semibold text-lg">Availability</h3>
                        </x-wirekit::card.header>
                        <x-wirekit::card.body>
                            <ul class="space-y-2 text-sm">
                                <li class="flex justify-between"><span>Mon - Fri</span> <span class="font-medium text-wk-text">8:00 AM - 6:00 PM</span></li>
                                <li class="flex justify-between"><span>Saturday</span> <span class="font-medium text-wk-text">9:00 AM - 4:00 PM</span></li>
                                <li class="flex justify-between"><span>Sunday</span> <span class="font-medium text-wk-danger">Closed</span></li>
                            </ul>
                        </x-wirekit::card.body>
                        <x-wirekit::card.footer>
                            <x-wirekit::button intent="neutral" surface="outline" class="w-full">Contact Support</x-wirekit::button>
                        </x-wirekit::card.footer>
                    </x-wirekit::card>
                </div>
            </div>
        </x-wirekit::container>
    </x-wirekit::section>
    
    <x-wirekit::section class="bg-gray-50 py-12 border-t border-gray-200">
        <x-wirekit::container class="max-w-4xl">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-heading font-bold text-gray-900">Reviews</h2>
                @if($canReview)
                    <x-wirekit::button intent="primary" wire:click="leaveReview({{ $bookingIdToReview }})">
                        Leave a Review
                    </x-wirekit::button>
                @endif
            </div>

            @if($reviews->isEmpty())
                <x-wirekit::empty-state 
                    title="No reviews yet" 
                    description="This provider doesn't have any reviews."
                    icon="star"
                />
            @else
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <x-wirekit::card>
                            <x-wirekit::card.body>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="font-medium text-gray-900">{{ $review->client->name }}</div>
                                    <div class="flex items-center text-yellow-500">
                                        @for($i = 0; $i < $review->rating; $i++)
                                            <x-wirekit::icon name="star" class="w-4 h-4" />
                                        @endfor
                                        @for($i = $review->rating; $i < 5; $i++)
                                            <x-wirekit::icon name="star" class="w-4 h-4 text-gray-300" />
                                        @endfor
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 mb-2">{{ $review->created_at->diffForHumans() }}</div>
                                @if($review->comment)
                                    <p class="text-gray-700">{{ $review->comment }}</p>
                                @endif
                            </x-wirekit::card.body>
                        </x-wirekit::card>
                    @endforeach
                </div>
            @endif
        </x-wirekit::container>
    </x-wirekit::section>
    
    <livewire:booking.create-modal />
    <livewire:review.create-modal />
</div>
