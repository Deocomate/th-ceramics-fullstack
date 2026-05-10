# Dinh Muc Seeder & Calculator DB Integration

**Date**: 2026-05-10 03:37
**Severity**: Medium (design debt removed)
**Component**: Product calculators, seeders
**Status**: Resolved

## What Happened

Integrated the 6 `dinh_muc` database tables into the frontend product calculators. Before this, the `weight-calculator.blade.php` (Ngoi Am Duong) had a hardcoded `coeffMatrix` JS object with all roofing constants duplicated in the view. Now the data flows from DB through controller to view via a `:dinhMuc` Blade prop.

Created `DinhMucSeeder` with 19 rows across the 6 empty tables using truncate-then-insert for idempotent re-runs. Wired `:dinhMuc` props through all 4 product detail blade files (ngoi-am-duong + 3 gach types). Added `@props` declaration to `quantity-calculator.blade.php` so the gach detail pages pass dinh muc data through.

## The Brutal Truth

The hardcoded `coeffMatrix` object was a ticking time bomb. Any change to roofing constants required editing a JS object buried inside a Blade view. No one would remember it was there. The 6 dinh muc tables were created by migrations but sat completely empty with zero seed data and zero frontend usage -- dead schema walking. This integration finally makes those tables earn their keep.

The `m²` vs `m2` mismatch is embarrassing. The HTML uses Unicode superscript (`m²`), the DB stores plain ASCII (`m2`), and the JS does a `.replace('m²', 'm2')` string conversion at runtime to match the lookup keys. This is fragile and will break silently if anyone changes the display format without updating the JS normalization logic.

## Technical Details

**Seeder**: `DinhMucSeeder.php` wraps all 6 table operations in `SET FOREIGN_KEY_CHECKS=0/1`. Each table is truncated then bulk-inserted:

- `DinhMucNgoiAmDuong`: 8 rows (4 tile types x 2 roof types -- "Mai go" and "Mai be tong")
- `DinhMucNgoiHaiCo`: 1 row (standard size, mai go=125, mai be tong=75)
- `DinhMucNgoiHaiVanMieu`: 1 row (standard size, mai go=125, mai be tong=88)
- `DinhMucGachTrangTri`: 3 sample rows (20x20, 10x20, 30x30)
- `DinhMucGachHoaThongGio`: 3 sample rows (vuong 20x20, vuong 30x30, chu nhat 20x30)
- `DinhMucGachCoBatTrang`: 3 sample rows (op tuong 5x20, xay tuong 10x20, lat nen 20x20)

**JS lookup change in weight-calculator.blade.php** (line 266, 444-455):
```js
const dinhMucData = @json($dinhMuc);  // was: const coeffMatrix = { hardcoded... }

// JS lookup: reads option TEXT not value code
const roof = roofStyleSelect.options[roofStyleSelect.selectedIndex]?.text || '';
const rawTileText = tileTypeSelect.options[tileTypeSelect.selectedIndex]?.text || '';
const tile = rawTileText.replace('m²', 'm2');  // normalize Unicode to ASCII

const row = dinhMucData.find(r => r.roof_type === roof && r.tile_type === tile);
if (row) {
    amCoeff = row.ngoi_am;
    duongCoeff = row.ngoi_duong;
    diemCoeff = row.diem;
}
```

**Controller side**: Each product detail controller fetches the relevant DinhMuc model and passes it as `$dinhMuc` to the view. The `@json($dinhMuc)` Blade directive serializes the Eloquent collection to a JS array of objects.

**Prop chain**: `Controller` -> `detail.blade.php` passes `:dinhMuc="$dinhMuc"` -> `weight-calculator` or `quantity-calculator` component receives via `@props(['dinhMuc' => []])`.

## What We Tried

No failed attempts. The approach was straightforward: replace hardcoded JS with serialized PHP data.

## Root Cause Analysis

The original developer hardcoded roofing constants in the JS layer because the DB tables had no seed data and no controller logic existed to hydrate them. This is classic bootstrapping debt -- the schema was created proactively but never wired up. The `coeffMatrix` object was the path of least resistance at implementation time.

## Lessons Learned

1. **Display names as lookup keys is a design smell.** The DB stores `roof_type='Mai go'` and `tile_type='15 cap/m2'` as Vietnamese display strings. If anyone renames these for UX polish, the lookup silently returns `undefined` and all coefficients become zero. Value-based keys (`roof_code='wood'`, `tile_pairs=15`) would survive display text changes.

2. **Unicode normalization at runtime is fragile.** The `m²` -> `m2` replace is a one-line fix that papers over a data modeling gap. If the tile type stored `m²` in the DB too, no conversion would be needed. Alternately, if the HTML used `m2` in the option text, no conversion would be needed. Pick one representation and use it everywhere.

3. **Truncate-then-insert is fine for 19 rows.** For a seeder this small, FK checks off/on is acceptable. If these tables grow to hundreds of rows, switch to upsert.

## Next Steps

- [ ] Consider normalizing the `m²`/`m2` discrepancy at the source: either store `m²` in the DB tile_type column or render `m2` in the select options HTML. Remove the runtime string replace.
- [ ] Add a JS fallback/warning when `dinhMucData.find()` returns undefined -- currently coefficients silently default to 0 with no user feedback.
- [ ] Verify the seed data values are correct business numbers, not placeholder guesses. The gach type seed data in particular (value=25, 50, 11) looks like rough estimates.
