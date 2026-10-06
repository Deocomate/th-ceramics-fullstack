@props([
    'index' => 1,
    'defaultShape' => 'rectangle',
    'variant' => 'hai-vm',
])

@php
    $isWeight = $variant === 'weight';
    $rowClass = $isWeight
        ? 'flex flex-col lg:flex-row gap-2 lg:gap-4 items-start lg:items-end'
        : 'flex flex-col lg:flex-row gap-2 lg:gap-4 items-start lg:items-end';
    $desktopLabelClass = $isWeight
        ? 'hidden lg:flex w-full lg:w-[150px] shrink-0 pb-1 flex-col'
        : 'hidden md:flex w-full md:w-[150px] shrink-0 pb-1 flex-col';
    $gridClass = $isWeight
        ? 'flex-1 w-full flex flex-row lg:grid lg:grid-cols-12 gap-2 lg:gap-7 items-end'
        : 'flex-1 w-full flex flex-row md:grid md:grid-cols-12 gap-2 md:gap-7 items-end';
    $shapeColClass = $isWeight
        ? 'w-[111px] md:w-[111px] lg:w-auto shrink-0 col-span-12 lg:col-span-3 flex flex-col justify-end'
        : 'w-[111px] md:w-auto shrink-0 md:col-span-3 flex flex-col justify-end';
    $inputColClass = $isWeight
        ? 'flex-1 lg:col-span-3 flex flex-col gap-1 lg:gap-2'
        : 'flex-1 md:col-span-3 flex flex-col gap-1 md:gap-2';
    $labelClass = $isWeight
        ? 'text-[9px] lg:text-[16px] uppercase font-semibold text-primary text-center tracking-tight leading-none min-h-[16px] lg:min-h-auto'
        : 'text-[9px] md:text-[16px] uppercase font-semibold text-[#2E2F2A] md:text-primary text-center tracking-tight leading-[10px] md:leading-none min-h-0 md:min-h-[44px] flex flex-col items-center justify-start';
    $selectClass = $isWeight
        ? 'w-full h-[30px] lg:h-[45px] px-2 lg:px-3 pr-5 lg:pr-6 border border-black/10 rounded-sm bg-transparent text-[9px] lg:text-[11px] uppercase appearance-none outline-none focus:border-secondary transition-colors'
        : 'w-full h-[30px] md:h-[45px] px-2 md:px-3 pr-5 md:pr-6 border border-[#DDD6D0] md:border-black/10 rounded-sm bg-transparent text-[9px] md:text-[11px] uppercase appearance-none outline-none focus:border-secondary transition-colors';
    $inputClass = $isWeight
        ? 'w-full h-[30px] lg:h-[45px] p-2 lg:p-3 border border-black/10 rounded-sm bg-transparent text-[12px] lg:text-base text-left pr-4 lg:pr-6 focus:border-secondary outline-none transition-colors'
        : 'w-full h-[30px] md:h-[45px] p-2 md:p-3 border border-[#DDD6D0] md:border-black/10 rounded-sm bg-transparent text-[12px] md:text-base text-left pr-4 md:pr-6 focus:border-secondary outline-none transition-colors';
    $mobileTitleClass = $isWeight
        ? 'font-semibold text-primary uppercase text-[11px] leading-tight'
        : 'font-semibold text-primary uppercase text-[11px] leading-[24px]';
    $removeDesktopClass = $isWeight
        ? 'text-[14px] text-secondary underline text-left font-bold w-fit ml-4 mt-1'
        : 'text-[14px] text-secondary underline text-left font-medium ml-4';
    $removeMobileClass = $isWeight
        ? 'text-[8px] text-secondary/70 underline italic font-bold'
        : 'text-[8px] text-[rgba(199,110,0,0.70)] underline italic font-normal md:hidden';
@endphp

<div class="{{ $rowClass }}" data-area-block>
    <div class="{{ $desktopLabelClass }}">
        <span class="font-semibold text-primary uppercase text-base" data-area-title>DIỆN TÍCH {{ $index }}</span>
        <button type="button" data-remove-area class="{{ $removeDesktopClass }} hidden">Loại bỏ</button>
    </div>
    <div class="{{ $gridClass }}">
        <div class="{{ $shapeColClass }}">
            <div class="flex items-center gap-2 {{ $isWeight ? 'lg:hidden mb-2 pl-2' : 'md:hidden mb-1 pl-0.5' }}">
                <span class="{{ $mobileTitleClass }}" data-area-title>DIỆN TÍCH {{ $index }}</span>
                <button type="button" data-remove-area class="{{ $removeMobileClass }} hidden">Loại bỏ</button>
            </div>
            <div class="relative">
                <select data-shape-select class="{{ $selectClass }}">
                    <option value="rectangle" @selected($defaultShape === 'rectangle')>HÌNH CHỮ NHẬT</option>
                    <option value="trapezoid" @selected($defaultShape === 'trapezoid')>HÌNH THANG</option>
                    <option value="triangle" @selected($defaultShape === 'triangle')>HÌNH TAM GIÁC</option>
                </select>
                <div class="absolute right-1 lg:right-3 top-1/2 -translate-y-1/2 pointer-events-none text-secondary">
                    <svg class="w-2.5 h-2.5 lg:w-3 lg:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="{{ $inputColClass }}" data-input-wrapper data-input-role="primary">
            <label class="{{ $labelClass }}">
                CHIỀU DÀI
                <span class="block text-[7px] md:text-[12px] font-normal italic normal-case text-[rgba(199,110,0,0.70)] md:text-secondary mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">Chiều tính diềm mái</span>
            </label>
            <div class="relative">
                <input type="number" step="0.01" min="0" placeholder="0" data-dimension="primary" class="{{ $inputClass }}" />
                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-primary/40">m</span>
            </div>
        </div>

        <div class="{{ $inputColClass }}" data-input-wrapper data-input-role="secondary">
            <label class="{{ $labelClass }}">
                CHIỀU RỘNG
                <span class="block text-[7px] md:text-[12px] opacity-0 mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">_</span>
            </label>
            <div class="relative">
                <input type="number" step="0.01" min="0" placeholder="0" data-dimension="secondary" class="{{ $inputClass }}" />
                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-primary/40">m</span>
            </div>
        </div>

        <div class="{{ $inputColClass }}" data-input-wrapper data-input-role="height" style="display: none">
            <label class="{{ $labelClass }}">
                CHIỀU CAO
                <span class="block text-[7px] md:text-[12px] opacity-0 mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">_</span>
            </label>
            <div class="relative">
                <input type="number" step="0.01" min="0" placeholder="0" data-dimension="height" class="{{ $inputClass }}" />
                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-primary/40">m</span>
            </div>
        </div>
    </div>
</div>
