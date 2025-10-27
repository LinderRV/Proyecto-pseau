<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Prep Academy') }}</title>

    <!-- Site icons / favicons -->
    <!-- Primary: PNG supplied by designer. If you add `public/img/prep-academy-favicon.png`, it will be used by most browsers. -->
    <link rel="icon" type="image/png" href="{{ asset('img/prep-academy-favicon.png') }}">
    <!-- Fallback to SVG and legacy ICO -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/prep-academy-favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/prep-academy-favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Custom CSS -->
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
        
        <!-- Tailwind CSS desde CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="{{ asset('js/tailwind-config.js') }}"></script>
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Helper scripts -->
        <script src="{{ asset('js/logout-helper.js') }}"></script>
        <script>
            // Load SweetAlert2 dynamically when needed, and provide a global confirm handler.
            // This avoids always loading the full SweetAlert2 bundle since the package
            // partial will also dynamically import it for server flashes.
            async function getSweetAlert2(){
                if(window.Swal) return window.Swal;
                try{
                    const mod = await import('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.esm.all.min.js');
                    const Swal = mod.default || mod;
                    // also expose as window.Swal for other code that expects it
                    window.Swal = Swal;
                    return Swal;
                }catch(err){
                    console.error('Failed to load SweetAlert2 dynamically:', err);
                    return null;
                }
            }

            document.addEventListener('DOMContentLoaded', function(){
                // helper to show Swal and submit form if confirmed; falls back to native confirm
                async function handleConfirm(message, proceed){
                    const Swal = await getSweetAlert2();
                    if(Swal && Swal.fire){
                        Swal.fire({
                            title: message || '¿Confirmar acción?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => { if(result.isConfirmed) proceed(); });
                    } else {
                        // fallback to native confirm
                        if(confirm(message || '¿Confirmar acción?')) proceed();
                    }
                }

                // Intercept forms with data-confirm
                document.querySelectorAll('form[data-confirm]').forEach(function(frm){
                    frm.addEventListener('submit', function(e){
                        // allow double-submit if already confirmed
                        if(frm.dataset.confirmed === '1') return;
                        e.preventDefault();
                        const msg = frm.getAttribute('data-confirm') || '¿Confirmar?';
                        handleConfirm(msg, function(){ frm.dataset.confirmed = '1'; frm.submit(); });
                    });
                });

                // Intercept links/buttons that have data-confirm and a data-action-form attribute
                document.querySelectorAll('[data-confirm]').forEach(function(el){
                    if(el.tagName.toLowerCase() === 'form') return; // forms handled above
                    el.addEventListener('click', function(e){
                        // allow normal behavior if element was already confirmed
                        if(el.dataset.confirmed === '1') return;
                        e.preventDefault();
                        const msg = el.getAttribute('data-confirm') || '¿Confirmar?';
                        const formSelector = el.getAttribute('data-action-form');
                        handleConfirm(msg, function(){
                            el.dataset.confirmed = '1';
                            if(formSelector){
                                const f = document.querySelector(formSelector);
                                if(f) f.submit();
                            } else if(el.tagName.toLowerCase()==='a' && el.href){
                                window.location = el.href;
                            } else if(el.type === 'submit'){
                                const f = el.closest('form'); if(f) f.submit();
                            }
                        });
                    });
                });

                // Backwards compat: replace inline onsubmit/onclick that call confirm('...')
                document.querySelectorAll('form[onsubmit]').forEach(function(frm){
                    const os = frm.getAttribute('onsubmit') || '';
                    if(os.includes('confirm(')){
                        const m = os.match(/confirm\((?:'|\")([^'\"]+)(?:'|\")\)/);
                        const msg = m ? m[1] : '¿Confirmar?';
                        frm.removeAttribute('onsubmit');
                        frm.setAttribute('data-confirm', msg);
                    }
                });

                document.querySelectorAll('[onclick]').forEach(function(el){
                    const oc = el.getAttribute('onclick') || '';
                    if(oc.includes('confirm(')){
                        const m = oc.match(/confirm\((?:'|\")([^'\"]+)(?:'|\")\)/);
                        const msg = m ? m[1] : '¿Confirmar?';
                        el.removeAttribute('onclick');
                        el.setAttribute('data-confirm', msg);
                    }
                });
            });
        </script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        {{-- Include SweetAlert2 package partial to render server-side flashes --}}
        @include('sweetalert2::index')
        @auth
            @include('partials.ai-chat')
        @endauth
    </body>
</html>
