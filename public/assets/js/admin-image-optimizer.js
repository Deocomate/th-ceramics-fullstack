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
    let heicLoad = null;

    function extension(file) {
        return (file.name.split('.').pop() || '').toLowerCase();
    }

    function isImageFile(file) {
        return Boolean(file && (String(file.type || '').startsWith('image/') || IMAGE_EXTENSIONS.has(extension(file))));
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
            if (typeof window.heic2any !== 'function') await loadHeicDecoder();
            if (typeof window.heic2any !== 'function') {
                throw new Error('Không tải được bộ đọc ảnh HEIC. Hãy tải lại trang rồi thử lại.');
            }
            const converted = await window.heic2any({ blob: file, toType: 'image/png', quality: 1 });
            source = Array.isArray(converted) ? converted[0] : converted;
            if (!(source instanceof Blob)) throw new Error('Không đọc được ảnh HEIC/HEIF này.');
        }

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

        const decoded = await decodeFile(file);
        try {
            if (!decoded.width || !decoded.height) throw new Error('Ảnh không có kích thước hợp lệ.');

            const maxDimension = imageLimit(input);
            const scale = Math.min(1, maxDimension / Math.max(decoded.width, decoded.height));
            let width = Math.max(1, Math.round(decoded.width * scale));
            let height = Math.max(1, Math.round(decoded.height * scale));
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d', { alpha: true });
            if (!context) throw new Error('Trình duyệt không thể xử lý ảnh này.');

            let quality = 0.90;
            let blob = null;
            for (let attempt = 0; attempt < 42; attempt++) {
                canvas.width = width;
                canvas.height = height;
                context.clearRect(0, 0, width, height);
                context.drawImage(decoded.image, 0, 0, width, height);
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
                throw new Error('Không thể giảm ảnh xuống dưới 1MB mà vẫn giữ được chất lượng phù hợp.');
            }

            const baseName = file.name.replace(/\.[^.]+$/, '') || 'image';
            return new File([blob], `${baseName}.webp`, { type: 'image/webp', lastModified: Date.now() });
        } finally {
            decoded.close();
        }
    }

    function replaceFiles(input, files) {
        const transfer = new DataTransfer();
        files.forEach((file) => transfer.items.add(file));
        input.files = transfer.files;
    }

    function imageInputs(form) {
        return Array.from(form.querySelectorAll('input[type="file"]')).filter((input) => {
            const accepted = (input.accept || '').toLowerCase();
            return accepted.includes('image') || /\.(jpe?g|png|webp|heic|heif)/i.test(accepted)
                || Array.from(input.files || []).some(isImageFile);
        });
    }

    async function prepareInput(input) {
        const files = Array.from(input.files || []);
        if (!files.some(isImageFile)) return;
        clearStagedTokens(input);

        const originalName = input.dataset.originalImageName || input.name;
        input.dataset.originalImageName = originalName;
        input.dataset.imageFieldName = originalName;
        input.disabled = true;
        const accepted = [];
        const errors = [];
        for (const file of files) {
            if (!isImageFile(file)) {
                accepted.push(file);
                continue;
            }
            try {
                setMessage(input, `Đang xử lý ${file.name}…`);
                accepted.push(await processFile(file, input));
            } catch (error) {
                errors.push(`${file.name}: ${error.message}`);
            }
        }
        replaceFiles(input, accepted);
        input.disabled = false;

        if (errors.length) {
            input.dataset.imageProcessingError = '1';
            setMessage(input, errors.join(' '), true);
        } else {
            delete input.dataset.imageProcessingError;
            const beforeBytes = files.filter(isImageFile).reduce((total, file) => total + file.size, 0);
            const afterBytes = accepted.filter(isImageFile).reduce((total, file) => total + file.size, 0);
            setMessage(input, `${accepted.filter(isImageFile).length} ảnh WebP · ${formatBytes(beforeBytes)} → ${formatBytes(afterBytes)} · tối đa 1MB/ảnh`);
        }

        processedChange.add(input);
        input.dispatchEvent(new Event('change', { bubbles: true }));
        if (errors.length) throw new Error(errors.join(' '));
    }

    async function postChunk(file, uploadId, index, total) {
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
                    reject(new Error(request.status === 413 ? 'Máy chủ từ chối một phần ảnh. Hãy thử lại.' : (validation || data.message || `Upload thất bại (HTTP ${request.status}).`)));
                    return;
                }
                resolve(data);
            };
            request.onerror = () => reject(new Error('Mất kết nối khi tải ảnh lên. Kiểm tra mạng rồi thử lại.'));
            request.ontimeout = () => reject(new Error('Hết thời gian tải ảnh lên. Hãy thử lại.'));
            request.send(body);
        });
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
            response = await postChunk(file, uploadId, index, total);
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
        const files = Array.from(input.files || []);
        const imageFiles = files.filter(isImageFile);
        if (!imageFiles.length) return;

        const tokens = [];
        for (let index = 0; index < imageFiles.length; index++) {
            setMessage(input, `Đang tải ảnh ${index + 1}/${imageFiles.length} lên vùng tạm…`);
            tokens.push(await stageFile(imageFiles[index]));
        }
        addStagedTokens(input.form, fieldName, tokens);
        input.dataset.stagedTokens = JSON.stringify(tokens);
        input.dataset.originalImageName = fieldName;
        input.removeAttribute('name');
        setMessage(input, `${tokens.length} ảnh WebP đã sẵn sàng để lưu.`);
    }

    document.addEventListener('change', (event) => {
        const input = event.target;
        if (!(input instanceof HTMLInputElement) || input.type !== 'file') return;
        if (processedChange.has(input)) {
            processedChange.delete(input);
            return;
        }
        if (!Array.from(input.files || []).some(isImageFile)) return;

        event.preventDefault();
        event.stopImmediatePropagation();
        const processing = prepareInput(input).catch((error) => {
            input.disabled = false;
            setMessage(input, error.message || 'Không thể xử lý ảnh này.', true);
            throw error;
        }).finally(() => inputProcessing.delete(input));
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

        const inputs = imageInputs(form).filter((input) => input.name && !input.dataset.stagedTokens
            && Array.from(input.files || []).some(isImageFile));
        if (!inputs.length) return;

        event.preventDefault();
        event.stopImmediatePropagation();
        const submitter = event.submitter instanceof HTMLElement ? event.submitter : undefined;
        try {
            for (const input of inputs) {
                const pending = inputProcessing.get(input);
                if (pending) await pending;
                if (input.dataset.imageProcessingError) throw new Error('Có ảnh chưa xử lý được. Chọn lại ảnh bị báo lỗi rồi thử lại.');
                if (!Array.from(input.files || []).every((file) => !isImageFile(file) || file.type === 'image/webp' && file.size <= MAX_BYTES)) {
                    await prepareInput(input);
                }
                await stageInput(input);
            }
            for (const input of inputs) input.disabled = false;
            continuingForms.add(form);
            form.requestSubmit(submitter);
        } catch (error) {
            for (const input of inputs) {
                input.disabled = false;
                if (!input.dataset.stagedTokens) setMessage(input, error.message || 'Không tải được ảnh lên vùng tạm. Hãy thử lại.', true);
            }
        }
    }, true);

    document.addEventListener('reset', (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) return;
        form.querySelectorAll('input[type="file"]').forEach((input) => {
            clearStagedTokens(input);
            delete input.dataset.imageProcessingError;
            setMessage(input, '');
        });
    }, true);

    function extendAccept(input) {
        const accept = (input.getAttribute('accept') || '').trim();
        if (!accept || accept.includes('image') || /\.(jpe?g|png|webp)/i.test(accept)) {
            if (!accept.includes('.heic')) input.setAttribute('accept', `${accept}${accept ? '' : 'image/*'}${ACCEPT_SUFFIX}`);
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
        maxBytes: MAX_BYTES,
    };
})();
