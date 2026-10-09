# Kaazilink — Agent Context File
_Place this file in the project root. Read it before every task. Update it after every task._

---

## Project Identity

Kaazilink is a Kenyan community skill/job/services marketplace connecting households with vetted local service providers (fundis, house help, caregivers, technicians, event vendors, agricultural workers, etc.) across 51 service categories grouped into 6 categories: Home Care, Fundi & Technical Repairs, Daily Needs & Emergency Response, Beauty/Events/Social Support, Agriculture/Rural/Farming, Business/Cyber/Education/Health.

Brand voice: grassroots, dignified, trust-first, Kenyan-market ("Tupate Fundi Haraka" / "Pata Kaazi Faster"). Avoid generic corporate SaaS visual language — this must not look like a templated AI-generated site.

## Stack

- **Backend:** Laravel
- **Frontend/reactivity:** Livewire
- Styling/framework choices are to be decided per-prompt; do not default to unstyled Bootstrap or generic Tailwind starter templates without deliberate design direction.

## Development Model — TWO PHASES

### Phase 1 — "The Sockets" (CURRENT PHASE)
Build the complete UI, page scaffolding, and routing. The site must look and feel finished to a visitor. **No backend logic, no database-driven behavior, no working features** are implemented in this phase — with one exception: where a future feature will need a structural "socket" to plug into (e.g., a reserved Livewire component method left empty, a named broadcast channel not yet wired to anything, a placeholder route), build that socket now so Phase 2 doesn't require re-scaffolding.

### Phase 2 — "The Appliances" (NOT STARTED)
Features are implemented one at a time from the roadmap below, each fully tested before the next begins, then wired into the existing Phase 1 UI.

## Current Route Map (Phase 1)

The following UI routes are currently active and using WireKit component-driven Livewire pages:
- `/` — Home page
- `/how-it-works` — How It Works (user & provider guide)
- `/services` — Browse Service Categories (Directory) 
- `/services/{slug}` — Individual Service Category (listing of providers in that category)
- `/provider/{slug}` — Individual Provider Profile

## Booking State Machine (Phase 1 Socket)

The core booking lifecycle uses the `Booking` model, tracking jobs through the following exact status enum:
`requested`, `accepted`, `rejected`, `in_progress`, `completed`, `cancelled_by_client`, `cancelled_by_provider`, `no_show_client`, `no_show_provider`, `disputed`, `resolved`, `refunded`.

A `BookingReschedule` model tracks the log of any edits to the `scheduled_at` field, storing original time, new time, requesting party, and reason.

**Transition Stub Mapping (Waiting on Phase 2 Features):**
- `accept()` — Feature 7 (Email/SMS Notifications)
- `reject()` — Feature 7 (Email/SMS Notifications)
- `markInProgress()` — Feature 4 (Location/Tracking - future usage)
- `complete()` — Feature 6 (Reviews) & Feature 9 (Payment Percentage Deduction)
- `cancelByClient()` — (Escrow refund logic)
- `cancelByProvider()` — (Escrow refund logic)
- `markNoShowClient()` — (Penalty logic)
- `markNoShowProvider()` — (Penalty logic)
- `dispute()` — (Admin dispute resolution panel)
- `resolve()` — (Escrow payout logic)
- `refund()` — Feature 8 (Daraja STK / M-PESA Reversals)
- `reschedule()` — Feature 7 (Email/SMS Notifications)

*Note: Per Phase 1 rules, the Booking model and its state transitions are strictly scaffolded as non-functional sockets and are NOT wired into any live UI.*

## Phase 2 Feature Roadmap (build order, for future reference — do not implement during Phase 1)

1. SMS Mobile Verification (signup)
2. Email Verification (signup)
3. KYC — Manual Verification (admin panel for ID/chief-letter/document review)
4. Location Detection Logic (geo-matching clients to nearest providers)
5. Google Maps API Integration (map display, provider location, navigation)
6. Comments and Reviews (star ratings on completed jobs)
7. Email Notifications (booking/confirmation/reminder alerts)
8. Daraja STK Integration (M-PESA Lipa Na M-PESA Online push payments)
9. Payment Percentage Deduction Logic (10–15% commission per completed transaction)
10. TIPS — 2.5% Deduction (optional gratuity)
11. Referral Points System Logic (redeemable referral rewards)
12. Google Ad Revenue Integration (AdSense/Ad Manager monetization)

## Explicitly Out of Scope (do not build, do not scaffold placeholders for)

These appear in founder materials but are NOT part of the current technical scope. Do not create database tables, routes, or UI for these unless a future prompt explicitly reintroduces one:

- WhatsApp manual-dispatcher workflow
- Advance payment / escrow to providers
- Recurring provider listing-fee subscriptions
- Featured/sponsored advertising placements (distinct from Google AdSense)
- Corporate/SACCO retainer contracts
- Emergency blood donor database
- Community workshops/seminars module

## Open Architectural Decision

The core booking/job lifecycle (client requests a service → provider accepts → job executes → job marked complete) has **not yet been formally specified**. Reviews, notifications, and payment deduction all depend on this state machine existing. Flag this to the PM/user the first time a prompt requires booking-state logic, rather than inventing an ad hoc structure.

## Version Control Policy — MANDATORY, EVERY ACTION

**You must commit and push to GitHub after every single completed action or task. This is not optional and does not wait for a milestone — one action, one commit+push.**

Before every commit, actively scan changed files for secrets — do not rely on `.gitignore` alone, since a new file can be created that doesn't match an existing pattern. If a file contains any of the following, **exclude that file from the commit** (keep it local/untracked) and flag it explicitly in your response to the user rather than silently omitting it:

### Never commit or push:
1. Files containing API keys or secrets of any kind (Daraja/M-PESA consumer key & secret, Google Maps API key, AdSense credentials, mail provider keys, etc.)
2. Test files — these carry looser restrictions than production code; keep them local or in a gitignored test-only path
3. Anything a secret-scanner (e.g. GitGuardian) would flag, including:
   - `.env` and any environment-variable files
   - Private keys — SSH, PGP, TLS/SSL certificates and their key files
   - Database connection strings/credentials (`DB_PASSWORD`, DSNs with embedded credentials)
   - Cloud provider credentials (AWS access key/secret, GCP service-account JSON, Azure credentials)
   - OAuth client secrets and OAuth tokens
   - JWT signing secrets
   - Generic high-entropy strings hardcoded in source that resemble tokens/passwords
   - Third-party API keys (Stripe, Twilio, SendGrid, Mailgun, Firebase config/service-account keys, Safaricom Daraja keys)
   - Webhook URLs with embedded secrets (Slack, Discord, or any incoming webhook)
   - Package registry tokens (npm, Composer/Packagist, PyPI)
   - Git/GitHub/GitLab personal access tokens
   - Hardcoded passwords/credentials anywhere in code, config, seeders, or migrations
   - Private encryption keys or keystores
4. `node_modules/`, `vendor/`, and other build artifacts/dependency directories (standard hygiene, not a security issue, but still excluded)

**Setup requirement:** initialize the repo with a correct `.gitignore` covering the categories above (`.env*`, `*.pem`, `*.key`, `id_rsa*`, `/vendor`, `/node_modules`, test directories, etc.) as the very first action, before any other Phase 1 code is written.

## Anti-Slop / Quality Standards

- Do not use generic AI-template layouts, stock hero sections, or placeholder Lorem Ipsum copy that looks unconsidered — use realistic Kenyan-context placeholder content (service names, KES pricing, Kenyan names/locations) even in Phase 1 mockup data.
- Write DRY, modular Livewire components — reusable provider-card, category-tile, and booking-widget components, not duplicated Blade partials.
- Component and route naming should reflect domain language (e.g., `Fundi`, `Provider`, `Booking`, `ServiceCategory`) not generic CRUD naming (`Item`, `Post`, `Record`).
- Every prompt you receive will begin with "read this file" and end with "update this file" — keep the progress log below current and specific, not vague.

## Progress Log

- **2026-08-27** — Project initialized. No code written yet. Mandatory GitHub commit/push-after-every-action policy and secret-exclusion list added to this file. Awaiting first Phase 1 scaffolding prompt from PM.
- **2026-08-27** — Initialized `.gitignore` covering environment files, private keys, build artifacts, and test directories to enforce secret-exclusion policies.
- **2026-08-27** — Renamed `README.md` to `agent_context.md` as per instructions.
- **2026-08-27** — Completed Phase 1 "Sockets" Scaffolding: Initialized Laravel 11 project (with Livewire structure), set strict `.gitignore`, configured placeholders in `.env.example`, defined static `ServiceDirectory` model with 51 categories, created empty Phase 1 Livewire components, and stubbed foundational routes in `web.php`.
- **2026-08-28** — Built the Phase 1 UI Visual Layer using WireKit components. Applied custom brand tokens (Kenyan Green, Terracotta, Safari Gold) in `app.css`. Scaffolded `home`, `browse`, `show`, `provider.profile`, and `how-it-works` pages using WireKit layout primitives (hero, grid, card, button). Validated UI across viewports using Playwright browser subagent and fixed minor routing bugs.
- **2026-08-28** — Scaffolded the Phase 1 Booking Socket data model. Created `Booking` and `BookingReschedule` models and migrations mapping exactly to the locked state machine (`requested`, `accepted`, `in_progress`, etc). Added method stubs for all state transitions with TODOs linking to their future Phase 2 dependencies. Confirmed this backend socket is NOT yet wired into any UI.
