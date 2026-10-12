(function () {
    'use strict';

    const MAX_BYTES = 999_999;
    const CHUNK_BYTES = 512 * 1024;
    const IMAGE_EXTENSIONS = new Set(['jpg', 'jpeg', 'png', 'webp', 'heic', 'heif']);
    const ACCEPT_SUFFIX = ',.heic,.heif,image/heic,image/heif,image/heic-sequence,image/heif-sequence';
    const uploadUrl = document.currentScript?.dataset?.uploadUrl || '';
    const heicUrl = document.currentScript?.dataset?.heicUrl || '';
    const processedChange = new WeakSet();
    const continuingForms = new WeakSet();
    const inputProcessing = new WeakMap();
    const inputGeneration = new WeakMap();
    const stagedProgress = new WeakMap();
    const optimizedFiles = new WeakSet();
    const lastGoodFiles = new WeakMap();
    const externalProcessing = new WeakMap();
    let heicLoad = null;

    function extension(file) {
        return (file.name.split('.').pop() || '').toLowerCase();
    }

    function isImageFile(file) {
        return Boolean(file && (String(file.type || '').startsWith('image/') || IMAGE_EXTENSIONS.has(extension(file))));
    }

    function isImageInput(input) {
        const accepted = (input.accept || '').toLowerCase();
        return accepted.includes('image') || /\.(jpe?g|png|webp|heic|heif)/i.test(accepted);
    }

    function isHeic(file) {
        return ['heic', 'heif'].includes(extension(file))
            || ['image/heic', 'image/heif', 'image/heic-sequence', 'image/heif-sequence'].includes(String(file.type || '').toLowerCase());
    }

    function isSupported(file) {
        const mime = String(file?.type || '').toLowerCase();
        return IMAGE_EXTENSIONS.has(extension(file))
            || ['image/jpeg', 'image/png', 'image/webp', 'image/heic', 'image/heif', 'image/heic-sequence', 'image/heif-sequence'].includes(mime);
    }

    function formatBytes(bytes) {
        if (bytes < 1024) return `${bytes} B`;
        if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(0)} KB`;
        return `${(bytes / 1024 / 1024).toFixed(1)} MB`;
    }

    function errorText(error, fallback) {
        return error instanceof Error && error.message ? error.message : fallback;
    }

    function messageElement(input) {
        let element = input.parentElement?.querySelector(':scope > .admin-image-upload-status');
        if (!element) {
            element = document.createElement('small');
            element.className = 'admin-image-upload-status block mt-1 text-xs text-gray-500';
            element.setAttribute('aria-live', 'polite');
            input.insertAdjacentElement('afterend', element);
        }
        return element;
    }

    function setMessage(input, text, error = false) {
        const element = messageElement(input);
        element.textContent = text;
        element.classList.toggle('text-red-600', error);
        element.classList.toggle('text-gray-500', !error);
    }

    function clearStagedTokens(input) {
        stagedProgress.delete(input);
        const form = input.form;
        if (!form || !input.dataset.stagedTokens) return;
        const hidden = form.querySelector('input[name="__staged_images"]');
        if (hidden) {
            try {
                const mapping = JSON.parse(hidden.value || '{}');
                delete mapping[input.dataset.imageFieldName || input.dataset.originalImageName || input.name];
                hidden.value = JSON.stringify(mapping);
                if (Object.keys(mapping).length === 0) hidden.remove();
            } catch (_) {
                hidden.remove();
            }
        }
        input.name = input.dataset.originalImageName || input.name;
        delete input.dataset.stagedTokens;
        delete input.dataset.imageFieldName;
    }

    function canvasBlob(canvas, quality) {
        return new Promise((resolve) => canvas.toBlob(resolve, 'image/webp', quality));
    }

    async function decodeFile(file) {
        let source = file;
        if (isHeic(file)) {
            try {
                return await decodeBrowser(file);
            } catch (_) {
                // Older browsers need the bundled HEIC decoder.
            }
            if (typeof window.heic2any !== 'function') await loadHeicDecoder();
            if (typeof window.heic2any !== 'function') {
                throw new Error('Không tải được bộ đọc ảnh HEIC. Hãy tải lại trang rồi thử lại.');
            }
            const converted = await window.heic2any({ blob: file, toType: 'image/png', quality: 1 });
            source = Array.isArray(converted) ? converted[0] : converted;
            if (!(source instanceof Blob)) throw new Error('Không đọc được ảnh HEIC/HEIF này.');
        }

        return decodeBrowser(source);
    }

    async function decodeBrowser(source) {
        if (typeof createImageBitmap === 'function') {
            try {
                const bitmap = await createImageBitmap(source, { imageOrientation: 'from-image' });
                return { image: bitmap, width: bitmap.width, height: bitmap.height, close: () => bitmap.close?.() };
            } catch (_) {
                // Use the browser image decoder as a compatibility fallback.
            }
        }

        const objectUrl = URL.createObjectURL(source);
        try {
            const image = await new Promise((resolve, reject) => {
                const element = new Image();
                element.onload = () => resolve(element);
                element.onerror = () => reject(new Error('Không đọc được ảnh này. Hãy chọn một file ảnh hợp lệ.'));
                element.src = objectUrl;
            });
            return { image, width: image.naturalWidth, height: image.naturalHeight, close: () => URL.revokeObjectURL(objectUrl) };
        } catch (error) {
            URL.revokeObjectURL(objectUrl);
            throw error;
        }
    }

    function loadHeicDecoder() {
        if (heicLoad) return heicLoad;
        if (!heicUrl) return Promise.reject(new Error('Thiếu bộ đọc ảnh HEIC. Hãy tải lại trang rồi thử lại.'));
        heicLoad = new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = heicUrl;
            script.onload = resolve;
            script.onerror = () => reject(new Error('Không tải được bộ đọc ảnh HEIC. Kiểm tra mạng rồi thử lại.'));
            document.head.appendChild(script);
        }).catch((error) => {
            heicLoad = null;
            throw error;
        });
        return heicLoad;
    }

    function imageLimit(input) {
        const hint = [input.name, input.id, input.closest('form')?.action || ''].join(' ').toLowerCase();
        return /banner|hero|slider/.test(hint) ? 2560 : 2000;
    }

    async function processFile(file, input) {
        if (!isSupported(file)) {
            throw new Error('Định dạng chưa hỗ trợ. Chọn JPG, PNG, WebP, HEIC hoặc HEIF.');
        }

        let decoded;
        try {
            decoded = await decodeFile(file);
        } catch (error) {
            throw new Error(errorText(error, isHeic(file)
                ? 'Không đọc được ảnh HEIC/HEIF này. Hãy xuất lại ảnh trên điện thoại rồi thử lại.'
                : 'Không đọc được ảnh này. File có thể bị hỏng; hãy chọn ảnh khác.'));
        }
        let canvas = null;
        try {
            if (!decoded.width || !decoded.height) throw new Error('Ảnh không có kích thước hợp lệ.');

            const maxDimension = imageLimit(input);
            const scale = Math.min(1, maxDimension / Math.max(decoded.width, decoded.height));
            let width = Math.max(1, Math.round(decoded.width * scale));
            let height = Math.max(1, Math.round(decoded.height * scale));
            canvas = document.createElement('canvas');
            const context = canvas.getContext('2d', { alpha: true });
            if (!context) throw new Error('Trình duyệt không thể xử lý ảnh này.');

            let quality = 0.90;
            let blob = null;
            let drawnWidth = 0;
            let drawnHeight = 0;
            for (let attempt = 0; attempt < 42; attempt++) {
                if (width !== drawnWidth || height !== drawnHeight) {
                    canvas.width = width;
                    canvas.height = height;
                    context.clearRect(0, 0, width, height);
                    context.drawImage(decoded.image, 0, 0, width, height);
                    drawnWidth = width;
                    drawnHeight = height;
                }
                blob = await canvasBlob(canvas, quality);
                if (!blob) throw new Error('Trình duyệt không hỗ trợ xuất WebP.');
                if (blob.type === 'image/webp' && blob.size <= MAX_BYTES) break;

                if (quality > 0.50) {
                    quality = Math.max(0.50, quality - 0.08);
                } else if (Math.max(width, height) > 320) {
                    width = Math.max(1, Math.round(width * 0.85));
                    height = Math.max(1, Math.round(height * 0.85));
                    quality = 0.74;
                } else {
                    blob = null;
                    break;
                }
            }

            if (!blob || blob.type !== 'image/webp' || blob.size > MAX_BYTES) {
                throw new Error('Ảnh này không thể chuyển thành WebP dưới 1MB trên thiết bị hiện tại. Hãy thử ảnh có độ phân giải thấp hơn.');
            }

            const baseName = file.name.replace(/\.[^.]+$/, '') || 'image';
            const optimized = new File([blob], `${baseName}.webp`, { type: 'image/webp', lastModified: Date.now() });
            optimizedFiles.add(optimized);
            return optimized;
        } catch (error) {
            const message = errorText(error, 'Trình duyệt không xử lý được ảnh này. Hãy thử ảnh có độ phân giải thấp hơn.');
            throw new Error(/[\u00c0-\u1ef9]/i.test(message)
                ? message
                : 'Trình duyệt không đủ bộ nhớ hoặc không xử lý được ảnh này. Hãy thử ảnh có độ phân giải thấp hơn.');
        } finally {
            if (canvas) {
                canvas.width = 0;
                canvas.height = 0;
            }
            decoded.close();
        }
    }

    function replaceFiles(input, files) {
        const transfer = new DataTransfer();
        files.forEach((file) => transfer.items.add(file));
        input.files = transfer.files;
    }

    function imageInputs(form) {
        return Array.from(form.querySelectorAll('input[type="file"]')).filter((input) => !input.disabled && isImageInput(input));
    }

    async function prepareInput(input, generation = inputGeneration.get(input)) {
        const files = Array.from(input.files || []);
        if (!files.length || !isImageInput(input)) return;
        clearStagedTokens(input);

        const originalName = input.dataset.originalImageName || input.name;
        input.dataset.originalImageName = originalName;
        input.dataset.imageFieldName = originalName;
        const accepted = [];
        const errors = [];
        for (const file of files) {
            try {
                setMessage(input, `Đang xử lý ${file.name}…`);
                accepted.push(optimizedFiles.has(file) ? file : await processFile(file, input));
            } catch (error) {
                errors.push(`${file.name}: ${errorText(error, 'Không đọc được ảnh này. File có thể bị hỏng.')}`);
            }
        }
        if (generation !== inputGeneration.get(input)) return;
        if (errors.length) {
            const previous = lastGoodFiles.get(input) || [];
            if (previous.length) {
                replaceFiles(input, previous);
                delete input.dataset.imageProcessingError;
            } else {
                input.value = '';
                input.dataset.imageProcessingError = '1';
            }
            setMessage(input, errors.join(' '), true);
            throw new Error(errors.join(' '));
        } else {
            replaceFiles(input, accepted);
            lastGoodFiles.set(input, accepted);
            delete input.dataset.imageProcessingError;
            const beforeBytes = files.reduce((total, file) => total + file.size, 0);
            const afterBytes = accepted.reduce((total, file) => total + file.size, 0);
            setMessage(input, `${accepted.length} ảnh WebP · ${formatBytes(beforeBytes)} → ${formatBytes(afterBytes)} · tối đa 1MB/ảnh`);
        }

        processedChange.add(input);
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function postChunk(file, uploadId, index, total) {
        const body = new FormData();
        body.append('chunk', file.slice(index * CHUNK_BYTES, (index + 1) * CHUNK_BYTES), file.name);
        body.append('upload_id', uploadId);
        body.append('chunk_index', String(index));
        body.append('total_chunks', String(total));
        body.append('original_name', file.name);

        return new Promise((resolve, reject) => {
            const request = new XMLHttpRequest();
            request.open('POST', uploadUrl);
            request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.content || '');
            request.setRequestHeader('Accept', 'application/json');
            request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            request.withCredentials = true;
            request.timeout = 120000;
            request.onload = () => {
                let data = {};
                try { data = JSON.parse(request.responseText || '{}'); } catch (_) {}
                if (request.status < 200 || request.status >= 300) {
                    const validation = data.errors ? Object.values(data.errors).flat().join(' ') : '';
                    const message = request.status === 413
                        ? 'Máy chủ từ chối phần ảnh vì vượt giới hạn tải lên.'
                        : request.status === 401 || request.status === 419
                            ? 'Phiên đăng nhập đã hết hạn. Hãy đăng nhập lại rồi chọn ảnh.'
                            : request.status >= 500
                                ? 'Máy chủ đang gặp sự cố khi nhận ảnh. Hệ thống sẽ thử lại.'
                            : (validation || data.message || 'Máy chủ chưa nhận được ảnh. Hãy thử lại.');
                    const error = new Error(message);
                    error.retryable = request.status === 408 || request.status === 429 || request.status >= 500;
                    reject(error);
                    return;
                }
                resolve(data);
            };
            request.onerror = () => reject(Object.assign(new Error('Mất kết nối khi tải ảnh lên. Kiểm tra mạng rồi thử lại.'), { retryable: true }));
            request.ontimeout = () => reject(Object.assign(new Error('Hết thời gian tải ảnh lên. Hãy thử lại.'), { retryable: true }));
            request.send(body);
        });
    }

    async function postChunkWithRetry(file, uploadId, index, total) {
        for (let attempt = 0; ; attempt++) {
            try {
                return await postChunk(file, uploadId, index, total);
            } catch (error) {
                if (!error.retryable || attempt >= 2) throw error;
                await new Promise((resolve) => setTimeout(resolve, 500 * (2 ** attempt)));
            }
        }
    }

    function uuid() {
        if (window.crypto?.randomUUID) return window.crypto.randomUUID();
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (char) => {
            const random = Math.random() * 16 | 0;
            return (char === 'x' ? random : (random & 3 | 8)).toString(16);
        });
    }

    async function stageFile(file) {
        if (file.type !== 'image/webp' || file.size > MAX_BYTES) {
            throw new Error('Ảnh chưa được tối ưu thành WebP dưới 1MB. Hãy chọn lại ảnh.');
        }
        const total = Math.max(1, Math.ceil(file.size / CHUNK_BYTES));
        const uploadId = uuid();
        let response;
        for (let index = 0; index < total; index++) {
            response = await postChunkWithRetry(file, uploadId, index, total);
        }
        if (!response?.complete || !response.token) throw new Error('Máy chủ chưa nhận đủ ảnh. Hãy thử lại.');
        return response.token;
    }

    function addStagedTokens(form, fieldName, tokens) {
        let hidden = form.querySelector('input[name="__staged_images"]');
        if (!hidden) {
            hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = '__staged_images';
            form.appendChild(hidden);
        }
        let mapping = {};
        try { mapping = JSON.parse(hidden.value || '{}'); } catch (_) {}
        mapping[fieldName] = tokens;
        hidden.value = JSON.stringify(mapping);
    }

    async function stageInput(input) {
        if (input.dataset.stagedTokens) return;
        const fieldName = input.dataset.imageFieldName || input.dataset.originalImageName || input.name;
        if (!fieldName) throw new Error('Không xác định được trường ảnh trong biểu mẫu.');
        const imageFiles = Array.from(input.files || []);
        if (!imageFiles.length) return;

        let progress = stagedProgress.get(input);
        if (!progress || progress.files.length !== imageFiles.length
            || progress.files.some((file, index) => file !== imageFiles[index])) {
            progress = { files: imageFiles, tokens: [] };
            stagedProgress.set(input, progress);
        }
        for (let index = progress.tokens.length; index < imageFiles.length; index++) {
            setMessage(input, `Đang tải ảnh ${index + 1}/${imageFiles.length}: ${imageFiles[index].name}…`);
            progress.tokens.push(await stageFile(imageFiles[index]));
        }
        addStagedTokens(input.form, fieldName, progress.tokens);
        input.dataset.stagedTokens = JSON.stringify(progress.tokens);
        input.dataset.originalImageName = fieldName;
        input.removeAttribute('name');
        setMessage(input, `${progress.tokens.length} ảnh WebP đã sẵn sàng để lưu.`);
    }

    document.addEventListener('change', (event) => {
        const input = event.target;
        if (!(input instanceof HTMLInputElement) || input.type !== 'file') return;
        if (input.dataset.galleryUploadMode === 'ajax') return;
        if (processedChange.has(input)) {
            processedChange.delete(input);
            return;
        }
        if (!isImageInput(input) || !input.files?.length) return;

        event.preventDefault();
        event.stopImmediatePropagation();
        const generation = (inputGeneration.get(input) || 0) + 1;
        inputGeneration.set(input, generation);
        const processing = prepareInput(input, generation).catch((error) => {
            if (generation === inputGeneration.get(input)) {
                setMessage(input, errorText(error, 'Không thể đọc ảnh này. Hãy chọn lại ảnh.'), true);
            }
            throw error;
        }).finally(() => {
            if (inputProcessing.get(input) === processing) inputProcessing.delete(input);
        });
        inputProcessing.set(input, processing);
        processing.catch(() => {});
    }, true);

    document.addEventListener('submit', async (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) return;
        if (continuingForms.has(form)) {
            continuingForms.delete(form);
            return;
        }

        let allInputs = imageInputs(form).filter((input) => input.dataset.galleryUploadMode !== 'ajax');
        let inputs = allInputs.filter((input) => input.name && !input.dataset.stagedTokens && input.files?.length);
        if (!inputs.length && !externalProcessing.get(form)?.size
            && !allInputs.some((input) => inputProcessing.has(input) || input.dataset.imageProcessingError)) return;

        event.preventDefault();
        event.stopImmediatePropagation();
        const submitter = event.submitter instanceof HTMLElement ? event.submitter : undefined;
        try {
            await Promise.all(Array.from(externalProcessing.get(form) || []));
            allInputs = imageInputs(form).filter((input) => input.dataset.galleryUploadMode !== 'ajax');
            inputs = allInputs.filter((input) => input.name && !input.dataset.stagedTokens && input.files?.length);
            for (const input of allInputs) {
                const pending = inputProcessing.get(input);
                if (pending) await pending;
                if (input.dataset.imageProcessingError) throw new Error('Có ảnh chưa xử lý được. Hãy chọn lại ảnh được báo lỗi rồi thử lưu.');
            }
            for (const input of inputs) {
                if (!Array.from(input.files || []).every((file) => file.type === 'image/webp' && file.size <= MAX_BYTES)) {
                    await prepareInput(input);
                }
                await stageInput(input);
            }
            continuingForms.add(form);
            try {
                form.requestSubmit(submitter);
            } finally {
                queueMicrotask(() => continuingForms.delete(form));
            }
        } catch (error) {
            for (const input of inputs) {
                if (!input.dataset.stagedTokens) setMessage(input, error.message || 'Không tải được ảnh lên vùng tạm. Hãy thử lại.', true);
            }
            for (const input of allInputs.filter((item) => item.dataset.imageProcessingError && !inputs.includes(item))) {
                setMessage(input, error.message || 'Hãy chọn lại ảnh được báo lỗi.', true);
            }
        }
    }, true);

    document.addEventListener('reset', (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) return;
        form.querySelectorAll('input[type="file"]').forEach((input) => {
            clearStagedTokens(input);
            lastGoodFiles.delete(input);
            delete input.dataset.imageProcessingError;
            setMessage(input, '');
        });
    }, true);

    function extendAccept(input) {
        const accept = (input.getAttribute('accept') || '').trim();
        if (accept.includes('image') || /\.(jpe?g|png|webp)/i.test(accept)) {
            if (!accept.includes('.heic')) input.setAttribute('accept', `${accept}${ACCEPT_SUFFIX}`);
        }
    }

    function observeInputs(root) {
        root.querySelectorAll?.('input[type="file"]').forEach(extendAccept);
    }

    observeInputs(document);
    new MutationObserver((mutations) => mutations.forEach((mutation) => mutation.addedNodes.forEach((node) => {
        if (node instanceof HTMLElement) {
            if (node.matches('input[type="file"]')) extendAccept(node);
            observeInputs(node);
        }
    }))).observe(document.documentElement, { childList: true, subtree: true });

    window.AdminImageOptimizer = {
        processFile,
        processFiles: async (fileList, input) => {
            const processed = [];
            for (const file of Array.from(fileList || [])) processed.push(await processFile(file, input || document.createElement('input')));
            return processed;
        },
        isImageFile,
        isProcessed: (file) => optimizedFiles.has(file),
        rememberFiles: (input) => lastGoodFiles.set(input, Array.from(input.files || [])),
        trackPending: (form, promise) => {
            if (!form) return promise;
            let pending = externalProcessing.get(form);
            if (!pending) {
                pending = new Set();
                externalProcessing.set(form, pending);
            }
            pending.add(promise);
            promise.finally(() => pending.delete(promise)).catch(() => {});
            return promise;
        },
        maxBytes: MAX_BYTES,
    };
})();
