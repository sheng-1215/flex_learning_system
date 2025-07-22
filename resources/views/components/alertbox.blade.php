@props([
    'type' => 'info', // default: info, can be 'success', 'danger', 'warning'
    'dismissible' => false
])

@php
    $alertClasses = [
        'success' => 'alert-success',
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
    ];
@endphp

<div class="alert {{ $alertClasses[$type] ?? 'alert-info' }} {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert">
    {{ $slot }}

</div>
