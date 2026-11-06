<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Asset Tags - MultiSchool ERP</title>
    <style>
        body {
            font-family: Arial, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 20px;
            color: #0f172a;
        }
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .print-btn {
            display: inline-block;
            padding: 10px 24px;
            background: #0284c7;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .print-btn:hover {
            background: #0369a1;
        }
        .page-a4 {
            background: #ffffff;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 10mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.08);
            display: flex;
            flex-wrap: wrap;
            align-content: flex-start;
            gap: 6mm;
            box-sizing: border-box;
        }
        .label {
            width: 60mm;
            height: 38mm;
            border: 1px dashed #cbd5e1;
            padding: 3mm;
            display: flex;
            align-items: center;
            box-sizing: border-box;
            page-break-inside: avoid;
            overflow: hidden;
            border-radius: 4px;
            background: #fff;
        }
        .label .qr {
            width: 24mm;
            height: 24mm;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .label .info {
            padding-left: 3mm;
            flex-grow: 1;
            overflow: hidden;
        }
        .label .school-name {
            font-size: 7pt;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .label .asset-name {
            font-size: 8.5pt;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.15;
            max-height: 2.3em;
            overflow: hidden;
            margin-bottom: 3px;
        }
        .label .tag-code {
            font-family: ui-monospace, SFMono-Regular, monospace;
            font-size: 9pt;
            font-weight: 800;
            color: #0284c7;
            letter-spacing: 0.05em;
            margin-bottom: 2px;
        }
        .label .category {
            font-size: 6.5pt;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .page-a4 {
                box-shadow: none;
                margin: 0;
                padding: 8mm;
                width: auto;
                min-height: auto;
            }
            .label {
                border: 1px solid #94a3b8;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="print-btn">
            🖨️ Print {{ $assets->count() }} Tag{{ $assets->count() > 1 ? 's' : '' }} (A4 Sheet)
        </button>
        <span style="margin-left: 15px; font-size: 0.9rem; color: #64748b;">
            Total: <strong>{{ $assets->count() }}</strong> stickers
        </span>
    </div>

    <div class="page-a4">
        @forelse($assets as $asset)
            <div class="label">
                <div class="qr">
                    {{-- Compact SVG QR placeholder graphic --}}
                    <svg width="78" height="78" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <rect width="100" height="100" fill="#ffffff" />
                        <!-- Top-Left Target -->
                        <rect x="5" y="5" width="28" height="28" fill="#0f172a" rx="2" />
                        <rect x="9" y="9" width="20" height="20" fill="#ffffff" />
                        <rect x="13" y="13" width="12" height="12" fill="#0f172a" />
                        <!-- Top-Right Target -->
                        <rect x="67" y="5" width="28" height="28" fill="#0f172a" rx="2" />
                        <rect x="71" y="9" width="20" height="20" fill="#ffffff" />
                        <rect x="75" y="13" width="12" height="12" fill="#0f172a" />
                        <!-- Bottom-Left Target -->
                        <rect x="5" y="67" width="28" height="28" fill="#0f172a" rx="2" />
                        <rect x="9" y="71" width="20" height="20" fill="#ffffff" />
                        <rect x="13" y="75" width="12" height="12" fill="#0f172a" />
                        <!-- Pattern dots -->
                        <rect x="42" y="12" width="6" height="6" fill="#0f172a" />
                        <rect x="52" y="12" width="6" height="6" fill="#0f172a" />
                        <rect x="42" y="24" width="6" height="6" fill="#0f172a" />
                        <rect x="52" y="36" width="6" height="6" fill="#0f172a" />
                        <rect x="12" y="45" width="6" height="6" fill="#0f172a" />
                        <rect x="24" y="45" width="6" height="6" fill="#0f172a" />
                        <rect x="36" y="45" width="6" height="6" fill="#0f172a" />
                        <rect x="48" y="48" width="6" height="6" fill="#0f172a" />
                        <rect x="60" y="45" width="6" height="6" fill="#0f172a" />
                        <rect x="72" y="45" width="6" height="6" fill="#0f172a" />
                        <rect x="84" y="45" width="6" height="6" fill="#0f172a" />
                        <rect x="42" y="60" width="6" height="6" fill="#0f172a" />
                        <rect x="54" y="60" width="6" height="6" fill="#0f172a" />
                        <rect x="42" y="72" width="6" height="6" fill="#0f172a" />
                        <rect x="66" y="72" width="6" height="6" fill="#0f172a" />
                        <rect x="78" y="72" width="6" height="6" fill="#0f172a" />
                        <rect x="54" y="84" width="6" height="6" fill="#0f172a" />
                        <rect x="72" y="84" width="6" height="6" fill="#0f172a" />
                    </svg>
                </div>
                <div class="info">
                    <div class="school-name">MultiSchool ERP</div>
                    <div class="asset-name" title="{{ $asset->name }}">{{ $asset->name }}</div>
                    <div class="tag-code">{{ $asset->asset_tag }}</div>
                    <div class="category">{{ $asset->category->name ?? 'General Asset' }}</div>
                </div>
            </div>
        @empty
            <p style="text-align: center; width: 100%; color: #64748b; padding-top: 50px;">
                No assets selected for printing.
            </p>
        @endforelse
    </div>

</body>
</html>

