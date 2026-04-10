@extends('layouts.app')

@section('title', 'Notificaciones')

@section('content')
<style>
    .page-wrapper { background: #F0F2F7; min-height: 100vh; font-family: 'DM Sans', sans-serif; }

    /* ── Topbar ── */
    .topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 24px 32px 0 32px;
    }
    .topbar-left { display: flex; align-items: center; gap: 12px; }
    .topbar-title { font-size: 1.5rem; font-weight: 700; color: #0B1F3A; margin: 0; }
    .badge-count {
        background: #DC2626; color: #fff;
        font-size: .78rem; font-weight: 700;
        padding: 2px 9px; border-radius: 99px;
    }
    .btn-marcar {
        font-size: .85rem; color: #2563EB; font-weight: 600;
        background: none; border: none; cursor: pointer;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-marcar:hover { text-decoration: underline; }

    /* ── Main card ── */
    .main-card {
        margin: 16px 32px 32px 32px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        overflow: hidden;
    }

    /* ── Tabs ── */
    .tabs-row {
        display: flex;
        padding: 0 22px;
        border-bottom: 1.5px solid #E2E8F0;
        gap: 4px;
    }
    .tab-btn {
        padding: 14px 16px;
        font-size: .88rem;
        font-weight: 500;
        color: #64748B;
        background: none;
        border: none;
        border-bottom: 2.5px solid transparent;
        margin-bottom: -1.5px;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        white-space: nowrap;
    }
    .tab-btn.active { color: #2563EB; border-bottom-color: #2563EB; font-weight: 700; }

    /* ── Grupo fecha ── */
    .group-label {
        font-size: .70rem;
        font-weight: 700;
        letter-spacing: .09em;
        color: #94A3B8;
        text-transform: uppercase;
        padding: 14px 24px 6px 24px;
        background: #F8FAFC;
    }

    /* ── Item notificación ── */
    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 24px;
        border-bottom: 1px solid #F8FAFC;
        cursor: pointer;
        transition: background .12s;
    }
    .notif-item:last-child { border-bottom: none; }
    .notif-item:hover { background: #F8FAFC; }
    .notif-item.unread { background: #EFF6FF; }
    .notif-item.unread:hover { background: #DBEAFE; }

    .dot-wrap { padding-top: 5px; flex-shrink: 0; }
    .dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
    .dot-green  { background: #16A34A; }
    .dot-blue   { background: #2563EB; }
    .dot-amber  { background: #D97706; }
    .dot-red    { background: #DC2626; }
    .dot-gray   { background: #CBD5E1; }

    .notif-body { flex: 1; }
    .notif-msg  { font-size: .90rem; color: #334155; line-height: 1.55; }
    .notif-msg strong { color: #0B1F3A; font-weight: 700; }
    .notif-time { font-size: .78rem; color: #94A3B8; margin-top: 4px; }

    .unread-pip {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #2563EB;
        margin-top: 6px;
        flex-shrink: 0;
    }
    .read-pip { width: 8px; height: 8px; flex-shrink: 0; }

    /* ── Empty state ── */
    .empty-state {
        padding: 48px 24px;
        text-align: center;
        color: #94A3B8;
        font-size: .88rem;
    }
</style>

<div class="page-wrapper">

    {{-- ── TOPBAR ── --}}
    <div class="topbar">
        <div class="topbar-left">
            <h1 class="topbar-title">Notificaciones</h1>
            {{-- TODO: mostrar conteo real de no leídas desde BD --}}
            <span class="badge-count" id="badge-count">0</span>
        </div>
        <button class="btn-marcar">Marcar todo leído</button>
    </div>

    {{-- ── MAIN CARD ── --}}
    <div class="main-card">

        <div class="tabs-row">
            <button class="tab-btn active">Todas</button>
            {{-- TODO: mostrar conteo real desde BD --}}
            <button class="tab-btn">No leídas</button>
            <button class="tab-btn">Alertas</button>
        </div>

        {{-- TODO: iterar notificaciones agrupadas por fecha desde BD --}}
        {{--
        @forelse($grupos as $fecha => $notificaciones)

            <div class="group-label">{{ strtoupper($fecha) }}</div>

            @foreach($notificaciones as $notif)
            <div class="notif-item {{ $notif->leida ? '' : 'unread' }}">
                <div class="dot-wrap">
                    <div class="dot dot-{{ $notif->tipo }}"></div>
                </div>
                <div class="notif-body">
                    <div class="notif-msg">{!! $notif->mensaje !!}</div>
                    <div class="notif-time">{{ $notif->tiempo }}</div>
                </div>
                @if(!$notif->leida)
                    <div class="unread-pip"></div>
                @else
                    <div class="read-pip"></div>
                @endif
            </div>
            @endforeach

        @empty
            <div class="empty-state">No tienes notificaciones.</div>
        @endforelse
        --}}

        <div class="empty-state">
            Las notificaciones aparecerán aquí cuando se conecte la base de datos.
        </div>

    </div>

</div>

<script>
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
</script>

@endsection