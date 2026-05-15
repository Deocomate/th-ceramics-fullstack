@php
    use App\Support\AssetPath;
@endphp
<section class="w-full">
  @if(!empty($factory->hero_banner_desktop))
    <img
      src="{{ AssetPath::url($factory->hero_banner_desktop) }}"
      alt="Xưởng sản xuất Thanh Hải"
      class="w-full h-auto object-cover hidden md:block"
    />
  @endif
  @if(!empty($factory->hero_banner_mobile))
    <img
      src="{{ AssetPath::url($factory->hero_banner_mobile) }}"
      alt="Xưởng sản xuất Thanh Hải"
      class="w-full h-auto block object-cover md:hidden"
    />
  @endif
</section>
