<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'client_id',
        'provider_id',
        'service_slug',
        'status',
        'scheduled_at',
    ];

    /**
     * Relationship to Reschedule History
     */
    public function reschedules()
    {
        return $this->hasMany(BookingReschedule::class);
    }

    // ==========================================
    // Phase 1 Sockets: Booking State Transitions
    // ==========================================

    public function accept()
    {
        // TODO: (Phase 2) Implement state transition logic, validations, and trigger Email Notifications / SMS
    }

    public function reject()
    {
        // TODO: (Phase 2) Implement state transition logic, notifications, and refund escrow if applicable
    }

    public function markInProgress()
    {
        // TODO: (Phase 2) Implement state transition logic when provider arrives/starts job
    }

    public function complete()
    {
        // TODO: (Phase 2) Implement state transition, trigger Payment Percentage Deduction, request Comments and Reviews
    }

    public function cancelByClient()
    {
        // TODO: (Phase 2) Implement logic for client cancellation, penalties, and refund escrow processing
    }

    public function cancelByProvider()
    {
        // TODO: (Phase 2) Implement logic for provider cancellation and full refund to client
    }

    public function markNoShowClient()
    {
        // TODO: (Phase 2) Implement client no-show penalty deductions
    }

    public function markNoShowProvider()
    {
        // TODO: (Phase 2) Implement provider no-show penalties and client refunds
    }

    public function dispute()
    {
        // TODO: (Phase 2) Implement dispute logic, freezing escrow payouts, alerting admins
    }

    public function resolve()
    {
        // TODO: (Phase 2) Implement dispute resolution and final payout logic
    }

    public function refund()
    {
        // TODO: (Phase 2) Implement manual or automatic refund via payment gateway (Daraja STK/M-PESA)
    }

    public function reschedule($newScheduledAt, $requestedBy, $reason = null)
    {
        // TODO: (Phase 2) Implement reschedule logic, create BookingReschedule log entry, notify other party
    }
}
