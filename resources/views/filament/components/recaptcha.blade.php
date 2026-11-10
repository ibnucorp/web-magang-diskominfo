<div wire:ignore>
    <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"
    data-callback="onRecaptchaSuccess"
    data-expired-callback="onRecaptchaExpired"></div>
</div>
    @error('data.recaptcha')
    <span class="text-sm text-danger-600">{{ $message }}</span>
    @enderror

    <script>
    //  Skrip bila sukses agar form recaptha tidak null
    function onRecaptchaSuccess(token) {
        @this.set('data.recaptcha', token);
    }

     //  Skrip bila expired
    function onRecaptchaExpired() {
        @this.set('data.recaptcha', '');
        if (typeof grecaptcha !== 'undefined') {
            grecaptcha.reset();
        }
    }
        // Skrip untuk reset captcha, agar field recaptcha kembali null 
        document.addEventListener('livewire:init', () => {
            Livewire.hook('request', ({ fail }) => {
                fail(() => {
                    if (typeof grecaptcha !== 'undefined') {
                        grecaptcha.reset();
                    }
                });
            });
        });        
    </script>