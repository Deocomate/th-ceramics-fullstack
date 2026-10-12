// Builds the minified client assets served from public/assets/dist.
// Run on a dev machine with `composer run build:assets` and commit the result;
// the server never runs this script.
import { execSync } from 'node:child_process';
import { createHash } from 'node:crypto';
import { mkdirSync, readFileSync, readdirSync, rmSync, writeFileSync } from 'node:fs';
import { basename, dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const ESBUILD_VERSION = '0.28.2';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const distDir = 'public/assets/dist';

// app.js is an ES module and is bundled together with everything it imports.
const bundleEntry = 'resources/js/app.js';

// Classic scripts and stylesheets are minified one file at a time.
const standaloneScripts = [
    'resources/js/admin-catalog-page-renderer.js',
    'resources/js/admin-form-ui.js',
    'resources/js/admin-image-optimizer.js',
    'resources/js/admin-product-priority.js',
    'resources/js/admin-product-search.js',
    'resources/js/admin-select.js',
    'resources/js/catalog-flipbook.js',
    'resources/js/content-protection.js',
];
const stylesheets = [
    'resources/css/admin-product-priority.css',
    'resources/css/admin-select.css',
    'resources/css/main.css',
];

const sharedFlags = ['--minify', '--legal-comments=none', '--target=es2019', '--log-level=warning'];

// Every argument is a fixed relative path or flag, so a plain command line is safe;
// going through the shell is what lets Windows resolve the npx.cmd shim.
const esbuild = (args) => execSync(['npx', '--yes', `esbuild@${ESBUILD_VERSION}`, ...args].join(' '), {
    cwd: root,
    stdio: 'inherit',
});

const sha256 = (file) => createHash('sha256').update(readFileSync(join(root, file))).digest('hex');

const builtName = (source) => basename(source).replace(/\.(js|css)$/, '.min.$1');

rmSync(join(root, distDir), { recursive: true, force: true });
mkdirSync(join(root, distDir), { recursive: true });

const metafile = `${distDir}/meta.json`;

esbuild([bundleEntry, '--bundle', '--format=esm', ...sharedFlags,
    `--outfile=${distDir}/${builtName(bundleEntry)}`, `--metafile=${metafile}`]);
esbuild([...standaloneScripts, ...sharedFlags, `--outdir=${distDir}`, '--out-extension:.js=.min.js']);
esbuild([...stylesheets, ...sharedFlags, `--outdir=${distDir}`, '--out-extension:.css=.min.css']);

const bundleSources = Object.keys(JSON.parse(readFileSync(join(root, metafile), 'utf8')).inputs).sort();
rmSync(join(root, metafile));

const sourcesByAsset = {
    [builtName(bundleEntry)]: bundleSources,
    ...Object.fromEntries([...standaloneScripts, ...stylesheets].map((source) => [builtName(source), [source]])),
};

const assets = {};
for (const name of Object.keys(sourcesByAsset).sort()) {
    assets[name] = {
        sha256: sha256(`${distDir}/${name}`),
        sources: Object.fromEntries(sourcesByAsset[name].map((source) => [source, sha256(source)])),
    };
}

writeFileSync(join(root, distDir, 'manifest.json'), `${JSON.stringify({ esbuild: ESBUILD_VERSION, assets }, null, 4)}\n`);

const built = readdirSync(join(root, distDir)).filter((file) => file !== 'manifest.json');
console.log(`Built ${built.length} assets into ${distDir} with esbuild ${ESBUILD_VERSION}.`);
