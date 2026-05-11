<section class="w-[85%] max-w-[1320px] mx-auto">
  <hr class="border-t border-black/10 mb-3 w-full" />

  <div class="mb-[30px] md:mb-10" data-aos="fade-up">
    <button
      type="button"
      class="product-filter-trigger flex items-center gap-3 text-textPrimary hover:text-secondary transition-colors font-bold uppercase tracking-[0.05em] text-[13px]"
      aria-expanded="{{ request()->hasAny(['search', 'sort']) ? 'true' : 'false' }}"
      aria-controls="product-filter-panel"
    >
      <div
        class="w-8 h-8 flex items-center justify-center border border-black/60 rounded text-black/60"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
        </svg>
      </div>
      BỘ LỌC SẢN PHẨM
    </button>

    <form
      id="product-filter-panel"
      method="GET"
      action="{{ url()->current() }}"
      class="{{ request()->hasAny(['search', 'sort']) ? 'grid' : 'hidden' }} mt-5 grid-cols-1 md:grid-cols-[1fr_220px_auto_auto] gap-3 md:gap-4"
    >
      <input
        type="search"
        name="search"
        value="{{ request('search') }}"
        placeholder="Tìm sản phẩm"
        class="w-full border border-black/20 bg-transparent px-4 py-3 text-sm text-primary outline-none focus:border-secondary"
      />
      <select
        name="sort"
        class="w-full border border-black/20 bg-transparent px-4 py-3 text-sm text-primary outline-none focus:border-secondary"
      >
        <option value="">Sắp xếp mặc định</option>
        <option value="price_asc" @selected(request('sort') === 'price_asc')>Giá tăng dần</option>
        <option value="price_desc" @selected(request('sort') === 'price_desc')>Giá giảm dần</option>
        <option value="name_asc" @selected(request('sort') === 'name_asc')>Tên A-Z</option>
      </select>
      <button type="submit" class="px-6 py-3 bg-secondary text-white text-sm font-bold uppercase">
        Lọc
      </button>
      <a href="{{ url()->current() }}" class="px-6 py-3 border border-black/20 text-primary text-sm font-bold uppercase text-center">
        Xóa
      </a>
    </form>
  </div>
</section>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".product-filter-trigger").forEach(function (button) {
      button.addEventListener("click", function () {
        var panel = document.getElementById(button.getAttribute("aria-controls"));
        if (!panel) return;
        var isHidden = panel.classList.contains("hidden");
        panel.classList.toggle("hidden", !isHidden);
        panel.classList.toggle("grid", isHidden);
        button.setAttribute("aria-expanded", isHidden ? "true" : "false");
      });
    });
  });
</script>
@endpush
