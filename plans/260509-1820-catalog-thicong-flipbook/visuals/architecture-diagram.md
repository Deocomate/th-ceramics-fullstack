# Diagram: Dynamic Customer Service Pages Architecture

## ASCII Version

```
┌─────────────────────────────────────────────────────────┐
│                    DATABASE LAYER                        │
│                                                         │
│  ┌─────────────────┐       ┌──────────────────┐         │
│  │    thi_cong     │       │     catalog      │         │
│  │─────────────────│       │──────────────────│         │
│  │ thi_cong (PK)   │       │ catalog_id (PK)  │         │
│  │ tieu_de         │       │ tieu_de          │         │
│  │ anh             │       │ anh_dai_dien     │         │
│  │ link_youtube    │       │ file (PDF path)  │         │
│  └────────┬────────┘       └────────┬─────────┘         │
└───────────┼─────────────────────────┼───────────────────┘
            │                         │
┌───────────┼─────────────────────────┼───────────────────┐
│           │     CONTROLLER LAYER     │                   │
│           │                         │                   │
│  ┌────────┴───────────────────┐ ┌──┴──────────────────┐ │
│  │ HuongDanThiCongController  │ │ CatalogController   │ │
│  │────────────────────────────│ │─────────────────────│ │
│  │ index() -> $guides         │ │ index() -> featured │ │
│  │                            │ │         -> grid     │ │
│  │                            │ │ read($id) -> PDF    │ │
│  └────────┬───────────────────┘ └──┬──────┬───────────┘ │
└───────────┼─────────────────────────┼──────┼─────────────┘
            │                         │      │
┌───────────┼─────────────────────────┼──────┼─────────────┐
│           │      VIEW LAYER          │      │             │
│           │                         │      │             │
│  ┌────────┴──────────────────┐ ┌────┴──────┴──────────┐ │
│  │ installation-guide-       │ │ catalog-content      │ │
│  │   content.blade.php       │ │   .blade.php         │ │
│  │───────────────────────────│ │──────────────────────│ │
│  │ @foreach($guides)         │ │ @if($featured)       │ │
│  │   2-col zigzag layout     │ │ @foreach($catalogs)  │ │
│  │   title + img + youtube   │ │   grid items         │ │
│  └───────────────────────────┘ └──────────────────────┘ │
│                                                         │
│                        ┌──────────────────────┐         │
│                        │ flipbook.blade.php   │         │
│                        │──────────────────────│         │
│                        │ Standalone HTML page │         │
│                        │ PDF.js + StPageFlip  │         │
│                        │ Fullscreen dark bg   │         │
│                        └──────────────────────┘         │
└─────────────────────────────────────────────────────────┘
```

## Mermaid Version — Phase Architecture

```mermaid
flowchart TB
    subgraph PHASE1["Phase 1: Installation Guide"]
        TC[("thi_cong<br/>table")]
        HDTC["HuongDanThiCongController<br/>::index()"]
        IG["installation-guide-content<br/>.blade.php"]
        TC -->|"latest()->get()"| HDTC
        HDTC -->|"$guides"| IG
    end

    subgraph PHASE2["Phase 2: Catalog List"]
        CAT[("catalog<br/>table")]
        CC["CatalogController<br/>::index()"]
        CCONTENT["catalog-content<br/>.blade.php"]
        CAT -->|"latest()->get()"| CC
        CC -->|"$featuredCatalog + $catalogs"| CCONTENT
    end

    subgraph PHASE3["Phase 3: PDF Flipbook"]
        ROUTE["Route: /tai-catalog/doc/{id}"]
        READ["CatalogController<br/>::read($id)"]
        FB["flipbook.blade.php"]
        PDFJS["PDF.js CDN"]
        FLIP["StPageFlip CDN"]
        ROUTE --> READ
        READ -->|"$catalog"| FB
        FB --> PDFJS
        FB --> FLIP
        CCONTENT -->|"route('tai-catalog.read')"| ROUTE
    end

    style PHASE1 fill:#e8f5e9,stroke:#2e7d32
    style PHASE2 fill:#e3f2fd,stroke:#1565c0
    style PHASE3 fill:#fff3e0,stroke:#e65100
```

## Mermaid Version — Flipbook Rendering Pipeline

```mermaid
sequenceDiagram
    actor User
    participant Browser
    participant PDFJS as PDF.js
    participant Canvas as Canvas API
    participant Flip as StPageFlip

    User->>Browser: Clicks "Xem chi tiết"
    Browser->>Browser: Show loading spinner
    Browser->>PDFJS: pdfjsLib.getDocument(pdfUrl)
    PDFJS-->>Browser: pdf.numPages

    loop Each page 1..N
        Browser->>PDFJS: pdf.getPage(i)
        PDFJS-->>Browser: page object
        Browser->>Canvas: page.render({canvasContext, viewport})
        Canvas-->>Browser: <canvas> element rendered
        Browser->>Browser: Wrap in <div class="my-page">
    end

    Browser->>Browser: Append all .my-page divs to #flipbook
    Browser->>Flip: new St.PageFlip('#flipbook', config)
    Browser->>Flip: pageFlip.loadFromHTML('.my-page')
    Flip-->>Browser: Book initialized
    Browser->>Browser: Hide spinner
    User->>Flip: Click/drag page corner
    Flip-->>User: 3D page flip animation
```

## Data Flow Summary

| Phase | Controller Method | Model | View | Output |
|-------|-------------------|-------|------|--------|
| 1 | `index()` | `ThiCong` | `installation-guide-content` | Zigzag grid rows |
| 2 | `index()` | `Catalog` | `catalog-content` | Featured + grid |
| 3 | `read($id)` | `Catalog` | `flipbook` | Fullscreen PDF reader |
