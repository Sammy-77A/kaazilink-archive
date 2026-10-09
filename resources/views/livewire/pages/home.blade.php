<div>
    <x-wirekit::hero>
        <x-slot:eyebrow><x-wirekit::badge intent="success">Tupate Fundi Haraka</x-wirekit::badge></x-slot:eyebrow>
        <x-slot:title>Pata Kaazi Faster</x-slot:title>
        <x-slot:lede>Connect with vetted local service providers across Kenya. Safe, reliable, and grassroots.</x-slot:lede>
        <x-slot:actions>
            <x-wirekit::button href="/services">Find a Service</x-wirekit::button>
            <x-wirekit::button intent="neutral" surface="outline" href="/how-it-works">How It Works</x-wirekit::button>
        </x-slot:actions>
    </x-wirekit::hero>

    <x-wirekit::section>
        <x-wirekit::container>
            <h2 class="text-3xl font-heading font-semibold text-center mb-12">Popular Service Categories</h2>
            
            <x-wirekit::grid cols="1 md:3 lg:3" class="gap-6">
                <x-wirekit::card>
                    <x-wirekit::card.header>
                        <h3 class="font-heading font-semibold text-xl text-wk-accent">Home Care</h3>
                    </x-wirekit::card.header>
                    <x-wirekit::card.body>
                        <p class="text-wk-text-subtle">Househelp, Nannies, Cleaners, Laundry</p>
                    </x-wirekit::card.body>
                    <x-wirekit::card.footer>
                        <x-wirekit::button href="/services/home-care" size="sm" intent="neutral" surface="outline">View Services</x-wirekit::button>
                    </x-wirekit::card.footer>
                </x-wirekit::card>
                
                <x-wirekit::card>
                    <x-wirekit::card.header>
                        <h3 class="font-heading font-semibold text-xl text-wk-accent">Fundi & Technical</h3>
                    </x-wirekit::card.header>
                    <x-wirekit::card.body>
                        <p class="text-wk-text-subtle">Plumbers, Electricians, Carpenters, Mechanics</p>
                    </x-wirekit::card.body>
                    <x-wirekit::card.footer>
                        <x-wirekit::button href="/services/fundi-technical-repairs" size="sm" intent="neutral" surface="outline">View Services</x-wirekit::button>
                    </x-wirekit::card.footer>
                </x-wirekit::card>

                <x-wirekit::card>
                    <x-wirekit::card.header>
                        <h3 class="font-heading font-semibold text-xl text-wk-accent">Daily Needs</h3>
                    </x-wirekit::card.header>
                    <x-wirekit::card.body>
                        <p class="text-wk-text-subtle">Riders, Water Delivery, Gas Refill</p>
                    </x-wirekit::card.body>
                    <x-wirekit::card.footer>
                        <x-wirekit::button href="/services/daily-needs-emergency-response" size="sm" intent="neutral" surface="outline">View Services</x-wirekit::button>
                    </x-wirekit::card.footer>
                </x-wirekit::card>
            </x-wirekit::grid>
            
            <div class="mt-12 text-center">
                <x-wirekit::button href="/services" surface="outline">View All 51 Services</x-wirekit::button>
            </div>
        </x-wirekit::container>
    </x-wirekit::section>
</div>
