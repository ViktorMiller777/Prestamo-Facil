@extends('layouts.app')

@section('title', 'Validación de domicilio — ' . $folio)

@section('content')
<style>
    .page-wrapper { background: #F0F2F7; min-height: 100vh; font-family: 'DM Sans', sans-serif; }

    /* ── Topbar breadcrumb ── */
    .topbar {
        display: flex;
        align-items: flex-start;
        padding: 20px 32px 14px 32px;
    }
    .breadcrumb-row { display: flex; align-items: center; gap: 6px; }
    .breadcrumb-back {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: .83rem; color: #2563EB; text-decoration: none; font-weight: 600;
        transition: color .12s;
    }
    .breadcrumb-back:hover { color: #1D4ED8; }
    .breadcrumb-back svg { width: 14px; height: 14px; stroke: currentColor; }
    .breadcrumb-sep { color: #CBD5E1; font-size: .9rem; }
    .breadcrumb-title { font-size: 1.25rem; font-weight: 700; color: #0B1F3A; }
    .breadcrumb-sub   { font-size: .82rem; color: #64748B; margin-top: 2px; }

    /* ── Grid principal ── */
    .main-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        padding: 0 32px 32px 32px;
        align-items: start;
    }

    /* ── Panel izquierdo ── */
    .left-panel { display: flex; flex-direction: column; gap: 16px; }

    /* ── Section card ── */
    .section-card {
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        padding: 22px 24px;
    }
    .section-label {
        font-size: .70rem;
        font-weight: 700;
        letter-spacing: .09em;
        color: #94A3B8;
        text-transform: uppercase;
        margin-bottom: 16px;
    }

    /* ── Datos interesado ── */
    .dato-group { margin-bottom: 14px; }
    .dato-group:last-child { margin-bottom: 0; }
    .dato-lbl { font-size: .75rem; color: #94A3B8; font-weight: 500; margin-bottom: 3px; }
    .dato-val { font-size: .95rem; font-weight: 600; color: #0B1F3A; }
    .dato-val.mono { font-family: 'DM Mono', monospace; font-size: .88rem; }

    /* ── Buscador ── */
    .search-wrap {
        display: flex;
        align-items: center;
        background: #0B1F3A;
        border-radius: 10px;
        padding: 13px 16px;
        gap: 10px;
        margin-bottom: 14px;
    }
    .search-wrap svg { width: 17px; height: 17px; stroke: #94A3B8; flex-shrink: 0; }
    .search-input {
        background: none;
        border: none;
        outline: none;
        color: #fff;
        font-size: .95rem;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        flex: 1;
    }
    .search-input::placeholder { color: #64748B; }

    .coords-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .coord-group {}
    .coord-lbl { font-size: .72rem; color: #94A3B8; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 5px; }
    .coord-input {
        width: 100%;
        padding: 9px 12px;
        border: 1.5px solid #E2E8F0;
        border-radius: 8px;
        font-size: .88rem;
        font-family: 'DM Mono', monospace;
        color: #0B1F3A;
        background: #fff;
        outline: none;
        box-sizing: border-box;
        transition: border-color .15s;
    }
    .coord-input:focus { border-color: #2563EB; }

    /* ── Estado de validación ── */
    .alert-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 14px 16px;
        border-radius: 10px;
        font-size: .86rem;
        font-weight: 500;
        line-height: 1.5;
    }
    .alert-box.amber { background: #FEF9EC; color: #92400E; border: 1.5px solid #FDE68A; }
    .alert-box .alert-dot {
        width: 9px; height: 9px; border-radius: 50%;
        margin-top: 4px; flex-shrink: 0;
    }
    .alert-box.amber .alert-dot { background: #D97706; }

    /* ── Lista de verificación ── */
    .checklist { display: flex; flex-direction: column; gap: 10px; }
    .check-item { display: flex; align-items: center; gap: 10px; font-size: .88rem; color: #0B1F3A; font-weight: 500; }
    .check-icon {
        width: 20px; height: 20px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: .72rem; font-weight: 700;
    }
    .check-icon.ok      { background: #DCFCE7; color: #16A34A; }
    .check-icon.pending { background: #F1F5F9; color: #94A3B8; border: 1.5px solid #E2E8F0; }

    /* ── Notas textarea ── */
    .notas-textarea {
        width: 100%;
        min-height: 110px;
        padding: 14px 16px;
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        background: #1E293B;
        color: #94A3B8;
        font-size: .86rem;
        font-family: 'DM Sans', sans-serif;
        resize: vertical;
        outline: none;
        box-sizing: border-box;
        transition: border-color .15s;
    }
    .notas-textarea::placeholder { color: #475569; }
    .notas-textarea:focus { border-color: #2563EB; }

    /* ── Botones acción ── */
    .actions-row { display: flex; gap: 10px; margin-top: 4px; }
    .btn-action {
        flex: 1;
        padding: 11px 0;
        border-radius: 9px;
        font-size: .88rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: background .15s;
    }
    .btn-rechazar { background: #FEE2E2; color: #991B1B; }
    .btn-rechazar:hover { background: #FECACA; }
    .btn-confirmar { background: #2563EB; color: #fff; }
    .btn-confirmar:hover { background: #1D4ED8; }

    /* ── Panel derecho — mapa ── */
    .right-panel { display: flex; flex-direction: column; gap: 12px; position: sticky; top: 20px; }

    .map-controls-row { display: flex; gap: 8px; align-items: flex-start; }

    /* Pin card */
    .pin-card {
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 12px;
        padding: 14px 16px;
        flex: 1;
    }
    .pin-card-title { font-size: .86rem; font-weight: 700; color: #0B1F3A; margin-bottom: 4px; }
    .pin-card-addr  { font-size: .78rem; color: #64748B; margin-bottom: 8px; line-height: 1.4; }
    .badge-confirmar {
        display: inline-block;
        padding: 3px 10px;
        background: #FEF3C7;
        color: #92400E;
        font-size: .75rem;
        font-weight: 700;
        border-radius: 99px;
    }

    /* Zoom buttons */
    .zoom-btns { display: flex; flex-direction: column; gap: 4px; }
    .btn-zoom {
        width: 34px; height: 34px;
        border: 1.5px solid #E2E8F0;
        border-radius: 8px;
        background: #fff;
        font-size: 1.1rem;
        font-weight: 600;
        color: #0B1F3A;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background .12s;
    }
    .btn-zoom:hover { background: #F0F2F7; }
    .btn-zoom.layers { font-size: .75rem; color: #64748B; }

    /* Mapa simulado */
    .map-container {
        width: 100%;
        aspect-ratio: 4/3.2;
        border-radius: 14px;
        overflow: hidden;
        border: 1.5px solid #E2E8F0;
        background: #E8EAE3;
        position: relative;
    }
    .map-iframe {
        width: 100%; height: 100%;
        border: none;
        pointer-events: none;
        filter: saturate(.7) brightness(1.05);
    }
    /* Overlay del pin */
    .map-pin-overlay {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -100%);
        display: flex; flex-direction: column; align-items: center;
        pointer-events: none;
    }
    .map-pin-dot {
        width: 18px; height: 18px;
        border-radius: 50% 50% 50% 0;
        background: #2563EB;
        transform: rotate(-45deg);
        box-shadow: 0 2px 8px rgba(37,99,235,.5);
    }
    .map-pin-shadow {
        width: 10px; height: 5px;
        background: rgba(0,0,0,.18);
        border-radius: 50%;
        margin-top: 2px;
    }

    /* Tooltip inferior del mapa */
    .map-tooltip {
        position: absolute;
        bottom: 16px; right: 16px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: .78rem;
        color: #64748B;
        max-width: 160px;
        line-height: 1.4;
        display: flex; align-items: flex-start; gap: 6px;
    }
    .map-tooltip-dot { width: 7px; height: 7px; border-radius: 50%; background: #2563EB; margin-top: 3px; flex-shrink: 0; }
</style>

<div class="page-wrapper">

    {{-- ── TOPBAR ── --}}
    <div class="topbar">
        <div>
            <div class="breadcrumb-row">
                <a href="{{ route('verificador.expediente', ['folio' => $folio]) }}" class="breadcrumb-back">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    Expediente
                </a>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-title">Validación de domicilio — {{ $folio }}</span>
            </div>
            <div class="breadcrumb-sub">{{ $nombre }}</div>
        </div>
    </div>

    {{-- ── GRID ── --}}
    <div class="main-grid">

        {{-- ── COL IZQUIERDA ── --}}
        <div class="left-panel">

            {{-- Datos del interesado --}}
            <div class="section-card">
                <div class="section-label">Datos del interesado</div>
                <div class="dato-group">
                    <div class="dato-lbl">Nombre</div>
                    <div class="dato-val">{{ $nombre }}</div>
                </div>
                <div class="dato-group">
                    <div class="dato-lbl">Domicilio declarado</div>
                    <div class="dato-val">{{ $calle }}</div>
                </div>
                <div class="dato-group">
                    <div class="dato-lbl">Ciudad</div>
                    <div class="dato-val">{{ $ciudad }}</div>
                </div>
                <div class="dato-group">
                    <div class="dato-lbl">C.P.</div>
                    <div class="dato-val mono">{{ $cp }}</div>
                </div>
            </div>

            {{-- Buscar ubicación manualmente --}}
            <div class="section-card">
                <div class="section-label">Buscar ubicación manualmente</div>

                <div class="search-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" class="search-input"
                           placeholder="Calle Hidalgo #412, Torreón"
                           value="{{ $calle }}, {{ $ciudad }}">
                </div>

                <div class="coords-row">
                    <div class="coord-group">
                        <div class="coord-lbl">Latitud</div>
                        <input type="text" class="coord-input" value="{{ $latitud }}" id="lat-input">
                    </div>
                    <div class="coord-group">
                        <div class="coord-lbl">Longitud</div>
                        <input type="text" class="coord-input" value="{{ $longitud }}" id="lng-input">
                    </div>
                </div>
            </div>

            {{-- Estado de validación --}}
            <div class="section-card">
                <div class="section-label">Estado de validación</div>
                <div class="alert-box amber">
                    <span class="alert-dot"></span>
                    Pin colocado manualmente. Confirma que corresponde al domicilio del comprobante.
                </div>
            </div>

            {{-- Lista de verificación --}}
            <div class="section-card">
                <div class="section-label">Lista de verificación</div>
                <div class="checklist">
                    <div class="check-item">
                        <span class="check-icon ok">✓</span>
                        Dirección encontrada en el mapa
                    </div>
                    <div class="check-item">
                        <span class="check-icon ok">✓</span>
                        Colonia coincide con comprobante
                    </div>
                    <div class="check-item">
                        <span class="check-icon ok">✓</span>
                        Municipio dentro de zona de servicio
                    </div>
                    <div class="check-item">
                        <span class="check-icon pending">○</span>
                        Confirmación manual del verificador
                    </div>
                </div>
            </div>

            {{-- Notas del verificador --}}
            <div class="section-card">
                <div class="section-label">Notas del verificador</div>
                <textarea class="notas-textarea"
                          placeholder="Agregar observaciones sobre el domicilio..."></textarea>
                <div class="actions-row" style="margin-top:14px;">
                    <button class="btn-action btn-rechazar">Rechazar domicilio</button>
                    <button class="btn-action btn-confirmar">Confirmar domicilio</button>
                </div>
            </div>

        </div>{{-- /left-panel --}}

        {{-- ── COL DERECHA — MAPA ── --}}
        <div class="right-panel">

            <div class="map-controls-row">
                <div class="pin-card">
                    <div class="pin-card-title">Pin colocado</div>
                    <div class="pin-card-addr">{{ $calle }}<br>Col. Centro, {{ $ciudad_corta }}</div>
                    <span class="badge-confirmar">Por confirmar</span>
                </div>
                <div class="zoom-btns">
                    <button class="btn-zoom">+</button>
                    <button class="btn-zoom">−</button>
                    <button class="btn-zoom layers">⊕</button>
                </div>
            </div>

            {{-- Mapa con OpenStreetMap embebido --}}
            <div class="map-container">
                <iframe
                    class="map-iframe"
                    src="https://www.openstreetmap.org/export/embed.html?bbox=-103.4200,25.5300,-103.3900,25.5550&layer=mapnik&marker={{ $latitud }},{{ $longitud }}"
                    allowfullscreen
                    loading="lazy">
                </iframe>
                <div class="map-pin-overlay">
                    <div class="map-pin-dot"></div>
                    <div class="map-pin-shadow"></div>
                </div>
                <div class="map-tooltip">
                    <span class="map-tooltip-dot"></span>
                    Haz clic en el mapa para mover el pin manualmente
                </div>
            </div>

        </div>{{-- /right-panel --}}

    </div>{{-- /main-grid --}}

</div>{{-- /page-wrapper --}}
@endsection