@props([
    'config' => null,
])
@php
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

    $applicationSlots = [
        'main' => 'Tường trang trí',
        'sub_1' => 'Lát nền',
        'sub_2' => 'Phòng khách',
        'sub_3' => 'Ngoài trời',
        'sub_4' => 'Phòng tắm',
    ];
    $applications = $config && is_array($config->ung_dung_da_dang) ? $config->ung_dung_da_dang : [];
    $hasApplications = collect($applicationSlots)
        ->keys()
        ->contains(function ($slot) use ($applications) {
            $item = is_array($applications[$slot] ?? null) ? $applications[$slot] : [];

            return !empty($item['title']) || !empty($item['image']);
        });
@endphp

@if ($hasApplications)
    <!-- Ứng dụng đa dạng Section -->
    <section class="max-w-[1320px] w-[85%] mx-auto pb-4 pt-8 md:py-12" data-aos="fade-up">
        <h2
            class="font-archivo text-[20px] sm:text-[24px] md:text-[32px] font-semibold uppercase text-secondary leading-[36px] sm:leading-[44px] md:leading-[80px] mb-5 sm:mb-6 md:mb-10 text-left">
            Ứng dụng đa dạng
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 sm:gap-6 md:gap-8 lg:gap-16">
            @php
                $mainItem = is_array($applications['main'] ?? null) ? $applications['main'] : [];
            @endphp
            <div class="flex flex-col">
                <div class="w-full aspect-[23/25] bg-[#E2E2E2] shadow-lg overflow-hidden">
                    @if (!empty($mainItem['image']))
                        <img src="{{ $mediaUrl($mainItem['image']) }}"
                            alt="{{ $mainItem['title'] ?? $applicationSlots['main'] }}"
                            class="w-full h-full object-cover">
                    @endif
                </div>
                <p
                    class="mt-0 sm:mt-[11px] flex h-[44px] sm:h-[50px] md:h-[55px] w-full md:w-[286px] flex-col justify-center text-left text-[14px] leading-[20px] sm:text-base md:text-lg lg:text-xl font-archivo font-semibold uppercase text-primary">
                    {{ $mainItem['title'] ?? $applicationSlots['main'] }}
                </p>
            </div>

            <div class="flex flex-col justify-between gap-0 sm:gap-5 h-full">
                @foreach ([['sub_1', 'sub_2'], ['sub_3', 'sub_4']] as $row)
                    <div class="grid grid-cols-2 gap-[10px] sm:gap-4 md:gap-8 lg:gap-16">
                        @foreach ($row as $slot)
                            @php
                                $item = is_array($applications[$slot] ?? null) ? $applications[$slot] : [];
                            @endphp
                            <div class="flex flex-col">
                                <div
                                    class="w-full aspect-[179/192] sm:aspect-square bg-[#E2E2E2] shadow-lg overflow-hidden">
                                    @if (!empty($item['image']))
                                        <img src="{{ $mediaUrl($item['image']) }}"
                                            alt="{{ $item['title'] ?? $applicationSlots[$slot] }}"
                                            class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <p
                                    class="mt-0 sm:mt-[11px] mx-auto flex h-[44px] sm:h-[50px] md:h-[55px] w-full md:w-[286px] flex-col justify-center text-center text-[14px] leading-[20px] sm:text-base md:text-lg lg:text-xl font-archivo font-semibold uppercase text-primary">
                                    {{ $item['title'] ?? $applicationSlots[$slot] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
