@if (session('status'))
    <div class="flash__wrapper">
        <div class="flash__backdrop">
            <div class="flash {{ session('severity') ?? '' }}">
                <header>
                    <h1>
                        @if(session('severity') && session('severity') == "warning")
                            <span class="state-icon"><x-icon name="warning" size="3" /></span>
                            @lang('app.notification.warning')
                        @elseif(session('severity') && session('severity') == "error")
                            <span class="state-icon"><x-icon name="error" size="3" /></span>
                            @lang('app.notification.error')
                        @elseif(session('severity') && session('severity') == "success")
                            <span class="state-icon"><x-success size="30" /></span>
                            @lang('app.notification.success')
                        @else
                            @lang('app.notification.warning')
                        @endif
                        <button class="app-btn close small" id="closeFlash">
                            <x-icon name="cancel" size="2" />
                        </button>
                    </h1>
                </header>
                <div class="message">{{ $slot }}</div>
            </div>
        </div>
    </div>
@endif
