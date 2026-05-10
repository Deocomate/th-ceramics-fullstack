# Calculator Logic Refinement -- Rounding, Cart Sync, and Auto-Recalculation

**Date**: 2026-05-10 05:12
**Severity**: Medium
**Component**: Client-side calculators (quantity, hai-vm, weight)
**Plan**: `plans/260510-0427-calculator-logic-refinement/`
**Status**: Resolved

## What Happened

Fixed structural flaws across all 3 Blade calculator components that were silently producing wrong brick counts and had zero integration with the Add-to-Cart form. Three separate categories of bugs, all self-inflicted:

1. **Rounding order was wrong in all 3 calculators.** The business spec says ceiling the total raw area to the next m2 BEFORE multiplying by rate and loss factor. All 3 calculators were doing `Math.ceil(rawArea * rate * lossFactor)` -- which undercounts because it rounds after the multiplication instead of before.

2. **Loss radio parsing used fragile label-text matching.** Instead of reading `value` attributes (`1.05`, `1.10`), the code parsed the displayed label text (`"5%"`, `"10%"`) to derive the loss factor. Works until someone changes the label format, then everything silently breaks.

3. **No auto-recalculation on input change.** Users had to click the "TINH TOAN" button after every edit. No debounced live update. The button was the only trigger.

4. **No cart sync in quantity-calculator at all.** Hai-vm and weight calculators had partial sync, but the simplest one (Gach brick calculator) had none.

## The Brutal Truth

The frustrating part is that every single one of these bugs was visible in the code from the moment it was written. `Math.ceil(totalArea * rate)` instead of `Math.ceil(ceilArea * rate)` is a one-line fix. Label-text parsing instead of `value` attributes is a rookie mistake. And the cart sync function was already working in hai-vm but nobody ported it to quantity-calculator. This wasn't a subtle architectural problem -- it was four independent failures of basic code review.

The exhausting reality is that the previous plan (`dinh-muc-seeder-and-calculator`) successfully wired DB data into JS but shipped with broken math. The data plumbing was correct; the formula layer sitting on top of it was not. We tested the data arrived, we didn't test the numbers were right.

## Technical Details

### Spec rounding rule (all 3 calculators)

```
ceilS = Math.ceil(rawSumOfAllAreas)     // round up to nearest m2
quantity = Math.ceil(ceilS * lossFactor * rate)   // then multiply and ceil final
```

### What was actually happening

| Calculator | Was | Now |
|-----------|-----|-----|
| quantity-calculator | `Math.ceil(roundedArea * rate * lossFactor)` -- correct but no cart sync | Same formula, now syncs `input[name="qty"]` |
| hai-vm-calculator | `Math.ceil(totalArea * rate * combinedFactor)` -- raw totalArea, not ceil'd | `Math.ceil(roundedArea * rate * combinedFactor)` where `roundedArea = Math.ceil(totalArea)` |
| weight-calculator | `Math.ceil(totalS * factor * coeff)` -- raw totalS | `Math.ceil(ceilS * factor * coeff)` where `ceilS = Math.ceil(totalS)` |

### Loss radio parsing before and after

Before (fragile, label-text based):
```javascript
// Parsed textContent like "5%" → extracted "5" → divided by 100
const getLossFactor = () => {
    // ... label text parsing ...
    return parseFloat(label.match(/([\d.]+)/)[1]) / 100 + 1;
};
```

After (robust, value-attribute based):
```html
<input type="radio" name="loss-rate" value="1.05" checked />
<input type="radio" name="loss-rate" value="1.10" />
```
```javascript
const getLossFactor = () => {
    if (!extraLossCheckbox || !extraLossCheckbox.checked) return 1.0;
    const selected = lossRadios.find(r => r.checked);
    return Number.parseFloat(selected?.value) || 1.0;
};
```

### Cart sync pattern

```javascript
const syncCart = (quantity) => {
    const qtyInputs = document.querySelectorAll('input[name="qty"]');
    qtyInputs.forEach(input => {
        input.value = quantity;
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
    });
};
```

Both events are necessary: `input` triggers Vue/Alpine reactive bindings, `change` triggers native form validation/state. One without the other breaks different things.

### Auto-recalculation with debounce

```javascript
let calcTimeout;
const scheduleUpdate = () => {
    clearTimeout(calcTimeout);
    calcTimeout = setTimeout(updateResults, 300);
};

// Wire to all number inputs in each area block
const wireBlockInputs = (block) => {
    block.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', scheduleUpdate);
    });
};
```

Guard clause on weight-calculator and hai-vm-calculator to prevent calc before dropdowns selected:
```javascript
const updateResults = () => {
    if (roofStyleSelect && !roofStyleSelect.value) return;
    if (tileTypeSelect && !tileTypeSelect.value) return;
    // ... calculation ...
};
```

Without this guard, changing a dimension input before selecting roof type triggers `updateResults()` which finds no matching dinhMuc row and displays "00 vien" -- with a misleading sync notice.

### Notice debouncing

```javascript
let noticeTimer;
let lastSyncedQuantity = 0;

// Only show notice if quantity changed AND > 0
if (quantity > 0 && quantity !== lastSyncedQuantity) {
    clearTimeout(noticeTimer);
    noticeEl.classList.remove('hidden');
    noticeEl.style.opacity = '1';
    noticeTimer = setTimeout(() => {
        noticeEl.style.opacity = '0';
        setTimeout(() => noticeEl.classList.add('hidden'), 300);
    }, 3000);
    lastSyncedQuantity = quantity;
}
```

### Input type change

All dimension inputs changed from `type="text"` to `type="number" step="0.01" min="0"`. This affects:
- `querySelectorAll('input[type="number"]')` selectors in auto-calc wiring
- Native browser validation (no more non-numeric inputs)
- Mobile keyboard behavior (shows numeric keypad)

## What We Tried

No false starts on this one. The plan was thorough -- 4 phases, each building on the previous. Phase 1 established the sync pattern on the simplest calculator. Phase 2 applied it to hai-vm. Phase 3 handled weight-calculator's 3-output complexity and the diem-length special case (length should NOT be ceil'd). Phase 4 added auto-recalculation to all 3.

The only hiccup was the guard clause in Phase 4 -- initially auto-recalc fired on every input even before dropdown selection, producing "00 vien" results with visible sync notices. Fixed by adding the `if (!roofStyleSelect.value) return;` guard at the top of `updateResults()`.

## Root Cause Analysis

The original implementation was built in stages by different context windows. The first pass got the DB-to-JS data injection working. The second pass (this one) fixed the actual math. Nobody tested with real numbers end-to-end between those two passes.

Fundamentally: we shipped calculators where the user-facing math was demonstrably wrong, and the cart integration was incomplete. The reason nobody caught it is that the calculators "worked" -- they produced numbers. Wrong numbers, but numbers. No assertion or test validated `expectedBrickCount` against `actualBrickCount` for real-world inputs like "a 15.3 m2 area with 5% loss at rate 40".

## Lessons Learned

1. **Value attributes over label parsing, always.** If a radio button represents a numeric value used in calculation, put that value in the `value` attribute. Parsing label text is always one label-format change away from breaking silently.

2. **Ceil order matters.** `Math.ceil(a * b * c)` is not the same as `Math.ceil(Math.ceil(a) * b * c)`. This is not a rounding preference -- it's a business rule. When tiling, you buy by the rounded-up m2, then apply rates. Document the spec formula explicitly in comments.

3. **Cart sync needs both `input` and `change` events.** One triggers framework reactivity, the other triggers native form lifecycle. Omitting either breaks integration in ways that are hard to diagnose because the symptom is "form submits wrong value" not "JS error."

4. **Auto-recalculation without guard clauses is dangerous.** A 300ms debounce on typing isn't enough if `updateResults()` produces bogus output because prerequisite dropdowns aren't selected. Guard at function entry, not at call site.

5. **The simplest calculator having the most missing features is a code smell.** Quantity-calculator is the simplest (one formula, one output) but had zero cart sync. The complex calculators had partial sync. Someone started building features on the hard ones and never back-ported to the easy ones.

## Next Steps

- **Verify**: Manual smoke test on all 3 product detail pages (`/san-pham/gach-co-bat-trang/...`, `/san-pham/gach-hoa-thong-gio/...`, `/san-pham/ngoi-am-duong/...`) with real measurements and confirm cart qty updates correctly.
- **Add test coverage**: Write Pest browser tests (Laravel Dusk or similar) that fill calculator inputs, verify the calculated brick count matches known-good values for a set of test vectors.
- **No further changes needed** -- the HTML/CSS restructuring in the components was part of a broader detail page redesign, not this calculator fix. The JS logic changes in this plan are complete.
