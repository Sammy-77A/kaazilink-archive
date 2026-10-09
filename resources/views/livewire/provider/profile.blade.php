<div>
    <x-wirekit::section class="bg-[var(--color-wk-bg-subtle)] border-b border-[var(--color-wk-border-subtle)]">
        <x-wirekit::container>
            <div class="py-8">
                <x-wirekit::breadcrumb class="mb-6">
                    <x-wirekit::link href="/" variant="subtle">Home</x-wirekit::link>
                    <x-wirekit::link href="/browse" variant="subtle">Providers</x-wirekit::link>
                    <span class="text-wk-text font-medium">{{ str($slug)->headline() }}</span>
                </x-wirekit::breadcrumb>
                
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    <x-wirekit::avatar src="https://ui-avatars.com/api/?name=Ochieng+P&background=006B3F&color=fff&size=128" size="xl" class="shrink-0" />
                    <div>
                        <h1 class="text-4xl font-heading font-bold text-wk-text mb-2">{{ str($slug)->headline() }}</h1>
                        <p class="text-lg text-wk-text-subtle mb-4">Professional Plumbing & Water Systems in Kibera, Nairobi.</p>
                        <div class="flex flex-wrap gap-4 items-center">
                            <x-wirekit::badge intent="warning">★ 4.8 (120 reviews)</x-wirekit::badge>
                            <x-wirekit::badge intent="success">✓ Verified by Kaazilink</x-wirekit::badge>
                            <span class="text-sm font-medium text-wk-accent">From KES 1,500 / visit</span>
                        </div>
                    </div>
                    <div class="md:ml-auto">
                        <x-wirekit::button size="lg" intent="accent">Book Now</x-wirekit::button>
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
                        <p>With over 10 years of experience, Ochieng's Plumbing Services offers reliable, fast, and affordable plumbing repairs for both households and businesses. We specialize in fixing leaks, unblocking drainage systems, and installing water tanks.</p>
                    </x-wirekit::prose>

                    <h2 class="text-2xl font-heading font-semibold mb-6">Services Offered</h2>
                    <x-wirekit::grid cols="1 sm:2" class="gap-4">
                        <x-wirekit::card>
                            <x-wirekit::card.body>
                                <h3 class="font-heading font-semibold text-lg">General Pipe Repair</h3>
                                <p class="text-sm text-wk-text-subtle">Fixing broken or leaking pipes inside or outside the house.</p>
                                <p class="font-medium text-wk-accent mt-2">KES 1,500</p>
                            </x-wirekit::card.body>
                        </x-wirekit::card>
                        <x-wirekit::card>
                            <x-wirekit::card.body>
                                <h3 class="font-heading font-semibold text-lg">Water Tank Installation</h3>
                                <p class="text-sm text-wk-text-subtle">Installing and connecting water tanks to the main supply line.</p>
                                <p class="font-medium text-wk-accent mt-2">KES 4,500</p>
                            </x-wirekit::card.body>
                        </x-wirekit::card>
                    </x-wirekit::grid>
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
</div>
