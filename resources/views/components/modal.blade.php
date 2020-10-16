<div class="modal" data-modal="{{ $refId }}">
    <div class="modal__backdrop">
        <div class="modal__dialog">
            <header class="modal__head">
                <h1>{{ $title }}</h1>
                <button class="app-btn small close" data-cancel>
                    <x-icon name="cancel" size="2" />
                </button>
            </header>
            <main class="modal__body">
                {{ $slot }}
            </main>
        </div>
    </div>
</div>
