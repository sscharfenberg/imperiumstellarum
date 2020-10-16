<div class="app-switch">
    <input
        type="checkbox"
        id="{{ $refId }}"
        name="{{ $refId }}"
        {{ $isChecked ? 'checked' : '' }}
        {{ $data ? 'data-'.$data : '' }}
    />
    <label class="off" for="{{ $refId }}">{{ $labelOff }}</label>
    <label class="switch" for="{{ $refId }}"></label>
    <label class="on" for="{{ $refId }}">{{ $labelOn }}</label>
</div>
