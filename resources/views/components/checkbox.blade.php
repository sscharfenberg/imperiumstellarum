<div class="checkbox__wrapper @if($error == '1') invalid @endif">
    <label class="checkbox__label" for="{{ $refId }}">
        <input
            type="checkbox"
            name="{{ $refId }}"
            id="{{ $refId }}"
            {{ $selected ? 'checked' : '' }} />
        <span class="checkbox__inner"></span>
    </label>
    <label class="checkbox__text" for="{{ $refId }}">
        {{ $slot }}
    </label>
</div>
