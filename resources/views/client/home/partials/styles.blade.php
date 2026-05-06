<style>
    .banner-section {
        height: 100%;
        /* padding: 59px 0; */
    }

    @media (min-width: 768px) {
        .banner-section {
            height: 680px;
            padding: 120px 0;
        }
    }

    @media (min-width: 1024px) {
        .banner-section {
            height: 885px;
            padding: 160px 0 100px;
        }
    }

    .banner-carousel {
        width: 75%;
        animation: banner-slide-in 0.3s ease-in-out;
    }

    .banner-slide {
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }

    .banner-slide.active,
    .banner-slide.opacity-100 {
        opacity: 1;
    }

    @keyframes banner-slide-in {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .banner-cta-btn {
        font-family: "Archivo", sans-serif;
        font-style: normal;
        display: inline-block;
        padding: 5px 20px;
        border: 0.5px solid rgba(255, 255, 255, 0.85);
        color: #fff;
        font-weight: 600;
        font-size: 10px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        text-align: center;
        transition: all 0.3s ease;
        border-radius: 4px;
    }

    @media (min-width: 768px) {
        .banner-cta-btn {
            font-family: inherit;
            padding: 12px 36px;
            border: 1.5px solid rgba(255, 255, 255, 0.85);
            font-size: 14px;
        }
    }

    .banner-cta-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: #fff;
    }

    .banner-dot.active {
        background-color: #c76e00;
    }
</style>

<style>
    .works-carousel {
        scroll-behavior: smooth;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .works-carousel::-webkit-scrollbar {
        display: none;
    }

    .works-scrollbar-track {
        height: 4px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 2px;
        position: relative;
    }

    .works-scrollbar-thumb {
        height: 100%;
        background: #c76e00;
        border-radius: 2px;
        min-width: 40px;
        position: absolute;
        top: 0;
        left: 0;
        transition: width 0.1s ease;
        cursor: pointer;
    }

    .works-item-title {
        text-align: center;
        font-family: Archivo, sans-serif;
        font-size: 10px;
        font-style: normal;
        font-weight: 600;
    }

    @media (min-width: 1024px) {
        .works-item-title {
            font-size: 18px;
        }
    }
</style>

<style>
    .partner-card-item {
        flex-shrink: 0;
        width: 40vw;
        /* height: 10rem; */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    /* .partner-card-item:first-child {
    padding: 1.5rem;
  }

  .partner-card-item:not(:first-child) {
    padding-top: 1.5rem;
    padding-bottom: 1.5rem;
  } */

    .partner-image {
        max-width: 86px;
        max-height: 100%;
        object-fit: contain;
    }

    @media (max-width: 640px) {
        .partner-card-item {
            width: 30vw;
        }
    }

    @media (min-width: 1024px) {
        .partner-card-item {
            flex-shrink: 0;
            width: 40vw;
            height: 10rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .partner-card-item:first-child {
            padding: 1.5rem;
        }

        .partner-card-item:not(:first-child) {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .partner-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .partner-card-item {
            width: auto;
            padding: 0;
        }

        .partner-card-item:first-child {
            padding: 0;
        }

        .partner-card-item:not(:first-child) {
            padding-top: 0;
            padding-bottom: 0;
        }
    }
</style>

<!-- GSXD-BLOCK:BEGIN file=components/home-awards.html tag=style idx=001 uid=bb118e65 -->
<style>
    .awards-component .awards-swiper {
        overflow: hidden;
        perspective: 1400px;
    }

    .awards-component .awards-swiper .swiper-wrapper {
        align-items: center;
        transform-style: preserve-3d;
        transition-timing-function: cubic-bezier(0.22, 1, 0.36, 1) !important;
    }

    .awards-component .awards-swiper .swiper-slide {
        --aw-width: 198px;
        --aw-height: 439px;
        --aw-tx: 0px;
        --aw-ty: 14px;
        --aw-tz: -170px;
        --aw-ry: 0deg;
        --aw-opacity: 0;
        width: var(--aw-width) !important;
        height: var(--aw-height) !important;
        aspect-ratio: auto;
        opacity: var(--aw-opacity);
        transform-origin: center center;
        transform-style: preserve-3d;
        transform: translate3d(var(--aw-tx), var(--aw-ty), var(--aw-tz)) rotateY(var(--aw-ry));
        backface-visibility: hidden;
        will-change: width, height, transform, opacity;
        transition:
            width 0.62s cubic-bezier(0.22, 1, 0.36, 1),
            height 0.62s cubic-bezier(0.22, 1, 0.36, 1),
            transform 0.62s cubic-bezier(0.22, 1, 0.36, 1),
            opacity 0.45s ease;
    }

    .awards-component .awards-swiper .swiper-slide:has(+ .swiper-slide-prev),
    .awards-component .awards-swiper .swiper-slide-next+.swiper-slide {
        --aw-width: 198px;
        --aw-height: 439px;
        --aw-opacity: 1;
    }

    .awards-component .awards-swiper .swiper-slide:has(+ .swiper-slide-prev) {
        --aw-tx: -50px;
        --aw-ty: 14px;
        --aw-tz: -150px;
        --aw-ry: 42deg;
    }

    .awards-component .awards-swiper .swiper-slide-prev,
    .awards-component .awards-swiper .swiper-slide-next {
        --aw-width: 258px;
        --aw-height: 515px;
        --aw-opacity: 1;
    }

    .awards-component .awards-swiper .swiper-slide-prev {
        --aw-tx: -14px;
        --aw-ty: 8px;
        --aw-tz: -70px;
        --aw-ry: 26deg;
    }

    .awards-component .awards-swiper .swiper-slide-active {
        --aw-width: 341px;
        --aw-height: 579px;
        --aw-tx: 0px;
        --aw-ty: 0px;
        --aw-tz: 0px;
        --aw-ry: 0deg;
        --aw-opacity: 1;
    }

    .awards-component .awards-swiper .swiper-slide-next {
        --aw-tx: 14px;
        --aw-ty: 8px;
        --aw-tz: -70px;
        --aw-ry: -26deg;
    }

    .awards-component .awards-swiper .swiper-slide-next+.swiper-slide {
        --aw-tx: 50px;
        --aw-ty: 14px;
        --aw-tz: -150px;
        --aw-ry: -42deg;
    }

    @media (min-width: 1024px) and (max-width: 1279px) {

        .awards-component .awards-swiper .swiper-slide:has(+ .swiper-slide-prev),
        .awards-component .awards-swiper .swiper-slide-next+.swiper-slide {
            --aw-width: 160px;
            --aw-height: 355px;
        }

        .awards-component .awards-swiper .swiper-slide:has(+ .swiper-slide-prev) {
            --aw-tx: -18px;
            --aw-ty: 12px;
            --aw-tz: -120px;
            --aw-ry: 38deg;
        }

        .awards-component .awards-swiper .swiper-slide-prev,
        .awards-component .awards-swiper .swiper-slide-next {
            --aw-width: 210px;
            --aw-height: 420px;
        }

        .awards-component .awards-swiper .swiper-slide-prev {
            --aw-tx: -10px;
            --aw-ty: 6px;
            --aw-tz: -55px;
            --aw-ry: 22deg;
        }

        .awards-component .awards-swiper .swiper-slide-active {
            --aw-width: 278px;
            --aw-height: 472px;
            --aw-tx: 0px;
            --aw-ty: 0px;
            --aw-tz: 0px;
            --aw-ry: 0deg;
        }

        .awards-component .awards-swiper .swiper-slide-next {
            --aw-tx: 10px;
            --aw-ty: 6px;
            --aw-tz: -55px;
            --aw-ry: -22deg;
        }

        .awards-component .awards-swiper .swiper-slide-next+.swiper-slide {
            --aw-tx: 18px;
            --aw-ty: 12px;
            --aw-tz: -120px;
            --aw-ry: -38deg;
        }
    }

    @media (max-width: 767px) {

        .awards-component .awards-swiper .swiper-slide,
        .awards-component .awards-swiper .swiper-slide:has(+ .swiper-slide-prev),
        .awards-component .awards-swiper .swiper-slide-prev,
        .awards-component .awards-swiper .swiper-slide-active,
        .awards-component .awards-swiper .swiper-slide-next,
        .awards-component .awards-swiper .swiper-slide-next+.swiper-slide {
            --aw-width: 304px;
            --aw-height: 516px;
            --aw-tx: 0px;
            --aw-ty: 0px;
            --aw-tz: 0px;
            --aw-ry: 0deg;
            --aw-opacity: 1;
            aspect-ratio: 304 / 516;
        }

        .awards-component .awards-swiper .swiper-slide>img {
            position: absolute;
            left: 0;
            top: 0;
            width: 304px;
            height: 516px;
            object-fit: cover;
            background: #d9d9d9;
            transition:
                top 0.78s cubic-bezier(0.22, 1, 0.36, 1),
                height 0.78s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.78s cubic-bezier(0.22, 1, 0.36, 1);
            will-change: top, height, transform;
        }

        .awards-component .awards-swiper .swiper-slide-prev>img,
        .awards-component .awards-swiper .swiper-slide-next>img {
            top: 27px;
            height: 461px;
        }

        .awards-component .awards-swiper .swiper-slide>div.absolute.bottom-0.left-0.right-0 {
            top: 0;
            bottom: 0;
            padding: 0;
            background: linear-gradient(180deg,
                    rgba(0, 0, 0, 0) 0%,
                    rgba(0, 0, 0, 0.7) 100%);
            transition:
                margin-top 0.78s cubic-bezier(0.22, 1, 0.36, 1),
                margin-bottom 0.78s cubic-bezier(0.22, 1, 0.36, 1);
            will-change: margin-top, margin-bottom;
        }

        .awards-component .awards-swiper .swiper-slide-prev>div.absolute.bottom-0.left-0.right-0,
        .awards-component .awards-swiper .swiper-slide-next>div.absolute.bottom-0.left-0.right-0 {
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .awards-component .awards-swiper .swiper-slide>div.absolute.bottom-0.left-0.right-0>p {
            position: absolute;
            left: 21px;
            width: 261px;
            color: #fff;
            font-family: "Archivo", sans-serif;
            font-size: 14px;
            font-weight: 600;
            line-height: 20px;
            white-space: normal;
            word-wrap: break-word;
        }

        .awards-component .awards-swiper .swiper-slide-active>div.absolute.bottom-0.left-0.right-0>p {
            bottom: 23px;
        }

        .awards-component .awards-swiper .swiper-slide-prev>div.absolute.bottom-0.left-0.right-0>p,
        .awards-component .awards-swiper .swiper-slide-next>div.absolute.bottom-0.left-0.right-0>p {
            bottom: 23px;
        }

        .awards-mobile-lightbox {
            transition: opacity 0.3s ease;
        }

        .awards-mobile-lightbox.is-open {
            pointer-events: auto;
            opacity: 1;
        }

        .awards-mobile-lightbox-image,
        .awards-mobile-lightbox-close {
            transition:
                opacity 0.3s ease,
                transform 0.3s ease;
        }

        .awards-mobile-lightbox.is-open .awards-mobile-lightbox-image,
        .awards-mobile-lightbox.is-open .awards-mobile-lightbox-close {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
</style>
