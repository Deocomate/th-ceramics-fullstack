/**
 * Shared YouTube embed helpers: no YouTube chrome/title/logo in view,
 * custom simple timeline only, best-effort 1080p.
 */

const CLEAN_PLAYER_PARAMS = {
    enablejsapi: "1",
    rel: "0",
    playsinline: "1",
    modestbranding: "1",
    controls: "0",
    fs: "0",
    iv_load_policy: "3",
    cc_load_policy: "0",
    disablekb: "1",
    vq: "hd1080",
    hd: "1",
};

let youtubeApiPromise = null;

const extractYoutubeId = (value) => {
    if (!value) {
        return null;
    }

    const raw = String(value).trim();
    if (/^[A-Za-z0-9_-]{11}$/.test(raw)) {
        return raw;
    }

    try {
        const parsed = new URL(raw, window.location.origin);
        const host = parsed.hostname.replace(/^www\./, "");

        if (host === "youtu.be") {
            const id = parsed.pathname.split("/").filter(Boolean)[0];
            return id && id.length === 11 ? id : null;
        }

        if (host === "youtube.com" || host === "m.youtube.com" || host === "youtube-nocookie.com") {
            const watchId = parsed.searchParams.get("v");
            if (watchId && watchId.length === 11) {
                return watchId;
            }

            const parts = parsed.pathname.split("/").filter(Boolean);
            const embedIndex = parts.findIndex((part) => ["embed", "shorts", "v", "live"].includes(part));
            const embedId = embedIndex >= 0 ? parts[embedIndex + 1] : null;
            return embedId && embedId.length === 11 ? embedId : null;
        }
    } catch (error) {
        // fall through
    }

    const match = raw.match(/(?:youtu\.be\/|v\/|embed\/|shorts\/|live\/|watch\?v=|&v=)([^#&?/]{11})/);
    return match?.[1] || null;
};

const buildEmbedUrl = (idOrUrl, { autoplay = false, extra = {} } = {}) => {
    const id = extractYoutubeId(idOrUrl);
    if (!id) {
        return "";
    }

    const params = new URLSearchParams({
        ...CLEAN_PLAYER_PARAMS,
        ...extra,
        origin: window.location.origin,
    });

    if (autoplay) {
        params.set("autoplay", "1");
    }

    if (Object.prototype.hasOwnProperty.call(extra, "mute")) {
        params.set("mute", String(extra.mute));
    }

    return `https://www.youtube.com/embed/${id}?${params.toString()}`;
};

const loadYoutubeApi = () => {
    if (window.YT?.Player) {
        return Promise.resolve(window.YT);
    }

    if (youtubeApiPromise) {
        return youtubeApiPromise;
    }

    youtubeApiPromise = new Promise((resolve) => {
        const previous = window.onYouTubeIframeAPIReady;
        window.onYouTubeIframeAPIReady = () => {
            if (typeof previous === "function") {
                previous();
            }
            resolve(window.YT);
        };

        if (!document.querySelector('script[src*="youtube.com/iframe_api"]')) {
            const script = document.createElement("script");
            script.src = "https://www.youtube.com/iframe_api";
            script.async = true;
            document.head.appendChild(script);
        }
    });

    return youtubeApiPromise;
};

const forceHd1080 = (player) => {
    if (!player || typeof player.getAvailableQualityLevels !== "function") {
        return;
    }

    try {
        const levels = player.getAvailableQualityLevels() || [];
        const preferred = ["highres", "hd1080", "hd720", "large"];
        const next = preferred.find((level) => levels.includes(level));

        if (next && typeof player.setPlaybackQuality === "function") {
            player.setPlaybackQuality(next);
        }
    } catch (error) {
        // YouTube may ignore quality overrides; keep playback going.
    }
};

const createTimelineUi = (root) => {
    const bar = document.createElement("div");
    bar.className = "yt-clean-player__bar";
    bar.setAttribute("data-yt-timeline", "");

    const track = document.createElement("button");
    track.type = "button";
    track.className = "yt-clean-player__track";
    track.setAttribute("aria-label", "Thanh thời gian video");

    const progress = document.createElement("span");
    progress.className = "yt-clean-player__progress";
    progress.setAttribute("data-yt-progress", "");

    track.appendChild(progress);
    bar.appendChild(track);
    root.appendChild(bar);

    return { bar, track, progress };
};

const bindTimeline = (player, track, progress) => {
    let rafId = null;
    let seeking = false;

    const sync = () => {
        if (!seeking && typeof player.getCurrentTime === "function" && typeof player.getDuration === "function") {
            const duration = player.getDuration() || 0;
            const current = player.getCurrentTime() || 0;
            const ratio = duration > 0 ? Math.min(1, Math.max(0, current / duration)) : 0;
            progress.style.transform = `scaleX(${ratio})`;
        }

        rafId = window.requestAnimationFrame(sync);
    };

    const seekFromEvent = (event) => {
        const duration = player.getDuration?.() || 0;
        if (!duration) {
            return;
        }

        const rect = track.getBoundingClientRect();
        const ratio = Math.min(1, Math.max(0, (event.clientX - rect.left) / rect.width));
        player.seekTo(duration * ratio, true);
        progress.style.transform = `scaleX(${ratio})`;
    };

    track.addEventListener("pointerdown", (event) => {
        event.preventDefault();
        event.stopPropagation();
        seeking = true;
        seekFromEvent(event);

        const onMove = (moveEvent) => seekFromEvent(moveEvent);
        const onUp = () => {
            seeking = false;
            window.removeEventListener("pointermove", onMove);
            window.removeEventListener("pointerup", onUp);
        };

        window.addEventListener("pointermove", onMove);
        window.addEventListener("pointerup", onUp);
    });

    rafId = window.requestAnimationFrame(sync);

    return () => {
        if (rafId !== null) {
            window.cancelAnimationFrame(rafId);
        }
    };
};

const mountYoutubePlayer = async (host, idOrUrl, { autoplay = true } = {}) => {
    const videoId = extractYoutubeId(idOrUrl);
    if (!host || !videoId) {
        return null;
    }

    host.innerHTML = "";
    host.classList.remove("hidden");

    const root = document.createElement("div");
    root.className = "yt-clean-player";

    const frame = document.createElement("div");
    frame.className = "yt-clean-player__frame";

    const playerElement = document.createElement("div");
    playerElement.className = "yt-clean-player__api";
    frame.appendChild(playerElement);
    root.appendChild(frame);

    const { track, progress } = createTimelineUi(root);
    host.appendChild(root);

    const YT = await loadYoutubeApi();

    return new Promise((resolve) => {
        let stopTimeline = null;

        const player = new YT.Player(playerElement, {
            width: "100%",
            height: "100%",
            videoId,
            playerVars: {
                autoplay: autoplay ? 1 : 0,
                controls: 0,
                modestbranding: 1,
                rel: 0,
                fs: 0,
                iv_load_policy: 3,
                cc_load_policy: 0,
                disablekb: 1,
                playsinline: 1,
                origin: window.location.origin,
            },
            events: {
                onReady(event) {
                    forceHd1080(event.target);
                    stopTimeline = bindTimeline(event.target, track, progress);

                    frame.addEventListener("click", (clickEvent) => {
                        if (clickEvent.target.closest("[data-yt-timeline]")) {
                            return;
                        }

                        const state = event.target.getPlayerState?.();
                        if (state === YT.PlayerState.PLAYING) {
                            event.target.pauseVideo();
                        } else {
                            event.target.playVideo();
                        }
                    });

                    if (autoplay) {
                        try {
                            event.target.playVideo();
                        } catch (error) {
                            // ignore autoplay policy failures
                        }
                    }

                    window.setTimeout(() => forceHd1080(event.target), 400);
                    window.setTimeout(() => forceHd1080(event.target), 1500);
                    resolve(event.target);
                },
                onStateChange(event) {
                    if (
                        event.data === YT.PlayerState.BUFFERING ||
                        event.data === YT.PlayerState.PLAYING
                    ) {
                        forceHd1080(event.target);
                    }
                },
                onError() {
                    if (typeof stopTimeline === "function") {
                        stopTimeline();
                    }
                },
            },
        });

        host._ytStopTimeline = () => {
            if (typeof stopTimeline === "function") {
                stopTimeline();
            }
        };
    });
};

const resetInlineVideoShell = (shell) => {
    if (!shell) {
        return;
    }

    const host = shell.querySelector("[data-yt-player-host]");
    if (host) {
        if (typeof host._ytStopTimeline === "function") {
            host._ytStopTimeline();
            host._ytStopTimeline = null;
        }
        host.innerHTML = "";
        host.classList.add("hidden");
    }

    shell.querySelectorAll("[data-product-video-iframe], [data-inline-video-iframe]").forEach((node) => {
        node.remove();
    });

    const playButton = shell.querySelector("[data-product-video-play], [data-inline-video-play]");
    if (playButton) {
        playButton.classList.remove("hidden");
    }

    shell.dataset.playing = "false";
};

const mountInlineVideoShell = async (shell, { autoplay = true } = {}) => {
    if (!shell) {
        return null;
    }

    const source = shell.dataset.youtubeId || shell.dataset.embedSrc || shell.dataset.videoUrl || "";
    if (!source) {
        return null;
    }

    resetInlineVideoShell(shell);

    const playButton = shell.querySelector("[data-product-video-play], [data-inline-video-play]");
    if (playButton) {
        playButton.classList.add("hidden");
    }

    let host = shell.querySelector("[data-yt-player-host]");
    if (!host) {
        host = document.createElement("div");
        host.setAttribute("data-yt-player-host", "");
        host.className = "absolute inset-0 z-20 w-full h-full bg-black";
        shell.appendChild(host);
    }

    shell.dataset.playing = "true";
    return mountYoutubePlayer(host, source, { autoplay });
};

const initInlineVideoShells = () => {
    if (document.body.dataset.inlineVideoShellsBound === "true") {
        return;
    }

    document.body.dataset.inlineVideoShellsBound = "true";

    document.addEventListener("click", (event) => {
        const playButton = event.target.closest("[data-inline-video-play], [data-product-video-play]");
        if (!playButton) {
            return;
        }

        const shell = playButton.closest("[data-inline-video-shell], [data-product-video-shell]");
        if (!shell) {
            return;
        }

        // Product gallery shells are handled by product-detail.js (swiper-aware).
        if (shell.hasAttribute("data-product-video-shell") && playButton.hasAttribute("data-product-video-play")) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();
        mountInlineVideoShell(shell, { autoplay: true });
    });
};

export {
    CLEAN_PLAYER_PARAMS,
    buildEmbedUrl,
    extractYoutubeId,
    forceHd1080,
    initInlineVideoShells,
    loadYoutubeApi,
    mountInlineVideoShell,
    mountYoutubePlayer,
    resetInlineVideoShell,
};
