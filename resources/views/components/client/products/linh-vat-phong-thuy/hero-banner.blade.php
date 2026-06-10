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
<!-- Banner Section -->
<section class="relative w-full h-[530px] md:h-[720px] lg:h-[840px] overflow-hidden flex items-start md:mb-[40px]">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="{{ !empty($config->thumbnail_main) ? asset('storage/' . $config->thumbnail_main) : asset('assets/images/linh-vat-banner.png') }}"
            alt="Linh Vật Phong Thủy Banner" class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <div class="container mx-auto px-6 lg:px-[100px] relative z-10 h-full flex flex-col">
        <!-- Main Title - Positioned more towards top-right/center like the image -->
        <div data-aos="fade-down"
            class="mt-[25px] md:mt-16 self-center md:self-start lg:translate-x-32 md:ml-[20%] w-full md:w-auto">
            <h1
                class="font-lavishly text-[48px] md:text-[64px] lg:text-[80px] text-white leading-[60px] md:leading-tight drop-shadow-2xl text-center md:text-left">
                Nghê thiêng trấn thổ
            </h1>
        </div>

        <!-- Poem - Positioned on the left like the image -->
        <div data-aos="fade-right"
            class="mt-[150px] md:mt-16 max-w-2xl text-white self-center md:self-start text-center md:-ml-12 px-4 md:px-0 w-full">
            <p
                class="font-ephesis text-[22px] md:text-3xl lg:text-[36px] leading-[32px] md:leading-[1.2] lg:leading-[1.4] tracking-[1.2px] md:tracking-wider drop-shadow-lg opacity-90">
                Oai phong bệ vệ trước sân đình
                <br />
                Chẳng phải sư tử, chẳng giống rồng
                <br />
                Nghê thiêng đứng vững ngàn năm vững
                <br />
                Trừ tà, trấn trạch, độ chúng sinh
                <br />
                Mắt sáng nhìn thấu muôn tà khí
                <br />
                Đuôi lửa quật tan ác quỷ ma
                <br />
                Dũng mãnh, trung thành, hồn Việt tỏ
                <br />
                Vững chãi cửa nhà, ấm thuận gia
            </p>
        </div>
    </div>
</section>
