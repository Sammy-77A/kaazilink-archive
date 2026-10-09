<div>
    <x-wirekit::section class="bg-[var(--color-wk-bg-subtle)] border-b border-[var(--color-wk-border-subtle)]">
        <x-wirekit::container>
            <div class="py-12 text-center">
                <h1 class="text-4xl font-heading font-bold text-wk-text mb-4">Service Directory</h1>
                <p class="text-lg text-wk-text-subtle max-w-2xl mx-auto">Browse our 51 vetted services across 6 categories to find the right professional for your needs.</p>
            </div>
        </x-wirekit::container>
    </x-wirekit::section>

    <x-wirekit::section>
        <x-wirekit::container>
            <x-wirekit::grid cols="1 md:2 lg:3" class="gap-8">
                <!-- Home Care -->
                <x-wirekit::card>
                    <x-wirekit::card.header>
                        <h3 class="font-heading font-semibold text-xl text-wk-accent">Home Care</h3>
                    </x-wirekit::card.header>
                    <x-wirekit::card.body>
                        <ul class="space-y-2 text-sm text-wk-text-subtle">
                            <li><x-wirekit::link href="/services/househelp-nanny">Househelp / Nanny</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/cleaner">Cleaner</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/laundry-mama-fua">Laundry (Mama Fua)</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/chef-cook">Chef / Cook</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/caregiver-elderly">Caregiver (Elderly)</x-wirekit::link></li>
                        </ul>
                    </x-wirekit::card.body>
                    <x-wirekit::card.footer>
                        <x-wirekit::button href="/services/home-care" size="sm" intent="neutral" surface="outline" class="w-full">Explore Category</x-wirekit::button>
                    </x-wirekit::card.footer>
                </x-wirekit::card>

                <!-- Fundi & Technical Repairs -->
                <x-wirekit::card>
                    <x-wirekit::card.header>
                        <h3 class="font-heading font-semibold text-xl text-wk-accent">Fundi & Technical</h3>
                    </x-wirekit::card.header>
                    <x-wirekit::card.body>
                        <ul class="space-y-2 text-sm text-wk-text-subtle">
                            <li><x-wirekit::link href="/services/plumber">Plumber</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/electrician">Electrician</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/carpenter">Carpenter</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/mason-builder">Mason / Builder</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/painter">Painter</x-wirekit::link></li>
                        </ul>
                    </x-wirekit::card.body>
                    <x-wirekit::card.footer>
                        <x-wirekit::button href="/services/fundi-technical-repairs" size="sm" intent="neutral" surface="outline" class="w-full">Explore Category</x-wirekit::button>
                    </x-wirekit::card.footer>
                </x-wirekit::card>

                <!-- Daily Needs & Emergency Response -->
                <x-wirekit::card>
                    <x-wirekit::card.header>
                        <h3 class="font-heading font-semibold text-xl text-wk-accent">Daily Needs & Emergency</h3>
                    </x-wirekit::card.header>
                    <x-wirekit::card.body>
                        <ul class="space-y-2 text-sm text-wk-text-subtle">
                            <li><x-wirekit::link href="/services/boda-boda-rider">Boda Boda Rider</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/tuk-tuk-driver">Tuk Tuk Driver</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/water-vendor">Water Vendor</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/gas-delivery">Gas Delivery</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/garbage-collection">Garbage Collection</x-wirekit::link></li>
                        </ul>
                    </x-wirekit::card.body>
                    <x-wirekit::card.footer>
                        <x-wirekit::button href="/services/daily-needs-emergency-response" size="sm" intent="neutral" surface="outline" class="w-full">Explore Category</x-wirekit::button>
                    </x-wirekit::card.footer>
                </x-wirekit::card>

                <!-- Beauty, Events & Social Support -->
                <x-wirekit::card>
                    <x-wirekit::card.header>
                        <h3 class="font-heading font-semibold text-xl text-wk-accent">Beauty & Events</h3>
                    </x-wirekit::card.header>
                    <x-wirekit::card.body>
                        <ul class="space-y-2 text-sm text-wk-text-subtle">
                            <li><x-wirekit::link href="/services/hairdresser-salonist">Hairdresser / Salonist</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/barber">Barber</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/makeup-artist">Makeup Artist</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/mc-hype-man">MC / Hype Man</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/dj">DJ</x-wirekit::link></li>
                        </ul>
                    </x-wirekit::card.body>
                    <x-wirekit::card.footer>
                        <x-wirekit::button href="/services/beauty-events-social-support" size="sm" intent="neutral" surface="outline" class="w-full">Explore Category</x-wirekit::button>
                    </x-wirekit::card.footer>
                </x-wirekit::card>

                <!-- Agriculture, Rural & Farming -->
                <x-wirekit::card>
                    <x-wirekit::card.header>
                        <h3 class="font-heading font-semibold text-xl text-wk-accent">Agriculture & Farming</h3>
                    </x-wirekit::card.header>
                    <x-wirekit::card.body>
                        <ul class="space-y-2 text-sm text-wk-text-subtle">
                            <li><x-wirekit::link href="/services/farm-hand">Farm Hand (Kibarua)</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/tractor-operator">Tractor Operator</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/veterinary-officer">Veterinary Officer</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/agronomist">Agronomist</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/produce-transporter">Produce Transporter</x-wirekit::link></li>
                        </ul>
                    </x-wirekit::card.body>
                    <x-wirekit::card.footer>
                        <x-wirekit::button href="/services/agriculture-rural-farming" size="sm" intent="neutral" surface="outline" class="w-full">Explore Category</x-wirekit::button>
                    </x-wirekit::card.footer>
                </x-wirekit::card>

                <!-- Business, Cyber, Education & Health -->
                <x-wirekit::card>
                    <x-wirekit::card.header>
                        <h3 class="font-heading font-semibold text-xl text-wk-accent">Business & Education</h3>
                    </x-wirekit::card.header>
                    <x-wirekit::card.body>
                        <ul class="space-y-2 text-sm text-wk-text-subtle">
                            <li><x-wirekit::link href="/services/tutor">Tutor (Primary/Secondary)</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/cybercafe-attendant">Cybercafe Attendant</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/graphic-designer">Graphic Designer</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/photographer">Photographer</x-wirekit::link></li>
                            <li><x-wirekit::link href="/services/translator">Translator</x-wirekit::link></li>
                        </ul>
                    </x-wirekit::card.body>
                    <x-wirekit::card.footer>
                        <x-wirekit::button href="/services/business-cyber-education-health" size="sm" intent="neutral" surface="outline" class="w-full">Explore Category</x-wirekit::button>
                    </x-wirekit::card.footer>
                </x-wirekit::card>
            </x-wirekit::grid>
        </x-wirekit::container>
    </x-wirekit::section>
</div>
