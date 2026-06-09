@props(['image' => null, 'dinhMuc' => []])

<!-- Gach va cac loai khac -->
<section
  class="w-full pb-0 md:pb-16 lg:pb-20 bg-background-secondary"
  data-aos="fade-up"
  data-quantity-calculator
>
  <div class="w-[85%] max-w-[1320px] mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-16 lg:gap-32 items-start mt-0">
      <div class="flex flex-col items-center">
        <h2 class="text-[20px] md:text-3xl font-semibold text-[#C76E00] md:text-secondary mb-6 md:mb-12 uppercase text-center md:tracking-normal">KÍCH THƯỚC</h2>
        <div class="w-full max-w-[500px] flex justify-center">
          <img src="{{ isset($image) ? $image : asset('assets/images/gtt-size.png') }}" alt="Kích thước sản phẩm" class="w-full h-auto object-contain px-4 md:px-0" />
        </div>
      </div>

      <div class="flex flex-col mt-4 md:mt-0">
        <h2 class="text-[20px] md:text-3xl font-semibold text-[#C76E00] md:text-secondary mb-8 md:mb-12 uppercase text-center lg:text-center md:tracking-normal w-[85%] mx-auto md:w-full">CÁCH TÍNH KHỐI LƯỢNG</h2>

        <div class="space-y-4 md:space-y-6 w-[90%] md:w-full mx-auto md:mx-0">
          <div class="flex flex-row md:flex-row gap-4 md:gap-6 items-end justify-center md:justify-start" data-area-block>
            <div class="w-[111px] md:w-[120px] shrink-0 pb-2 md:pb-1 text-left">
              <span class="font-semibold text-[#2E2F2A] md:text-primary uppercase text-[11px] md:text-base">DIỆN TÍCH 1</span>
            </div>
            <div class="flex-1 max-w-[250px] md:max-w-none w-full grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-6 items-end">
              <hr class="hidden md:block md:col-span-1" />
              <div class="flex flex-col gap-1 md:gap-2 md:col-span-2">
                <label class="text-[9px] md:text-[16px] uppercase font-semibold text-[#2E2F2A] md:text-primary text-center md:mb-2 leading-[24px] md:leading-normal">CHIỀU DÀI</label>
                <div class="relative">
                  <input type="number" step="0.01" min="0" placeholder="00" class="w-full h-[30px] md:h-[45px] p-2 md:p-3 text-[12px] md:text-base border border-[#DDD6D0] md:border-black/10 rounded-[1px] md:rounded-sm bg-transparent text-left pr-6 md:pr-8 focus:border-secondary outline-none transition-colors text-[#2E2F2A] md:text-primary placeholder:text-[#9CA3AF]" />
                  <span class="absolute right-2 md:right-3 top-1/2 -translate-y-1/2 text-[10px] md:text-[12px] text-black/40 md:text-primary/40 leading-[15px]">m</span>
                </div>
              </div>
              <div class="flex flex-col gap-1 md:gap-2 md:col-span-2">
                <label class="text-[9px] md:text-[16px] uppercase font-semibold text-[#2E2F2A] md:text-primary text-center md:mb-2 leading-[24px] md:leading-normal">CHIỀU RỘNG</label>
                <div class="relative">
                  <input type="number" step="0.01" min="0" placeholder="00" class="w-full h-[30px] md:h-[45px] p-2 md:p-3 text-[12px] md:text-base border border-[#DDD6D0] md:border-black/10 rounded-[1px] md:rounded-sm bg-transparent text-left pr-6 md:pr-8 focus:border-secondary outline-none transition-colors text-[#2E2F2A] md:text-primary placeholder:text-[#9CA3AF]" />
                  <span class="absolute right-2 md:right-3 top-1/2 -translate-y-1/2 text-[10px] md:text-[12px] text-black/40 md:text-primary/40 leading-[15px]">m</span>
                </div>
              </div>
            </div>
          </div>

          <div class="flex flex-row md:flex-row gap-4 md:gap-6 items-end justify-center md:justify-start" data-area-block>
            <div class="w-[111px] md:w-[120px] shrink-0 pb-2 md:pb-1 flex flex-row gap-2 md:gap-0 md:flex-col items-start md:mt-2">
              <span class="font-semibold text-[#2E2F2A] md:text-primary uppercase text-[11px] md:text-base">DIỆN TÍCH 2</span>
              <button data-remove-area class="text-[8px] md:text-[14px] text-[#C76E00]/70 md:text-secondary underline text-start font-normal md:font-medium opacity-80 hover:opacity-100 transition-opacity mt-0.5 md:mt-0 italic md:not-italic">Loại bỏ</button>
            </div>
            <div class="flex-1 max-w-[250px] md:max-w-none w-full grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-6 items-end">
              <hr class="hidden md:block md:col-span-1" />
              <div class="relative md:col-span-2 flex flex-col justify-end h-full">
                <input type="number" step="0.01" min="0" placeholder="00" class="w-full h-[30px] md:h-[45px] p-2 md:p-3 text-[12px] md:text-base border border-[#DDD6D0] md:border-black/10 rounded-[1px] md:rounded-sm bg-transparent text-left pr-6 md:pr-8 focus:border-secondary outline-none transition-colors text-[#2E2F2A] md:text-primary placeholder:text-[#9CA3AF]" />
                <span class="absolute right-2 md:right-3 top-1/2 -translate-y-1/2 text-[10px] md:text-[12px] text-black/40 md:text-primary/40 leading-[15px]">m</span>
              </div>
              <div class="relative md:col-span-2 flex flex-col justify-end h-full">
                <input type="number" step="0.01" min="0" placeholder="00" class="w-full h-[30px] md:h-[45px] p-2 md:p-3 text-[12px] md:text-base border border-[#DDD6D0] md:border-black/10 rounded-[1px] md:rounded-sm bg-transparent text-left pr-6 md:pr-8 focus:border-secondary outline-none transition-colors text-[#2E2F2A] md:text-primary placeholder:text-[#9CA3AF]" />
                <span class="absolute right-2 md:right-3 top-1/2 -translate-y-1/2 text-[10px] md:text-[12px] text-black/40 md:text-primary/40 leading-[15px]">m</span>
              </div>
            </div>
          </div>

          <div class="text-center w-full pt-1">
            <button data-add-area class="text-[#C76E00] md:text-secondary font-semibold text-[10px] md:text-sm underline hover:opacity-80 transition-opacity leading-[20px]">+ Thêm diện tích</button>
          </div>

          <div class="w-full pt-4 md:pt-0 pb-6 md:pb-0">
            <button data-calculate-quantity class="w-full py-[16px] md:py-4 bg-[#C16A00] hover:bg-secondary text-white text-[14px] font-bold uppercase tracking-[0.42px] md:tracking-widest rounded-[2px] md:rounded-sm shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1),0_2px_4px_-2px_rgba(0,0,0,0.1)] transition-all duration-300 leading-[20px]">TÍNH TOÁN KHỐI LƯỢNG</button>
          </div>

          <div class="pt-6 md:pt-8 border-t border-black/10 mt-2 md:mt-0">
            <div class="flex flex-col md:grid md:grid-cols-5 gap-y-2 md:gap-y-4 items-start w-full">
              <div class="flex justify-between items-center w-full md:contents">
                <div class="md:col-span-4">
                  <h4 class="font-semibold text-[#2E2F2A] md:text-primary uppercase text-[16px] md:text-xl leading-[28px] md:leading-normal">KẾT QUẢ</h4>
                </div>
                <div class="text-right">
                  <span data-total-area-output class="text-[16px] md:text-2xl font-bold text-[#2E2F2A] md:text-primary leading-[20px] md:leading-none">{{ isset($totalArea) ? $totalArea : '00' }} m²</span>
                </div>
              </div>

              @isset($showInfo)
              <div class="flex flex-row items-end justify-start w-full md:contents pt-1 md:pt-0 relative">
                <div class="md:col-span-3 pr-2 md:pr-0">
                  <p class="text-[12px] md:text-[14px] italic text-[#2E2F2A] md:text-primary font-light md:font-light leading-[18px] max-w-[132px] md:max-w-none">
                    {{ isset($areaNote) ? $areaNote : 'Diện tích được làm tròn lên đến m² gần nhất.' }}
                  </p>
                </div>
                <div class="text-center md:col-span-1 px-2 md:px-0 flex flex-col justify-end">
                  <span class="text-[14px] md:text-base font-bold text-[#2E2F2A] md:text-primary tracking-[0.80px] md:tracking-wider leading-[24px]">Định mức</span>
                </div>
                <div class="hidden md:block md:col-span-1"></div>
              </div>

              <div class="flex justify-between items-center w-full md:contents">
                <div class="md:col-span-3 py-1 text-left w-[40%] md:w-auto">
                  <span class="text-[#2E2F2A] md:text-primary text-[16px] md:text-base leading-[24px]">{{ isset($label1) ? $label1 : 'Gạch' }}</span>
                </div>
                <div class="text-center py-1 w-[30%] md:w-auto">
                  <span data-rate-output class="text-[#2E2F2A] md:text-primary text-[14px] md:text-base leading-[16px]">{{ isset($rate1) ? $rate1 : '-- viên/m²' }}</span>
                </div>
                <div class="text-right py-1 w-[30%] md:w-auto flex justify-end">
                  <span data-value-output class="text-[#2E2F2A] md:text-primary font-bold text-[14px] md:text-base leading-[16px]">{{ isset($value1) ? $value1 : '00 viên' }}</span>
                </div>
              </div>

              @isset($label2)
              <div class="flex justify-between items-center w-full md:contents border-t border-black/5 md:border-none pt-2 md:pt-0 mt-2 md:mt-0">
                <div class="md:col-span-3 py-1 text-left w-[35%] md:w-auto">
                  <span class="text-[#2E2F2A] md:text-primary text-[16px] md:text-base leading-[24px]">{{ isset($label2) ? $label2 : 'Gạch' }}</span>
                </div>
                <div class="text-center py-1 w-[30%] md:w-auto">
                  <span data-rate-output class="text-[#2E2F2A] md:text-primary text-[14px] md:text-base leading-[16px]">{{ isset($rate2) ? $rate2 : '-- viên/m²' }}</span>
                </div>
                <div class="text-right py-1 w-[35%] md:w-auto flex justify-end">
                  <span data-value-output class="text-[#2E2F2A] md:text-primary font-bold text-[14px] md:text-base leading-[16px]">{{ isset($value2) ? $value2 : '00 viên' }}</span>
                </div>
              </div>
              @endisset

              @else
              <div class="w-full md:col-span-5 pt-1 md:pt-0">
                <p class="text-[12px] italic text-[#2E2F2A] md:text-primary/80 leading-[18px]">
                  {{ isset($areaNote) ? $areaNote : 'Diện tích được làm tròn <br class="md:hidden"> lên đến m² gần nhất.' }}
                </p>
              </div>
              @endisset
            </div>
          </div>

          <div class="space-y-4 pt-4 border-t border-black/10 border-solid mt-2 md:mt-0">
            <input type="checkbox" id="extra-loss-quantity" class="peer sr-only" />
            <label
              for="extra-loss-quantity"
              class="w-fit text-[15px] font-medium text-primary/80 cursor-pointer flex items-center gap-3 select-none peer-checked:[&_.loss-checkbox-box]:border-secondary peer-checked:[&_.loss-checkbox-box]:bg-secondary peer-checked:[&_.loss-checkbox-icon]:opacity-100 peer-focus-visible:[&_.loss-checkbox-box]:ring-2 peer-focus-visible:[&_.loss-checkbox-box]:ring-secondary/40 peer-focus-visible:[&_.loss-checkbox-box]:ring-offset-1"
            >
              <span class="loss-checkbox-box relative flex h-[20px] w-[20px] items-center justify-center outline outline-1 outline-black/20 md:border md:border-black/20 rounded-[2px] md:rounded-none bg-transparent transition-all">
                <svg class="loss-checkbox-icon h-3 w-3 text-white opacity-0 transition-opacity" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.5">
                  <path d="M4 10.5L8 14.5L16 6.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </span>
              <span class="flex items-center gap-2">
                <span class="text-[15px] text-[#2E2F2A]/80 font-medium md:text-primary/80 leading-[22.5px]">Cộng thêm hao hụt</span>
                <span class="w-[20px] h-[20px] rounded-full outline outline-1 outline-[#2E2F2A]/40 md:border md:border-primary/40 md:outline-none text-[#2E2F2A]/60 md:text-primary/60 flex items-center justify-center text-[10px] font-bold leading-[15px]">?</span>
              </span>
            </label>

            <div class="pl-8 space-y-3 hidden peer-checked:block">
              <label class="flex items-center gap-3 cursor-pointer group">
                <div class="relative flex items-center justify-center">
                  <input type="radio" name="loss-rate" value="1.05" class="peer appearance-none w-[20px] h-[20px] border border-black/30 md:border-black/30 rounded-full checked:border-primary transition-all cursor-pointer" checked />
                  <div class="absolute w-2.5 h-2.5 rounded-full bg-[#C76E00] md:bg-primary scale-0 peer-checked:scale-100 transition-transform"></div>
                </div>
                <span class="text-[14px] text-[#2E2F2A] md:text-primary/70 font-medium md:font-medium tracking-tight">Thêm 5%</span>
              </label>
              <label class="flex items-center gap-3 cursor-pointer group">
                <div class="relative flex items-center justify-center">
                  <input type="radio" name="loss-rate" value="1.10" class="peer appearance-none w-[20px] h-[20px] border border-black/30 rounded-full checked:border-primary transition-all cursor-pointer" />
                  <div class="absolute w-2.5 h-2.5 rounded-full bg-[#C76E00] md:bg-primary scale-0 peer-checked:scale-100 transition-transform"></div>
                </div>
                <span class="text-[14px] text-[#2E2F2A] md:text-primary/70 font-medium tracking-tight">Thêm 10% (Đối với gạch dị hình)</span>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

