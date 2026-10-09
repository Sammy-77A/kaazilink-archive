<div>
    <x-wirekit::section class="bg-[var(--color-wk-bg-subtle)] border-b border-[var(--color-wk-border-subtle)]">
        <x-wirekit::container>
            <div class="py-8">
                <x-wirekit::breadcrumb class="mb-6">
                    <x-wirekit::link href="/" variant="subtle">Home</x-wirekit::link>
                    <x-wirekit::link href="/browse" variant="subtle">Services</x-wirekit::link>
                    <span class="text-wk-text font-medium">{{ str($slug)->headline() }}</span>
                </x-wirekit::breadcrumb>
                
                <h1 class="text-4xl font-heading font-bold text-wk-text mb-4">{{ str($slug)->headline() }} Providers</h1>
                <p class="text-lg text-wk-text-subtle">Found 12 vetted professionals near you.</p>
            </div>
        </x-wirekit::container>
    </x-wirekit::section>

    <x-wirekit::section>
        <x-wirekit::container>
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Filters Sidebar -->
                <div class="w-full md:w-64 shrink-0">
                    <x-wirekit::card>
                        <x-wirekit::card.header>
                            <h3 class="font-heading font-semibold text-lg text-wk-accent">Filters</h3>
                        </x-wirekit::card.header>
                        <x-wirekit::card.body>
                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-sm font-medium text-wk-text mb-2">Location</h4>
                                    <x-wirekit::select name="location">
                                        <option>Nairobi</option>
                                        <option>Mombasa</option>
                                        <option>Kisumu</option>
                                        <option>Nakuru</option>
                                    </x-wirekit::select>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-wk-text mb-2">Price Range</h4>
                                    <div class="space-y-2">
                                        <x-wirekit::checkbox name="price" value="low">Under KES 1,000</x-wirekit::checkbox>
                                        <x-wirekit::checkbox name="price" value="med">KES 1,000 - 3,000</x-wirekit::checkbox>
                                        <x-wirekit::checkbox name="price" value="high">Over KES 3,000</x-wirekit::checkbox>
                                    </div>
                                </div>
                            </div>
                        </x-wirekit::card.body>
                        <x-wirekit::card.footer>
                            <x-wirekit::button size="sm" class="w-full">Apply Filters</x-wirekit::button>
                        </x-wirekit::card.footer>
                    </x-wirekit::card>
                </div>

                <!-- Provider List -->
                <div class="flex-1">
                    <x-wirekit::grid cols="1 lg:2" class="gap-6">
                        <!-- Provider 1 -->
                        <x-wirekit::card>
                            <x-wirekit::card.body class="flex gap-4">
                                <x-wirekit::avatar src="https://ui-avatars.com/api/?name=Ochieng+P&background=006B3F&color=fff" size="lg" />
                                <div>
                                    <h3 class="font-heading font-semibold text-lg">Ochieng's Plumbing Services</h3>
                                    <p class="text-sm text-wk-text-subtle mb-2">Kibera, Nairobi</p>
                                    <x-wirekit::badge intent="warning" class="mb-2">★ 4.8 (120 reviews)</x-wirekit::badge>
                                    <p class="text-sm font-medium text-wk-accent">From KES 1,500 / visit</p>
                                </div>
                            </x-wirekit::card.body>
                            <x-wirekit::card.footer class="flex justify-between">
                                <x-wirekit::button href="/provider/ochiengs-plumbing" size="sm" intent="neutral" surface="outline">View Profile</x-wirekit::button>
                                <x-wirekit::button size="sm">Book Now</x-wirekit::button>
                            </x-wirekit::card.footer>
                        </x-wirekit::card>

                        <!-- Provider 2 -->
                        <x-wirekit::card>
                            <x-wirekit::card.body class="flex gap-4">
                                <x-wirekit::avatar src="https://ui-avatars.com/api/?name=Mama+F&background=C0392B&color=fff" size="lg" />
                                <div>
                                    <h3 class="font-heading font-semibold text-lg">Mama Fua - Lilian</h3>
                                    <p class="text-sm text-wk-text-subtle">Kileleshwa, Nairobi</p>
                                    <x-wirekit::badge intent="warning" class="mb-2">★ 4.9 (340 reviews)</x-wirekit::badge>
                                    <p class="text-sm font-medium text-wk-accent">From KES 800 / day</p>
                                </div>
                            </x-wirekit::card.body>
                            <x-wirekit::card.footer class="flex justify-between">
                                <x-wirekit::button href="/provider/mama-fua-lilian" size="sm" intent="neutral" surface="outline">View Profile</x-wirekit::button>
                                <x-wirekit::button size="sm">Book Now</x-wirekit::button>
                            </x-wirekit::card.footer>
                        </x-wirekit::card>

                        <!-- Provider 3 -->
                        <x-wirekit::card>
                            <x-wirekit::card.body class="flex gap-4">
                                <x-wirekit::avatar src="https://ui-avatars.com/api/?name=Kamau+E&background=E8A317&color=fff" size="lg" />
                                <div>
                                    <h3 class="font-heading font-semibold text-lg">Kamau Electricals</h3>
                                    <p class="text-sm text-wk-text-subtle">Thika Road, Nairobi</p>
                                    <x-wirekit::badge intent="warning" class="mb-2">★ 4.5 (89 reviews)</x-wirekit::badge>
                                    <p class="text-sm font-medium text-wk-accent">From KES 2,000 / fix</p>
                                </div>
                            </x-wirekit::card.body>
                            <x-wirekit::card.footer class="flex justify-between">
                                <x-wirekit::button href="/provider/kamau-electricals" size="sm" intent="neutral" surface="outline">View Profile</x-wirekit::button>
                                <x-wirekit::button size="sm">Book Now</x-wirekit::button>
                            </x-wirekit::card.footer>
                        </x-wirekit::card>
                        
                        <!-- Provider 4 -->
                        <x-wirekit::card>
                            <x-wirekit::card.body class="flex gap-4">
                                <x-wirekit::avatar src="https://ui-avatars.com/api/?name=Amina+S&background=1F2937&color=fff" size="lg" />
                                <div>
                                    <h3 class="font-heading font-semibold text-lg">Amina Salon & Spa</h3>
                                    <p class="text-sm text-wk-text-subtle">Eastleigh, Nairobi</p>
                                    <x-wirekit::badge intent="warning" class="mb-2">★ 4.7 (210 reviews)</x-wirekit::badge>
                                    <p class="text-sm font-medium text-wk-accent">From KES 1,200 / style</p>
                                </div>
                            </x-wirekit::card.body>
                            <x-wirekit::card.footer class="flex justify-between">
                                <x-wirekit::button href="/provider/amina-salon" size="sm" intent="neutral" surface="outline">View Profile</x-wirekit::button>
                                <x-wirekit::button size="sm">Book Now</x-wirekit::button>
                            </x-wirekit::card.footer>
                        </x-wirekit::card>
                    </x-wirekit::grid>

                    <div class="mt-8">
                        <x-wirekit::pagination />
                    </div>
                </div>
            </div>
        </x-wirekit::container>
    </x-wirekit::section>
</div>
