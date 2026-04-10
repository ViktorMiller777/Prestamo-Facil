@extends('layouts.app')

@section('title', 'Expediente digital — ' . ($folio ?? ''))

@section('content')
<style>
    .page-wrapper { background: #F0F2F7; min-height: 100vh; font-family: 'DM Sans', sans-serif; }

    /* ── Topbar breadcrumb ── */
    .topbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 20px 32px 14px 32px;
    }
    .breadcrumb-row { display: flex; align-items: center; gap: 6px; }
    .breadcrumb-back {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: .83rem; color: #64748B; text-decoration: none; font-weight: 500;
        transition: color .12s;
    }
    .breadcrumb-back:hover { color: #2563EB; }
    .breadcrumb-back svg { width: 14px; height: 14px; stroke: currentColor; }
    .breadcrumb-sep { color: #CBD5E1; font-size: .9rem; }
    .breadcrumb-title { font-size: 1.25rem; font-weight: 700; color: #0B1F3A; }
    .breadcrumb-sub   { font-size: .82rem; color: #64748B; margin-top: 2px; }

    /* ── Hero card ── */
    .hero-card {
        margin: 0 32px 20px 32px;
        background: #0B1F3A;
        border-radius: 14px;
        padding: 22px 28px;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .hero-avatar {
        width: 54px; height: 54px;
        border-radius: 50%;
        background: #2563EB;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem; font-weight: 700; color: #fff;
        flex-shrink: 0;
        font-family: 'DM Sans', sans-serif;
    }
    .hero-info { flex: 1; min-width: 0; }
    .hero-name { font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 2px; }
    .hero-addr { font-size: .83rem; color: #94A3B8; margin-bottom: 8px; }
    .hero-meta { display: flex; align-items: center; gap: 14px; }
    .badge-status {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 99px;
        font-size: .78rem; font-weight: 600;
    }
    .badge-status.revision { background: #FEF3C7; color: #92400E; }
    .badge-status .dot { width: 7px; height: 7px; border-radius: 50%; background: #D97706; }
    .hero-date { font-size: .80rem; color: #94A3B8; }
    .hero-right { text-align: right; flex-shrink: 0; }
    .hero-folio-label { font-size: .70rem; color: #64748B; text-transform: uppercase; letter-spacing: .07em; margin-bottom: 4px; }
    .hero-folio-value {
        font-family: 'DM Mono', monospace;
        font-size: 1rem; font-weight: 500; color: #2563EB;
    }
    .hero-docs { font-size: .78rem; color: #94A3B8; margin-top: 4px; }

    /* ── Grid layout ── */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 16px;
        padding: 0 32px 32px 32px;
        align-items: start;
    }
    .col-left  { display: flex; flex-direction: column; gap: 16px; }
    .col-right { display: flex; flex-direction: column; gap: 16px; }

    /* ── Section card ── */
    .section-card {
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        padding: 20px 22px;
    }
    .section-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 18px;
    }
    .section-title { font-size: .95rem; font-weight: 700; color: #0B1F3A; }
    .badge-count {
        padding: 3px 10px; border-radius: 99px;
        font-size: .75rem; font-weight: 700;
    }
    .badge-count.amber { background: #FEF3C7; color: #92400E; }
    .badge-count.red   { background: #FEE2E2; color: #991B1B; }

    /* ── Documentos grid ── */
    .docs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .doc-card {
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        padding: 14px 16px;
    }
    .doc-card.uploaded  { border-color: #BBF7D0; }
    .doc-card.missing   { border-color: #FECACA; border-style: dashed; }
    .doc-name { font-size: .86rem; font-weight: 600; color: #0B1F3A; margin-bottom: 8px; }
    .doc-preview-link {
        font-size: .78rem; color: #2563EB; text-decoration: none; font-weight: 500;
        display: block; margin-bottom: 10px;
    }
    .doc-preview-link:hover { text-decoration: underline; }
    .doc-footer { display: flex; align-items: center; gap: 10px; }
    .doc-status-ok   { font-size: .78rem; color: #16A34A; font-weight: 600; }
    .doc-status-miss { font-size: .78rem; color: #EF4444; font-weight: 600; }
    .doc-link-ver {
        font-size: .78rem; color: #2563EB; font-weight: 600;
        text-decoration: none; cursor: pointer;
    }
    .doc-link-ver:hover { text-decoration: underline; }
    .doc-upload-btn {
        font-size: .80rem; color: #2563EB; font-weight: 600;
        background: none; border: none; cursor: pointer;
        font-family: 'DM Sans', sans-serif; padding: 0;
    }

    /* ── Fotos vehículo grid ── */
    .fotos-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .foto-slot {
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        aspect-ratio: 4/3;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: 6px;
        background: #F8FAFC;
        cursor: pointer;
        transition: border-color .15s, background .15s;
        position: relative;
        overflow: hidden;
    }
    .foto-slot:hover { border-color: #2563EB; background: #EFF6FF; }
    .foto-slot.loaded { background: #DBEAFE; border-color: #93C5FD; }
    .foto-slot.missing { border-style: dashed; }
    .foto-label {
        font-size: .75rem; font-weight: 600; color: #64748B;
        position: absolute; bottom: 8px; left: 0; right: 0; text-align: center;
    }
    .foto-label.error { color: #EF4444; }
    .foto-add  { font-size: .78rem; color: #94A3B8; }
    .foto-loaded-text { font-size: .78rem; color: #2563EB; font-weight: 500; }

    /* ── Datos personales / vehículo / familiar ── */
    .datos-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
    .btn-edit {
        font-size: .83rem; color: #2563EB; font-weight: 600;
        background: none; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif;
    }
    .datos-grid   { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 18px 12px; }
    .datos-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px 12px; }
    .dato-label { font-size: .72rem; color: #94A3B8; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px; }
    .dato-value { font-size: .90rem; color: #0B1F3A; font-weight: 500; }
    .dato-value.mono { font-family: 'DM Mono', monospace; font-size: .84rem; }

    /* ── Bottom notice ── */
    .notice-text { font-size: .80rem; color: #94A3B8; margin-top: 14px; line-height: 1.5; }

    @media (max-width: 1024px) {

    .topbar { padding: 20px; }
    .breadcrumb-title { font-size: 1rem; }

    .hero-card { margin: 0 16px 16px 16px; flex-direction: column; align-items: flex-start; gap: 14px; }
    .hero-right { text-align: left; }

    .content-grid { grid-template-columns: 1fr; padding: 0 16px 32px 16px; }

    .datos-grid   { grid-template-columns: 1fr 1fr; }
    .datos-grid-2 { grid-template-columns: 1fr 1fr; }

    .docs-grid { grid-template-columns: 1fr; }

    .fotos-grid { grid-template-columns: 1fr 1fr; }
}
</style>

<div class="page-wrapper">

    {{-- ── TOPBAR BREADCRUMB ── --}}
    <div class="topbar">
        <div>
            <div class="breadcrumb-row">
                <a href="{{ route('verificador.bandeja') }}" class="breadcrumb-back">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    Bandeja
                </a>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-title">Expediente — {{ $folio ?? '' }}</span>
            </div>
            <div class="breadcrumb-sub">{{ $nombre ?? '' }}</div>
        </div>
    </div>

    {{-- ── HERO CARD ── --}}
    <div class="hero-card">
        <div class="hero-avatar">{{ $iniciales ?? '' }}</div>
        <div class="hero-info">
            <div class="hero-name">{{ $nombre ?? '' }}</div>
            <div class="hero-addr">{{ $domicilio ?? '' }}</div>
            <div class="hero-meta">
                <span class="badge-status revision"><span class="dot"></span>En revisión</span>
                <span class="hero-date">Solicitud: {{ $fecha_solicitud ?? '' }}</span>
            </div>
        </div>
        <div class="hero-right">
            <div class="hero-folio-label">Folio de presolicitud</div>
            <div class="hero-folio-value">{{ $folio ?? '' }}</div>
            <div class="hero-docs">Expediente: {{ $docs_cargados ?? 0 }} de {{ $docs_total ?? 0 }} documentos</div>
        </div>
    </div>

    {{-- ── GRID ── --}}
    <div class="content-grid">

        {{-- ── COL IZQUIERDA ── --}}
        <div class="col-left">

            {{-- Documentos de identidad y domicilio --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">Documentos de identidad y domicilio</span>
                    <span class="badge-count amber">{{ $docs_cargados ?? 0 }}/{{ $docs_total ?? 0 }} docs</span>
                </div>
                <div class="docs-grid">

                    {{-- INE — Distribuidores.INE --}}
                    <div class="doc-card uploaded">
                        <div class="doc-name">INE</div>
                        <a href="#" class="doc-preview-link">Vista previa · INE.pdf</a>
                        <div class="doc-footer">
                            <span class="doc-status-ok">✓ Cargado</span>
                            <a href="#" class="doc-link-ver">Ver</a>
                        </div>
                    </div>

                    {{-- Comprobante de domicilio — Distribuidores.comprobante_domicilio --}}
                    <div class="doc-card uploaded">
                        <div class="doc-name">Comprobante de domicilio</div>
                        <a href="#" class="doc-preview-link">Vista previa · CFE.pdf</a>
                        <div class="doc-footer">
                            <span class="doc-status-ok">✓ Cargado</span>
                            <a href="#" class="doc-link-ver">Ver</a>
                        </div>
                    </div>

                    {{-- Reporte de Buró de Crédito --}}
                    <div class="doc-card uploaded">
                        <div class="doc-name">Reporte de Buró de Crédito</div>
                        <a href="#" class="doc-preview-link">Vista previa · BURO.pdf</a>
                        <div class="doc-footer">
                            <span class="doc-status-ok">✓ Cargado</span>
                            <a href="#" class="doc-link-ver">Ver</a>
                        </div>
                    </div>

                    {{-- Foto vehículo principal --}}
                    <div class="doc-card missing">
                        <div class="doc-name">Foto de vehículo principal</div>
                        <div style="margin-bottom:10px;">
                            <button class="doc-upload-btn">+ Subir documento</button>
                        </div>
                        <div class="doc-footer">
                            <span class="doc-status-miss">✗ Faltante</span>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Datos personales capturados — tabla Personas --}}
            <div class="section-card">
                <div class="datos-header">
                    <span class="section-title">Datos personales capturados</span>
                    <button class="btn-edit">Editar</button>
                </div>
                <div class="datos-grid">
                    <div>
                        <div class="dato-label">Nombre completo</div>
                        <div class="dato-value">{{ $nombre ?? '' }}</div>
                    </div>
                    <div>
                        <div class="dato-label">Sexo</div>
                        <div class="dato-value">{{ $sexo ?? '' }}</div>
                    </div>
                    <div>
                        <div class="dato-label">Fecha de nacimiento</div>
                        <div class="dato-value">{{ $fecha_nacimiento ?? '' }}</div>
                    </div>
                    <div>
                        <div class="dato-label">CURP</div>
                        <div class="dato-value mono">{{ $curp ?? '' }}</div>
                    </div>
                    <div>
                        <div class="dato-label">RFC</div>
                        <div class="dato-value mono">{{ $rfc ?? '' }}</div>
                    </div>
                    <div>
                        <div class="dato-label">Teléfono personal</div>
                        <div class="dato-value">{{ $telefono_personal ?? '' }}</div>
                    </div>
                    <div>
                        <div class="dato-label">Celular</div>
                        <div class="dato-value">{{ $celular ?? '' }}</div>
                    </div>
                    <div>
                        <div class="dato-label">Domicilio</div>
                        <div class="dato-value">{{ $calle ?? '' }}</div>
                    </div>
                    <div>
                        <div class="dato-label">Ciudad</div>
                        <div class="dato-value">{{ $ciudad ?? '' }}</div>
                    </div>
                </div>
                <p class="notice-text">Faltan documentos antes de enviar al coordinador.</p>
            </div>

        </div>{{-- /col-left --}}

        {{-- ── COL DERECHA ── --}}
        <div class="col-right">

            {{-- Fotos del vehículo --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">Fotos del vehículo</span>
                    <span class="badge-count red">1/4 fotos</span>
                </div>
                <div class="fotos-grid">

                    <div class="foto-slot loaded">
                        <span class="foto-loaded-text">Foto frontal cargada</span>
                        <span class="foto-label">Frente</span>
                    </div>

                    <div class="foto-slot missing">
                        <span class="foto-add">+ Subir foto</span>
                        <span class="foto-label error">Lateral derecho</span>
                    </div>

                    <div class="foto-slot missing">
                        <span class="foto-add">+ Subir foto</span>
                        <span class="foto-label error">Lateral izquierdo</span>
                    </div>

                    <div class="foto-slot missing">
                        <span class="foto-add">+ Subir foto</span>
                        <span class="foto-label error">Trasera</span>
                    </div>

                </div>
            </div>

            {{-- Datos del vehículo — tabla Datos_vehiculo --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">Datos del vehículo</span>
                </div>
                <div class="datos-grid-2">
                    <div>
                        <div class="dato-label">Marca</div>
                        <div class="dato-value">{{ $marca ?? '' }}</div>
                    </div>
                    <div>
                        <div class="dato-label">Modelo</div>
                        <div class="dato-value">{{ $modelo ?? '' }}</div>
                    </div>
                    <div>
                        <div class="dato-label">Color</div>
                        <div class="dato-value">{{ $color ?? '' }}</div>
                    </div>
                    <div>
                        <div class="dato-label">Número de placas</div>
                        <div class="dato-value mono">{{ $numero_placas ?? '' }}</div>
                    </div>
                </div>
            </div>

            {{-- Familiar de referencia — tabla Afiliales --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">Familiar de referencia</span>
                </div>
                <div class="datos-grid-2">
                    <div>
                        <div class="dato-label">Nombre</div>
                        <div class="dato-value">{{ $familiar_nombre ?? '' }}</div>
                    </div>
                    <div>
                        <div class="dato-label">Parentesco</div>
                        <div class="dato-value">{{ $familiar_parentesco ?? '' }}</div>
                    </div>
                </div>
            </div>

        </div>{{-- /col-right --}}

    </div>{{-- /content-grid --}}

</div>{{-- /page-wrapper --}}
@endsection