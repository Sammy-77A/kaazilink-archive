<div>
    <x-wirekit::modal name="payment-modal" ariaLabel="Pay with M-PESA">
        <x-wirekit::card>
            <x-wirekit::card.header>
                <div class="flex items-center justify-between">
                    <div>
                        <x-wirekit::heading size="lg">M-PESA Payment</x-wirekit::heading>
                        <p class="text-xs text-gray-500 mt-1">Processed securely via {{ ucfirst($lockedGateway) }}</p>
                    </div>
                    @if($booking)
                        <div class="text-right">
                            <span class="text-xs text-gray-500 uppercase tracking-wider block">Total Amount</span>
                            <span class="text-lg font-bold text-green-700">KES {{ number_format($booking->price, 2) }}</span>
                        </div>
                    @endif
                </div>
            </x-wirekit::card.header>

            <x-wirekit::card.body>
                @if(session()->has('payment_sent') || $isInitiated)
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm mb-4">
                        <div class="flex items-center gap-2 font-semibold text-green-900 mb-1">
                            <svg class="w-5 h-5 animate-pulse text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            STK Push Prompt Dispatched!
                        </div>
                        <p>An M-PESA prompt has been sent to <strong>{{ $phone }}</strong>.</p>
                        <p class="mt-1">Please enter your M-PESA PIN on your mobile phone to complete payment of <strong>KES {{ number_format($booking?->price, 2) }}</strong>.</p>
                    </div>
                @endif

                @if($errorMessage)
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm mb-4">
                        <span class="font-semibold block mb-1">Payment Request Failed:</span>
                        {{ $errorMessage }}
                    </div>
                @endif

                <x-wirekit::form wire:submit="initiatePayment" class="space-y-4">
                    <div>
                        <x-wirekit::label for="payment-phone">M-PESA Phone Number</x-wirekit::label>
                        <x-wirekit::input 
                            id="payment-phone"
                            wire:model="phone" 
                            type="tel" 
                            placeholder="0712345678 or 254712345678"
                            required
                        />
                        <p class="text-xs text-gray-500 mt-1">The phone number that will receive the M-PESA PIN prompt.</p>
                        @error('phone') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-gray-50 p-3 rounded-md text-xs text-gray-600 flex justify-between">
                        <span>Gateway Lock-in:</span>
                        <span class="font-mono font-semibold uppercase text-gray-700">{{ $lockedGateway }}</span>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t">
                        <x-wirekit::button type="button" intent="neutral" wire:click="$dispatch('wirekit-modal-close', { name: 'payment-modal' })">
                            {{ $isInitiated ? 'Close' : 'Cancel' }}
                        </x-wirekit::button>
                        <x-wirekit::button type="submit" intent="primary">
                            {{ $currentPayment && $currentPayment->isFailed() ? 'Retry M-PESA Payment' : 'Send M-PESA STK Push' }}
                        </x-wirekit::button>
                    </div>
                </x-wirekit::form>
            </x-wirekit::card.body>
        </x-wirekit::card>
    </x-wirekit::modal>
</div>
