<section class="w-full pb-0 pt-14 md:pb-8 lg:pb-10 md:pt-14 overflow-hidden">
    <div
        class="w-[85%] max-w-[1320px] mx-auto grid grid-cols-1 md:grid-cols-[571px_1fr] gap-8 md:gap-[48px] items-center">
        <!-- Left Image -->
        <div class="w-full md:w-[571px] h-auto md:h-[575px]" data-aos="fade-right">
            <img src="{{ asset('assets/images/faq2.png') }}" alt="FAQ Image" class="w-full h-full object-cover" />
        </div>

        <!-- Right Content -->
        <div class="flex flex-col items-start justify-start h-full md:pt-[165px] md:pb-[100px]" data-aos="fade-left">
            <p
                class="text-[#2E2F2A] text-sm md:text-[16px] font-bold uppercase tracking-wider mb-2 md:mb-[10px] font-archivo leading-none">
                Bạn có thắc mắc?
            </p>
            <h2
                class="text-[#2E2F2A] text-[24px] md:text-[36px] font-normal leading-tight md:leading-[1.2] mb-3 md:mb-[3px] font-archivo max-w-[484px]">
                Xem các câu hỏi thường gặp
            </h2>
            <p
                class="text-[#2E2F2A] text-[14px] md:text-[16px] font-extralight leading-relaxed mb-6 md:mb-[39px] font-archivo max-w-[648px]">
                Khám phá danh sách các câu hỏi thường gặp và câu trả lời của chúng tôi.
            </p>
            <a href="{{ route('client.faq') }}"
                class="inline-flex items-center justify-center w-[200px] h-[30px] border border-[#909090] text-[#2E2F2A] text-[14px] font-normal font-archivo hover:text-secondary hover:border-secondary transition-all">
                (FAQ) Câu hỏi thường gặp
            </a>
        </div>
    </div>
</section>
