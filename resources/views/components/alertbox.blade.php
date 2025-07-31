@props([
    'type' => 'info', // info | success | danger | warning
    'dismissible' => false
])

@php
    $alertClasses = [
        'success' => 'alert-success',
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
    ];

    $icon = [
        'success' => '✅',
        'danger' => '❌',
        'warning' => '⚠️',
        'info' => 'ℹ️',
    ];
@endphp

<style>
    .custom-alert {
        border-radius: 8px;
        padding: 15px 20px;
        font-size: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
        position: relative;
    }
    .custom-alert .close {
        position: absolute;
        top: 10px;
        right: 15px;
        background: none;
        border: none;
        font-size: 20px;
        line-height: 1;
        color: #000;
        opacity: 0.7;
        cursor: pointer;
    }
    .custom-alert .close:hover {
        opacity: 1;
    }
</style>

<div class="alert custom-alert {{ $alertClasses[$type] ?? 'alert-info' }} {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert">
    @if ($slot != '')
    
    <span class="me-2">{{ $icon[$type] ?? 'ℹ️' }}</span>
    
    {{ $slot }}
    @endif

    @if ($dismissible)
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">&times;</button>
    @endif
</div>
