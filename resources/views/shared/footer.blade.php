<footer class="footer">
    <address class="footer__address">
        @lang("app.footer.handcrafted")
        <span>❤</span>
        @lang("app.footer.name")
    </address>
    <ul class="footer__links">
        <li><a href="{{ route('terms') }}">@lang("app.terms.navTitle")</a></li>
        <li><a href="{{ route('privacy') }}">@lang("app.privacy.navTitle")</a></li>
        <li><a href="{{ route('imprint') }}">@lang("app.imprint.navTitle")</a></li>
    </ul>
</footer>
