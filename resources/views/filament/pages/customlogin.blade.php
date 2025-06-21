<div>
        @vite('resources/css/app.css') 
    <style>

    main {
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 1rem;
        box-sizing: border-box;
        margin: 0 auto;
        max-width: none !important;
        background: none !important;
        border: none !important;
        box-shadow: none !important;
    }

    @media screen and (min-width: 768px) {
    main {
        padding: 2rem;
        background-image: url('ruta/a/tu/imagen-de-fondo.jpg');
        background-size: cover;
        background-position: center;
    }
    
    .login-container {
        width: 70%;
        max-width: 500px;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    }

    @media screen and (min-width: 1024px) {
    main {
        flex-direction: row;
        padding: 0;
    }
    
    .login-container {
        width: 450px;
        margin: 2rem;
        padding: 3rem;
    }
    
    .login-hero {
        flex: 1;
        background-image: url('ruta/a/tu/imagen-grande.jpg');
        background-size: cover;
        background-position: center;
        min-height: 100vh;
    }
    }

    @media screen and (min-width: 1440px) {
    .login-container {
        width: 500px;
        margin: 3rem;
    }
    }


    @media screen and (max-width: 375px) {
        main {
            padding: 0.5rem;
            justify-content: flex-start;
            padding-top: 2rem;
        }
        
        .login-container {
            width: 100%;
            padding: 1.5rem;
        }
        }

        @keyframes pulseRing {
        0%, 100% {
            transform: scale(1);
            opacity: 0.8;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.3;
        }
    }

    @keyframes digitalParticle {
        0% {
            transform: translate(0, 0);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        100% {
            transform: translate(var(--tx, 50px), var(--ty, -50px));
            opacity: 0;
        }
    }

    @keyframes scanLine {
        0% {
            transform: translateY(-10px);
        }
        100% {
            transform: translateY(35px);
        }
    }

    @keyframes glitch {
        0% {
            transform: translate(0);
        }
        20% {
            transform: translate(-3px, 3px);
        }
        40% {
            transform: translate(-3px, -3px);
        }
        60% {
            transform: translate(3px, 3px);
        }
        80% {
            transform: translate(3px, -3px);
        }
        100% {
            transform: translate(0);
        }
    }

    @keyframes terminalCursor {
        0%, 100% {
            opacity: 0;
        }
        50% {
            opacity: 1;
        }
    }

    .typewriter h4 {
    overflow: hidden; 
    border-right: .15em solid orange; 
    margin: 0 auto; 
    letter-spacing: .15em; 
    animation: 
        typing 3.5s steps(40, end),
        blink-caret .75s step-end infinite;
    }

    @keyframes typing {
    from { width: 0 }
    to { width: 100% }
    }


        @keyframes blink-caret {
        from, to { border-color: transparent }
        50% { border-color: #42bfd8; }
        }

        .glitch-text {
            position: relative;
            color: white;
        }
        .glitch-layers {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .glitch-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: inherit;
        }
        .glitch-layer--1 {
            color: #0ff;
            animation: glitch-anim-1 2s infinite linear alternate-reverse;
            clip-path: polygon(0 20%, 100% 20%, 100% 30%, 0 30%);
        }
        .glitch-layer--2 {
            color: #f0f;
            animation: glitch-anim-2 2s infinite linear alternate-reverse;
            clip-path: polygon(0 60%, 100% 60%, 100% 70%, 0 70%);
        }
        @keyframes glitch-anim-1 {
            0% { transform: translateX(0) }
            20% { transform: translateX(-3px) }
            40% { transform: translateX(3px) }
            60% { transform: translateX(-3px) }
            80% { transform: translateX(3px) }
            100% { transform: translateX(0) }
        }

    </style>

<div class="flex flex-col md:flex-row w-full max-w-6xl mx-4 bg-transparent rounded-2xl overflow-hidden">
    <div class="hidden md:flex flex-col justify-center items-center w-full md:w-1/2 lg:w-2/5 p-8 lg:p-12 bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900 dark:from-gray-900 dark:via-indigo-900 dark:to-black relative overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-1/4 left-1/4 w-32 h-32 rounded-full bg-blue-500 filter blur-3xl opacity-70 animate-pulse"></div>
            <div class="absolute bottom-1/3 right-1/3 w-40 h-40 rounded-full bg-purple-600 filter blur-3xl opacity-50 animate-pulse delay-300"></div>
        </div>
        

        <div class="absolute inset-0 opacity-10 bg-[size:40px_40px] bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)]"></div>
        
        <div class="relative z-10 flex flex-col items-center text-center">
        
            <div class="relative mb-8 group" style="perspective: 1000px;">
                <div class="absolute -inset-4 rounded-full bg-gradient-to-br from-blue-500/10 to-purple-600/10 
                            blur-xl opacity-0 transition-opacity duration-500"></div>
                
                <div class="relative w-28 h-28 rounded-full transition-all duration-700 group-hover:rotate-y-180">
                    <img src="{{ asset('images/logo.png') }}" alt="SOCfusion Logo" 
                        class="absolute w-full h-full rounded-full shadow-2xl border-4 border-white/30 dark:border-gray-800/80 
                                object-cover transition-all duration-500 
                                backdrop-blur-sm bg-white/10 p-1">
                    
                    <div class="absolute w-full h-full rounded-full bg-[url('{{ asset('images/logo.png') }}')] 
                                bg-cover bg-center filter contrast-125 brightness-110 saturate-150 
                                opacity-0 transition-opacity duration-300
                                border-2 border-teal-400/50 shadow-[0_0_15px_rgba(59,130,246,0.5)]">
                        <div class="absolute inset-0 bg-[linear-gradient(135deg,#00f2fe_0%,#4facfe_50%,transparent_100%)] 
                                    mix-blend-overlay opacity-70"></div>
                    </div>
                </div>
                
                <div class="absolute inset-0 rounded-full border-2 border-transparent 
                            border-t-indigo-400/80 border-r-blue-400/60
                            animate-[pulseRing_3s_cubic-bezier(0.4,0,0.6,1)_infinite]"></div>
                <div class="absolute inset-0 rounded-full border-2 border-transparent 
                            border-b-teal-400/80 border-l-cyan-400/60
                            animate-[pulseRing_3s_cubic-bezier(0.4,0,0.6,1)_infinite_1s]"></div>
                
                <div class="absolute inset-0 overflow-hidden rounded-full">
                    <div class="absolute top-1/4 left-1/4 w-1 h-1 bg-blue-400 rounded-full 
                                animate-[digitalParticle_4s_linear_infinite]"></div>
                    <div class="absolute top-3/4 left-1/3 w-1 h-1 bg-teal-400 rounded-full 
                                animate-[digitalParticle_3s_linear_infinite_1s]"></div>
                </div>
            </div>
            
        
            <h2 class="glitch-text text-2xl font-bold mb-3 tracking-wider" data-text="BIENVENIDO A">
                <span class="glitch-layers">
                    <span class="glitch-layer glitch-layer--1">BIENVENIDO A</span>
                    <span class="glitch-layer glitch-layer--2">BIENVENIDO A</span>
                    <span class="glitch-layer glitch-layer--3">BIENVENIDO A</span>
                </span>
                BIENVENIDO A
            </h2>

                
                <span class="text-4xl font-extrabold text-transparent bg-clip-text 
                            bg-gradient-to-r from-indigo-400 via-blue-400 to-teal-400 
                            tracking-wider mb-6 relative">
                    <span class="relative block hover:animate-[glitch_0.5s_linear_infinite]">
                        <span class="absolute top-0 left-0 w-full h-full opacity-0 hover:opacity-100 
                                    text-indigo-300 animate-[glitch_0.5s_linear_infinite_0.1s]">SOCFUSION</span>
                        <span class="absolute top-0 left-0 w-full h-full opacity-0 hover:opacity-100 
                                    text-teal-300 animate-[glitch_0.5s_linear_infinite_0.2s]">SOCFUSION</span>
                        SOCFUSION
                    </span>
                </span>

                
            <div class="text-sm text-white/80 max-w-xs mt-6 font-mono tracking-wide relative overflow-hidden typewriter">
                <h4 class="inline-block whitespace-nowrap">
                    Security Operations Center
                </h4>
            </div>
        </div>
    </div>

    <div class="flex-1 flex flex-col justify-center items-center p-8 sm:p-10 md:p-12 bg-white/5 dark:bg-gray-900/95 backdrop-blur-sm border border-gray-800/10 dark:border-white/10 rounded-2xl md:rounded-none md:border-l-0 relative overflow-hidden">
        <div class="absolute -left-20 -top-20 w-64 h-64 bg-indigo-600/10 rounded-full filter blur-3xl"></div>
        
        <div class="relative z-10 w-full max-w-xs sm:max-w-sm">
            <div class="md:hidden flex justify-center mb-8">
                <div class="relative group">
                    <img src="{{ asset('images/logo.png') }}" alt="SOCfusion Logo" class="w-20 h-20 rounded-full shadow-lg border-4 border-indigo-100/30 dark:border-gray-700 object-cover">
                    <div class="absolute inset-0 rounded-full border border-indigo-400/30 animate-spin-slow"></div>
                </div>
            </div>

            <h3 class="text-xl sm:text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-blue-500 mb-6 text-center tracking-tight">
                INGRESO DE USUARIO
            </h3>

            @if (session()->has('error'))
                <div class="mb-6 px-4 py-3 text-sm font-mono text-red-400 bg-red-900/30 dark:bg-red-900/20 border border-red-700/30 dark:border-red-700/50 rounded-lg backdrop-blur-sm">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="authenticate" class="space-y-6">
                <div class="space-y-5">
                    {{ $this->form }}
                </div>
                
                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-medium rounded-lg shadow-lg hover:shadow-indigo-500/20 transition-all duration-300 transform hover:scale-[1.02] active:scale-95 group">
                    <span class="relative z-10">INICIAR SESIÓN</span>
                    <span class="absolute inset-0 bg-gradient-to-r from-indigo-700 to-blue-700 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300">→</span>
                </button>
            </form>

            <!-- <div class="mt-8 text-center text-xs text-gray-500 dark:text-gray-400 font-mono tracking-wide">
                <span class="opacity-70">¿ACCESO PERDIDO?</span>
                <a href="/password/reset" class="text-indigo-400 hover:text-blue-400 ml-2 transition-colors">
                    RECUPERAR CREDENCIALES
                </a>
            </div> -->
        </div>
    </div>
</div>

</div>

