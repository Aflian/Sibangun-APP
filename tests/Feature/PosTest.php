<?php

test('pos livewire view has one root element', function () {
    $view = file_get_contents(resource_path('views/livewire/pos/index.blade.php'));

    expect(trim($view))->toStartWith('<div ')
        ->and($view)->not->toContain('<!DOCTYPE html>')
        ->and($view)->not->toContain('<html')
        ->and($view)->not->toContain('<body');
});
