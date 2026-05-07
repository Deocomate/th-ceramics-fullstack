@props(['giaTriVuotTroi' => null])

@php
    $staticFallback = [
        (object) ['title' => 'CỐT ĐẤT LUYỆN LỬA', 'desscription' => 'Được tôi luyện ở nhiệt độ 1300°C tạo nên cốt gốm đanh chắc, thách thức mọi khắc nghiệt từ sương muối đến bão giông. Đây là sự đầu tư một lần cho giá trị truyền đời, chẳng ngại dấu vết thời gian', 'image' => null],
        (object) ['title' => 'KIẾN TRÚC TRƯỜNG TỒN', 'desscription' => 'Vẻ đẹp truyền thống kết hợp cùng công nghệ sản xuất hiện đại, tạo nên mái nhà vững chãi, che chở vạn vật qua bao thăng trầm, mang lại sự bình yên và hưng thịnh cho gia chủ.', 'image' => null],
        (object) ['title' => 'NGHỆ THUẬT GỐM SỨ', 'desscription' => 'Từng viên ngói là một tác phẩm nghệ thuật, kết tinh từ bàn tay khéo léo của các nghệ nhân làng gốm Bát Tràng, mang trong mình tâm hồn và sức sống di sản Việt.', 'image' => null],
        (object) ['title' => 'SẮC MEN ĐỘC BẢN', 'desscription' => 'Lớp men hỏa biến biến ảo trong lò nung, tạo nên những sắc thái màu đặc trưng không viên nào giống viên nào, tạo nên dấu ấn riêng biệt cho ngôi nhà của bạn.', 'image' => null],
    ];
    $values = (!empty($giaTriVuotTroi) && count($giaTriVuotTroi) > 0)
        ? $giaTriVuotTroi
        : $staticFallback;
    $fallbackImages = ['value-01.png', 'value-02.png', 'value-03.png', 'value-04.png'];
@endphp

<section class="w-full relative pb-8 md:pb-16" data-aos="fade-up">
  <div class="text-center mb-8 md:mb-12">
    <h2 class="text-[20px] md:text-3xl font-semibold text-secondary uppercase">
      Giá trị vượt trội
    </h2>
  </div>

  <div
    class="relative w-full overflow-hidden flex flex-col min-h-[511px] md:min-h-[900px]"
  >
    <img
      src="{{ asset('assets/images/gia-tri-vuot-troi.png') }}"
      alt="Giá trị vượt trội nền"
      class="absolute inset-0 w-full h-full object-cover z-0"
    />
    <div
      class="absolute inset-0 z-[1] md:hidden bg-[linear-gradient(92deg,rgba(255,255,255,0)_0%,rgba(116,44,44,0.54)_55%,rgba(116,44,44,0.60)_84%),rgba(186,113,113,0.34)]"
    ></div>

    <div
      class="relative z-10 container mx-auto px-6 lg:px-[140px] flex-grow flex flex-col items-end justify-start md:justify-center text-end text-white pt-[44px] md:pt-32"
    >
      <h3
        id="value-title"
        class="text-[20px] leading-[32px] md:text-3xl font-semibold mb-[5px] md:mb-10 tracking-[0.6px] md:tracking-wide drop-shadow-md transition-all duration-500"
      >
        {{ $values[0]->title }}
      </h3>

      <div class="w-full max-w-[383px] md:max-w-none md:shadow-none">
        <p
          id="value-description"
          class="font-charm text-[16px] leading-[28px] md:text-[32px]/relaxed max-w-[383px] md:max-w-5xl tracking-[1.1px] md:tracking-wider transition-all duration-500"
        >
          {{ $values[0]->desscription }}
        </p>
      </div>
    </div>

    <div
      class="absolute top-[261px] left-0 z-10 w-full md:relative md:top-auto md:left-auto mb-0 md:mb-16 md:px-0 max-w-[1920px] mx-auto"
    >
      <div
        class="flex flex-row justify-center md:justify-start gap-2 md:gap-5 items-end translate-y-0 md:translate-y-[20px] px-2 md:px-0"
      >
        @foreach($values as $index => $item)
        <div
          class="value-image-item {{ $index === 0 ? 'active' : '' }} aspect-[19/33] w-[80px] md:w-[200px] flex-shrink-0 shadow-lg"
          data-title="{{ $item->title }}"
          data-description="{{ $item->desscription }}"
        >
          <img
            src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/images/' . $fallbackImages[$index]) }}"
            alt="{{ $item->title }}"
            class="w-full h-full object-cover"
          />
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

@push('styles')
<style>
  .value-image-item {
    transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }

  .value-image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
  }

  .value-image-item:hover,
  .value-image-item.active {
    z-index: 20;
    width: 110px !important;
  }

  @media (min-width: 768px) {
    .value-image-item:hover,
    .value-image-item.active {
      width: 240px !important;
    }
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const valueSection = document
      .querySelector("#value-title")
      ?.closest("section");
    if (!valueSection) return;

    const valueImages = Array.from(
      valueSection.querySelectorAll(".value-image-item"),
    );
    const valueTitle = valueSection.querySelector("#value-title");
    const valueDescription = valueSection.querySelector("#value-description");

    if (!valueImages.length || !valueTitle || !valueDescription) return;

    const updateValueSection = (img) => {
      if (img.classList.contains("active")) return;

      valueImages.forEach((item) => item.classList.remove("active"));
      img.classList.add("active");

      valueTitle.style.opacity = "0";
      valueDescription.style.opacity = "0";
      valueTitle.style.transform = "translateY(10px)";
      valueDescription.style.transform = "translateY(10px)";

      window.setTimeout(() => {
        valueTitle.textContent = img.dataset.title || "";
        valueDescription.textContent = img.dataset.description || "";

        valueTitle.style.opacity = "1";
        valueDescription.style.opacity = "1";
        valueTitle.style.transform = "translateY(0)";
        valueDescription.style.transform = "translateY(0)";
      }, 300);
    };

    valueImages.forEach((img) => {
      img.addEventListener("mouseenter", () => {
        if (window.innerWidth >= 1024) {
          updateValueSection(img);
        }
      });

      img.addEventListener("click", () => {
        updateValueSection(img);
      });
    });
  });
</script>
@endpush
