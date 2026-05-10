# Seeder System Rewrite

**Date**: 2026-05-10 07:10
**Severity**: Medium (data integrity and idempotency)
**Component**: Database seeders (all 6 files)
**Status**: Resolved

## What Happened

Rewrote all 6 Laravel seeders to purge placeholder/lorem ipsum data and replace it with real Vietnamese content, real image paths from `assets/images/`, and idempotent `firstOrCreate` patterns. The original seeders used `create()`, `DB::unprepared`, raw SQL, `File::copy`/`Storage::put`, and placeholder strings that made repeated `db:seed` runs break on constraint violations or silently produce nonsense data.

## The Brutal Truth

The old seeders were a minefield of one-shot code. Running `php artisan db:seed` a second time would either crash (unique constraint violations, duplicate key exceptions) or silently produce garbage (lorem ipsum descriptions, `defaults/placeholder.png` for every product image, zero child table rows for several categories). The `HomeAndAboutUsSeeder` used raw `DB::unprepared` SQL -- unreadable, unmaintainable, and completely decoupled from Eloquent model casts. The `ProductDetailSeeder` copied files with `File::copy`/`Storage::put` during seeding, which is absurd: seeding should be a DB operation, not a filesystem mutation.

The worst part: hidden columns. Child tables had `NOT NULL` columns that were invisible in the model's `$fillable` arrays. `phan_loai_ngoi_bo_noc_ct` and `phan_loai_bo_noc_chu_van_ct` both have `UNIQUE` constraints on `name` -- no error until you run the seeder twice. These were 2am discoveries, not documented warnings.

## Technical Details

**Files modified (6):**

1. **DatabaseSeeder.php** -- Reordered call sequence to respect FK dependencies: `User` -> `DinhMuc` -> `PageConfig` -> `HomeAndAboutUs` -> `ProductType` -> `ProductDetail`.

2. **DinhMucSeeder.php** -- Replaced `truncate()` + `create()` with `firstOrCreate()`. Expanded product matrices:
   - `gach_trang_tri`: 3 -> 5 sizes
   - `gach_hoa_thong_gio`: 3 -> 4 sizes
   - `gach_co_bat_trang`: 3 -> 4 sizes

3. **PageConfigSeeder.php** -- Changed `create()` -> `firstOrCreate()`. Removed `json_encode()` calls -- the model's `$casts` array handles serialization. Added real `zalo_link`.

4. **HomeAndAboutUsSeeder.php** -- Complete rewrite. Replaced `DB::unprepared` raw SQL with Eloquent `firstOrCreate()`. Added previously unseeded `seedGiaTriVuotTroi` (4 rows). All content now real Vietnamese text.

5. **ProductTypeSeeder.php** -- Replaced `DB::table()->insertOrIgnore` with Eloquent `firstOrCreate()`. All 9 category parents use real images from `assets/images/` instead of `defaults/placeholder.png`. Added child table seeding for categories that had zero child data: `GachHoaThongGioAnh`, `GiaTriGachHoaThongGio`, `DauAnGachTrangTri`, etc.

6. **ProductDetailSeeder.php** -- Removed `File::copy`/`Storage::put` filesystem operations. Products now use string paths directly. 60+ products with real Vietnamese descriptions, proper SKU codes, and realistic VND pricing. Fixed unique constraint issues on `phan_loai` tables.

**Hidden columns discovered (NOT in model fillables, but NOT NULL in schema):**
- `background` column
- `desscription` column (typo in schema naming -- this is the actual column name)
- `description` column
- `location` column

**UNIQUE constraints that caused silent failures on re-run:**
- `phan_loai_ngoi_bo_noc_ct.name`
- `phan_loai_bo_noc_chu_van_ct.name`

## What We Tried

Running `php artisan db:seed` as a single batch after the initial rewrite crashed with opaque constraint violations buried in the cascade. The breakthrough was running each seeder individually:

```
php artisan db:seed --class=DinhMucSeeder
php artisan db:seed --class=PageConfigSeeder
php artisan db:seed --class=HomeAndAboutUsSeeder
php artisan db:seed --class=ProductTypeSeeder
php artisan db:seed --class=ProductDetailSeeder
```

This isolated errors to their source seeder instantly. The constraint violation on `phan_loai_ngoi_bo_noc_ct.name` was invisible when buried in the full `db:seed` output.

## Root Cause Analysis

The original seeders were written as write-once, run-once scripts -- typical for early prototyping when the dev assumes a fresh DB every time. Nobody planned for idempotent seeders because nobody planned to seed more than once. The placeholder data (lorem ipsum, fake images, missing child rows) was "good enough" for UI scaffolding but completely useless for demo, QA, or staging environments. The use of raw SQL and filesystem operations in seeders suggests the original author was unfamiliar with Laravel seeder conventions or was porting data from a legacy system dump.

## Lessons Learned

1. **Every seeder must be idempotent. No exceptions.** `firstOrCreate` costs nothing and saves hours of debugging when someone re-runs `db:seed` on staging.

2. **Run seeders individually to debug.** Batch output hides which seeder threw the violation. Isolate, then combine.

3. **Model `$fillable` can lie to you.** Child tables had NOT NULL columns not listed in fillables. The schema is the source of truth -- read the migration before writing the seeder.

4. **Seeders should never touch the filesystem.** `File::copy` and `Storage::put` have no place in database seeding. Path strings to existing assets are sufficient.

5. **Placeholder data in seeders is a liability, not a convenience.** Lorem ipsum product descriptions and fake images make the application look broken during demos and hide real UI rendering bugs.

## Next Steps

- [ ] Audit remaining models for hidden NOT NULL columns not in `$fillable` -- there are likely more
- [ ] Verify all image paths in seeders actually exist in `assets/images/` (spot-checked, not exhaustive)
- [ ] Consider a `SeedCommand` that wraps each seeder call in try/catch with per-seeder error reporting
- [ ] Add a `--force` flag to the seed command that skips `firstOrCreate` guard for CI fresh-DB runs
