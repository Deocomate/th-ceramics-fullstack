@props([
    'trangChu' => null,
    'ngoiAmDuongs' => null,
    'ngoiHais' => null,
    'gachHoas' => null,
    'about' => null,
    'factory' => null,
    'showroomImages' => null,
    'showroomContent' => null,
    'news' => null,
    'article' => null,
    'articles' => null,
    'relatedArticles' => null,
    'historyArticles' => null,
    'projects' => null,
    'project' => null,
    'relatedProjects' => null,
    'categories' => null,
    'selectedCategory' => null,
    'currentCategory' => null,
    'config' => null,
    'products' => null,
    'relatedProducts' => null,
    'product' => null,
    'colors' => null,
    'dinhMuc' => null,
    'giaTriVuotTroi' => null,
    'parentConfig' => null,
    'pageLabel' => null,
    'indexRouteName' => null,
    'categoryType' => null,
    'categoryLabel' => null,
    'denGomProducts' => null,
    'denSuProducts' => null,
    'featuredProducts' => null,
    'collectionProducts' => null,
    'ngheProducts' => null,
    'linhVatProducts' => null,
    'bgImage' => null,
    'activeOrder' => false,
    'activeAccount' => false,
    'activeCatalog' => false,
    'activeGuide' => false,
    'activeProcess' => false,
    'activePrivacy' => false,
    'activeReturn' => false,
    'activeShipping' => false,
    'image' => null,
    'label1' => null,
    'rate1' => null,
    'label2' => null,
    'rate2' => null,
    'sectionId' => null,
    'sectionClass' => null,
    'sectionTitle' => null,
    'desktopLinkHref' => null,
    'detailRouteName' => null,
    'wrapperClass' => null,
    'titleClass' => null,
    'title' => null,
    'subtitle' => null,
    'description' => null,
    'items' => null,
])
<!-- Ưu điểm vượt trội Section -->
<section class="md:pb-16 bg-background-secondary overflow-hidden md:pt-4">
    <div class="w-[85%] max-w-[1320px] mx-auto">
        <h2 class="text-secondary text-[20px] md:text-[32px] font-semibold uppercase text-center mt-1 font-archivo md:leading-[62.5px]" data-aos="fade-up">
            Ưu điểm vượt trội
        </h2>

        @php
            $galleryImages = $config->anh->pluck('image')->toArray();

            $advantagesData = [
                [
                    'title' => 'Bền bỉ & Thân thiện',
                    'description' => 'Chịu tốt mọi thời tiết, không han gỉ hay nứt vỡ như sắt và thủy tinh. Chất liệu đất nung tự nhiên cực kỳ an toàn và thân thiện môi trường',
                    'image' => $galleryImages[0] ?? null
                ],
                [
                    'title' => 'Giá trị phong thủy',
                    'description' => 'Kết tinh từ Thổ - Hỏa, mang lại nguồn năng lượng ấm áp và vẻ đẹp độc bản từ đôi bàn tay nghệ nhân',
                    'image' => $galleryImages[1] ?? null
                ],
                [
                    'title' => 'Linh hoạt & Dễ dùng',
                    'description' => 'Thiết kế thông minh giúp việc tháo lắp, thay bóng hay vệ sinh trở nên đơn giản, tiện lợi cho mọi không gian',
                    'image' => $galleryImages[2] ?? null
                ],
                [
                    'title' => 'Ánh sáng nghệ thuật',
                    'description' => 'Chất gốm khuếch tán ánh sáng dịu nhẹ qua các đường chạm khắc, tạo hiệu ứng bóng đổ lung linh và thư thái',
                    'image' => $galleryImages[3] ?? null
                ]
            ];
        @endphp

         <!-- Mobile Swiper (Chỉ hiển thị trên mobile) -->
         <div class="md:hidden">
             <div class="swiper advantage-swiper">
                 <div class="swiper-wrapper">
                     @foreach ($advantagesData as $card)
                         <div class="swiper-slide h-auto">
                             <x-client.products.den-gom-su.advantage-card
                                 :bg-image="$card['image']"
                                 :title="$card['title']"
                                 :description="$card['description']" />
                         </div>
                     @endforeach
                 </div>
             </div>
             <!-- Dots -->
             <div class="advantage-pagination flex justify-center items-center gap-[3px] w-full mt-6"></div>
         </div>

         <!-- Desktop Grid (ẩn trên mobile, hiển thị trên md) -->
         <div class="hidden md:flex flex-wrap lg:flex-nowrap justify-between gap-[25px] items-start pt-[45px] lg:pt-[45px] pb-[50px]">
             @foreach ($advantagesData as $index => $card)
                 <div class="w-full md:w-[48%] lg:w-[312px] {{ $index % 2 === 0 ? 'lg:mt-[87px]' : '' }}">
                     <x-client.products.den-gom-su.advantage-card
                         :bg-image="$card['image']"
                         :title="$card['title']"
                         :description="$card['description']" />
                 </div>
             @endforeach
         </div>
     </div>
 </section>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            new Swiper(".advantage-swiper", {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: false,
                pagination: {
                    el: ".advantage-pagination",
                    clickable: true,
                    renderBullet: function(index, className) {
                        return (
                            '<span class="' +
                            className +
                            ' rounded-full bg-secondary/30 transition-all cursor-pointer inline-block"></span>'
                        );
                    },
                },
            });
        });
    </script>
@endpush
