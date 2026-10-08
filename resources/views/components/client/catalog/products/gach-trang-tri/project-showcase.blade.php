@props([
    'projects' => collect(),
])
@php
    $projects = is_object($projects) ? $projects : collect($projects ?? []);
    $mediaUrl = function (?string $path, string $fallback = '') {
        if (empty($path)) {
            return $fallback;
        }

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (\Illuminate\Support\Str::startsWith($path, 'assets/')) {
            return asset($path);
        }

        return asset('storage/' . $path);
    };
@endphp
<!-- Dấu ấn trên những công trình Section -->
<section class="w-full overflow-hidden mt-12 pb-12" data-aos="fade-up">
    <!-- Header/Title -->
    <div class="max-w-[1320px] w-[85%] mx-auto mb-[21px] md:mb-12">
        <h2
            class="font-archivo text-[20px] md:text-[32px] font-semibold uppercase text-secondary leading-[36px] md:leading-tight text-left">
            Dấu ấn trên những công trình
        </h2>
    </div>

    <!-- Custom Slider Container -->
    <div class="relative w-full h-[380px] md:h-[500px] lg:h-[800px] overflow-hidden select-none"
        id="custom-project-slider">
        <div class="slider-inner absolute inset-0 flex items-center justify-center">
            @if ($projects->isNotEmpty())
                @foreach ($projects as $index => $project)
                    @php
                        $projectImages = is_array($project->images) ? $project->images : [];
                        $projectImage = $projectImages[0] ?? null;
                    @endphp
                    <div class="custom-project-slide cursor-pointer" data-index="{{ $index }}">
                        <img src="{{ $mediaUrl($projectImage, asset('assets/images/trang-tri-slide-01.jpg')) }}"
                            alt="{{ $project->ten_du_an ?? 'Công trình' }}"
                            class="w-full h-full object-cover transition-transform duration-1000 ease-out hover:scale-[1.03]" />
                        <div class="absolute inset-0 pointer-events-none z-[2]"
                            style="background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(0, 0, 0, 0.80) 100%)">
                        </div>
                        <div
                            class="slide-content absolute bottom-[25px] left-[20px] md:bottom-12 md:left-12 lg:bottom-16 lg:left-16 text-white z-10 max-w-[85%]">
                            <h3
                                class="font-archivo font-bold text-[16px] md:text-lg lg:text-[20px] uppercase tracking-wider mb-1 lg:mb-2 leading-[20px] md:leading-tight">
                                {{ $project->ten_du_an ?? '' }}
                            </h3>
                            <div
                                class="font-archivo text-[12px] md:text-sm lg:text-[16px] leading-[16px] md:leading-relaxed">
                                <p class="mt-[4px] md:mt-0"><span class="font-bold">Địa điểm:</span><span
                                        class="font-normal"> {{ $project->dia_diem ?? '' }}</span></p>
                                <p class="mt-[2px] md:mt-0"><span class="font-bold">Sản phẩm:</span><span
                                        class="font-normal"> {{ $project->san_pham ?? '' }}</span></p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center text-white py-12 w-full">
                    Đang cập nhật hình ảnh công trình...
                </div>
            @endif
        </div>
    </div>

    <!-- Custom Pagination Dots -->
    <div class="flex justify-center gap-[7px] md:gap-3 mt-6 md:hidden" id="custom-project-dots">
        <!-- Will be populated dynamically via JavaScript -->
    </div>
</section>
