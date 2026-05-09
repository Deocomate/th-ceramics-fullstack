# Coupon/Discount Module Implementation

**Date**: 2026-05-09
**Severity**: Medium (architectural decisions with long-term impact)
**Component**: Cart/Checkout/Coupon system
**Status**: Resolved

## What Happened

Implemented a full coupon/discount module across 5 phases: database schema, admin CRUD, validation service, checkout AJAX UI, and server-side re-validation. 18 files, 1443 insertions, zero blockers.

## The Brutal Truth

This went suspiciously smoothly. The parallel agent strategy for phases 2+3 worked because the phases had clean boundaries -- no shared files until the final checkout integration. The fact that nothing broke during Tinker verification is either a sign of solid design or a sign that we haven't pushed edge cases hard enough yet.

## Key Decisions and Tradeoffs

**is_delete boolean vs SoftDeletes**: Chose `is_delete` boolean to match existing project convention (`is_active`/`is_delete` pattern on other models). Rejected SoftDeletes because consistency with sibling models matters more than Laravel idiom purity. This means manual `where('is_delete', false)` scoping everywhere -- not ideal, but predictable for anyone working in this codebase.

**decimal(12,2) for discount_value**: Required for VND fixed-amount coupons where values routinely exceed 1,000,000. Standard decimal(8,2) would overflow. Could have separated percent vs fixed into different columns, but the single column with discriminator (`discount_type`) keeps queries simpler.

**Session-based coupon storage (th_cart_coupon)**: Mirrors the existing `th_cart` session pattern. No new coupling. The risk is that if the session expires between apply and checkout, the user loses their coupon silently. Could improve with a flash message warning, but that's UX polish for later.

**Server-side re-validation in processCheckout**: Non-negotiable security decision. Client-side applied coupon is treated as a hint, never trusted. Re-runs all 8 validation steps at payment time. Adds a query but prevents trivial fraud.

**incrementUsage inside DB::transaction**: Atomicity guarantee -- if the order fails after incrementing, the usage count rolls back. Obvious call, but easy to miss during implementation.

## Lessons Learned

1. Parallel agent execution works when phases have zero file overlap. The moment checkout touched CartService (also modified in phase 3), it should have gone sequential. Got lucky this time.
2. Project conventions (is_delete) beat framework idioms (SoftDeletes) every time. Consistency is more valuable than correctness.
3. Session-based coupon is the weakest link. If this gets abused in production, migrate to DB-persisted cart coupons.

## Next Steps

- Stress-test edge cases: expired coupon mid-session, concurrent usage limit hits, zero-value order after discount
- Monitor for session-expired-coupon complaints post-launch
- Consider DB-backed coupon assignment if cart abandonment becomes an issue
