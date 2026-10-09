<?php

namespace App\Livewire\Payment;

use Livewire\Component;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentManager;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class PayModal extends Component
{
    public ?int $bookingId = null;
    public ?Booking $booking = null;
    public string $phone = '';
    public string $lockedGateway = '';
    public ?Payment $currentPayment = null;
    public ?string $errorMessage = null;
    public bool $isInitiated = false;

    #[On('openPaymentModal')]
    public function openModal(int $bookingId, PaymentManager $paymentManager)
    {
        $this->bookingId = $bookingId;
        $this->booking = Booking::with(['client', 'provider', 'payments'])->findOrFail($bookingId);

        // Security: only the booking's client can initiate payment
        if ($this->booking->client_id !== Auth::id()) {
            abort(403, 'Unauthorized to pay for this booking.');
        }

        // RETRY RULE & GATEWAY LOCK-IN RULE: Resolve gateway
        $this->lockedGateway = $paymentManager->resolveGatewayForBooking($this->booking);

        // Prepopulate phone
        $this->phone = Auth::user()->phone ?? '';
        $this->errorMessage = null;
        $this->isInitiated = false;
        $this->currentPayment = $this->booking->payments()->latest()->first();

        $this->dispatch('wirekit-modal-show', name: 'payment-modal');
    }

    public function initiatePayment(PaymentManager $paymentManager)
    {
        $this->validate([
            'phone' => ['required', 'string', 'regex:/^(?:\+?254|0)[17]\d{8}$/'],
        ], [
            'phone.regex' => 'Please enter a valid Kenyan Safaricom phone number (e.g. 0712345678 or 254712345678).',
        ]);

        if (!$this->booking || empty($this->booking->price)) {
            $this->errorMessage = 'This booking does not have a valid payable amount.';
            return;
        }

        try {
            $this->currentPayment = $paymentManager->initiateBookingPayment($this->booking, $this->phone);

            if ($this->currentPayment->status === 'initiated') {
                $this->isInitiated = true;
                $this->errorMessage = null;
                session()->flash('payment_sent', 'STK Push sent to your phone! Please enter your M-PESA PIN to complete payment.');
            } else {
                $this->errorMessage = $this->currentPayment->error_message ?: 'Failed to initiate M-PESA STK Push.';
            }
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.payment.pay-modal');
    }
}
