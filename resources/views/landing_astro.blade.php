<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('astro/favicon.svg') }}" />
        <meta name="generator" content="Astro" />
        <meta name="description" content="Template built with tailwindcss using Tailus blocks v2" />
        <title>SIMPEG - Sistem Informasi Kepegawaian</title>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700&display=swap">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            html {
                font-family: "Urbanist", sans-serif;
            }
        </style>
    </head>
    <body class="bg-white dark:bg-gray-950">
        
        <header>
            <nav class="z-10 w-full absolute">
                <div class="max-w-7xl mx-auto px-6 md:px-12 xl:px-6">
                    <div class="flex flex-wrap items-center justify-between py-2 gap-6 md:py-4 md:gap-0 relative">
                        <input aria-hidden="true" type="checkbox" name="toggle_nav" id="toggle_nav" class="hidden peer">
                        <div class="relative z-20 w-full flex justify-between lg:w-max md:px-0">
                            <a href="#home" aria-label="logo" class="flex space-x-2 items-center">
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">SIMPEG</span>
                            </a>
                            
                            <div class="relative flex items-center lg:hidden max-h-10">
                                <label role="button" for="toggle_nav" aria-label="humburger" id="hamburger" class="relative  p-6 -mr-6">
                                    <div aria-hidden="true" id="line" class="m-auto h-0.5 w-5 rounded bg-sky-900 dark:bg-gray-300 transition duration-300"></div>
                                    <div aria-hidden="true" id="line2" class="m-auto mt-2 h-0.5 w-5 rounded bg-sky-900 dark:bg-gray-300 transition duration-300"></div>
                                </label>
                            </div>
                        </div>
                        <div aria-hidden="true" class="fixed z-10 inset-0 h-screen w-screen bg-white/70 backdrop-blur-2xl origin-bottom scale-y-0 transition duration-500 peer-checked:origin-top peer-checked:scale-y-100 lg:hidden dark:bg-gray-900/70"></div>
                        <div class="flex-col z-20 flex-wrap gap-6 p-8 rounded-3xl border border-gray-100 bg-white shadow-2xl shadow-gray-600/10 justify-end w-full invisible opacity-0 translate-y-1  absolute top-full left-0 transition-all duration-300 scale-95 origin-top 
                                    lg:relative lg:scale-100 lg:peer-checked:translate-y-0 lg:translate-y-0 lg:flex lg:flex-row lg:items-center lg:gap-0 lg:p-0 lg:bg-transparent lg:w-7/12 lg:visible lg:opacity-100 lg:border-none
                                    peer-checked:scale-100 peer-checked:opacity-100 peer-checked:visible lg:shadow-none 
                                    dark:shadow-none dark:bg-gray-800 dark:border-gray-700">
                           
                            <div class="text-gray-600 dark:text-gray-300 lg:pr-4 lg:w-auto w-full lg:pt-0">
                                <ul class="tracking-wide font-medium lg:text-sm flex-col flex lg:flex-row gap-6 lg:gap-0">
                                    <li>
                                        <a href="#features" class="block md:px-4 transition hover:text-primary">
                                            <span>Features</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#solution" class="block md:px-4 transition hover:text-primary">
                                            <span>Solution</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#reviews" class="block md:px-4 transition hover:text-primary">
                                            <span>Reviews</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#blog" class="block md:px-4 transition hover:text-primary">
                                            <span>Blog</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="mt-12 lg:mt-0">
                                <a
                                    href="{{ route('login') }}"
                                    class="relative flex h-9 w-full items-center justify-center px-4 before:absolute before:inset-0 before:rounded-full before:bg-primary before:transition before:duration-300 hover:before:scale-105 active:duration-75 active:before:scale-95 sm:w-max"
                                    >
                                    <span class="relative text-sm font-semibold text-white"
                                    >Login</span
                                    >
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <main class="space-y-40 mb-40">
            <div class="relative" id="home">
                <div aria-hidden="true" class="absolute inset-0 grid grid-cols-2 -space-x-52 opacity-40 dark:opacity-20">
                    <div class="blur-[106px] h-56 bg-gradient-to-br from-primary to-purple-400 dark:from-blue-700"></div>
                    <div class="blur-[106px] h-32 bg-gradient-to-r from-cyan-400 to-sky-300 dark:to-indigo-600"></div>
                </div>
                <div class="max-w-7xl mx-auto px-6 md:px-12 xl:px-6">
                    <div class="relative pt-36 ml-auto">
                        <div class="lg:w-2/3 text-center mx-auto">
                            <h1 class="text-gray-900 text-balance dark:text-white font-bold text-5xl md:text-6xl xl:text-7xl">Masa Depan <span class="text-primary dark:text-white">Tanda Tangan Digital</span> untuk Kepegawaian</h1>
                            <p class="mt-8 text-gray-700 dark:text-gray-300">SIMPEG menghadirkan solusi tanda tangan digital yang aman, sah, dan efisien untuk dokumen kepegawaian Anda. Dengan teknologi enkripsi dan verifikasi QR code, dokumen Anda terjamin keasliannya dan mudah diverifikasi kapan saja, di mana saja.</p>
                            <div class="mt-16 flex flex-wrap justify-center gap-y-4 gap-x-6">
                                <a
                                href="{{ route('register') }}"
                                class="relative flex h-11 w-full items-center justify-center px-6 before:absolute before:inset-0 before:rounded-full before:bg-primary before:transition before:duration-300 hover:before:scale-105 active:duration-75 active:before:scale-95 sm:w-max"
                                >
                                <span class="relative text-base font-semibold text-white"
                                    >Daftar Sekarang</span
                                >
                                </a>
                                <a
                                href="#"
                                class="relative flex h-11 w-full items-center justify-center px-6 before:absolute before:inset-0 before:rounded-full before:border before:border-transparent before:bg-primary/10 before:bg-gradient-to-b before:transition before:duration-300 hover:before:scale-105 active:duration-75 active:before:scale-95 dark:before:border-gray-700 dark:before:bg-gray-800 sm:w-max"
                                >
                                <span
                                    class="relative text-base font-semibold text-primary dark:text-white"
                                    >Pelajari Lebih Lanjut</span
                                >
                                </a>
                            </div>
                            <div class="py-8 mt-16 border-y border-gray-100 dark:border-gray-800 sm:flex justify-between hidden">
                                <div class="text-left">
                                    <h6 class="text-lg font-semibold text-gray-700 dark:text-white">Harga Terjangkau</h6>
                                    <p class="mt-2 text-gray-500">Tanpa biaya tersembunyi, solusi digital untuk semua instansi.</p>
                                </div>
                                <div class="text-left">
                                    <h6 class="text-lg font-semibold text-gray-700 dark:text-white">Proses Cepat & Mudah</h6>
                                    <p class="mt-2 text-gray-500">Tanda tangan dokumen hanya dalam hitungan detik.</p>
                                </div>
                                <div class="text-left">
                                    <h6 class="text-lg font-semibold text-gray-700 dark:text-white">Legal & Terpercaya</h6>
                                    <p class="mt-2 text-gray-500">Tanda tangan digital diakui secara hukum dan terverifikasi.</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-12 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-6 gap-x-6 gap-y-4">
                            <div class="p-4 grayscale transition duration-200 hover:grayscale-0">
                                <img src="{{ asset('astro/images/clients/microsoft.svg') }}" class="h-12 w-auto mx-auto" loading="lazy" alt="client logo" />
                            </div>
                            <div class="p-4 grayscale transition duration-200 hover:grayscale-0">
                            <img src="{{ asset('astro/images/clients/airbnb.svg') }}" class="h-12 w-auto mx-auto" loading="lazy" alt="client logo" />
                            </div>
                            <div class="p-4 flex grayscale transition duration-200 hover:grayscale-0">
                            <img src="{{ asset('astro/images/clients/google.svg') }}" class="h-9 w-auto m-auto" loading="lazy" alt="client logo" />
                            </div>
                            <div class="p-4 grayscale transition duration-200 hover:grayscale-0">
                                <img src="{{ asset('astro/images/clients/ge.svg') }}" class="h-12 w-auto mx-auto" loading="lazy" alt="client logo" />
                            </div>
                            <div class="p-4 flex grayscale transition duration-200 hover:grayscale-0">
                                <img src="{{ asset('astro/images/clients/netflix.svg') }}" class="h-8 w-auto m-auto" loading="lazy" alt="client logo" />
                            </div>
                            <div class="p-4 grayscale transition duration-200 hover:grayscale-0">
                                <img src="{{ asset('astro/images/clients/google-cloud.svg') }}" class="h-12 w-auto mx-auto" loading="lazy" alt="client logo" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="features">
                <div class="max-w-7xl mx-auto px-6 md:px-12 xl:px-6">
                    <div class="md:w-2/3 lg:w-1/2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-secondary">
                        <path fill-rule="evenodd" d="M9 4.5a.75.75 0 01.721.544l.813 2.846a3.75 3.75 0 002.576 2.576l2.846.813a.75.75 0 010 1.442l-2.846.813a3.75 3.75 0 00-2.576 2.576l-.813 2.846a.75.75 0 01-1.442 0l-.813-2.846a3.75 3.75 0 00-2.576-2.576l-2.846-.813a.75.75 0 010-1.442l2.846-.813A3.75 3.75 0 007.466 7.89l.813-2.846A.75.75 0 019 4.5zM18 1.5a.75.75 0 01.728.568l.258 1.036c.236.94.97 1.674 1.91 1.91l1.036.258a.75.75 0 010 1.456l-1.036.258c-.94.236-1.674.97-1.91 1.91l-.258 1.036a.75.75 0 01-1.456 0l-.258-1.036a2.625 2.625 0 00-1.91-1.91l-1.036-.258a.75.75 0 010-1.456l1.036-.258a2.625 2.625 0 001.91-1.91l.258-1.036A.75.75 0 0118 1.5zM16.5 15a.75.75 0 01.712.513l.394 1.183c.15.447.5.799.948.948l1.183.395a.75.75 0 010 1.422l-1.183.395c-.447.15-.799.5-.948.948l-.395 1.183a.75.75 0 01-1.422 0l-.395-1.183a1.5 1.5 0 00-.948-.948l-1.183-.395a.75.75 0 010-1.422l1.183-.395c.447-.15.799.5.948.948l.395-1.183A.75.75 0 0116.5 15z" clip-rule="evenodd" />
                        </svg>
                        
                        <h2 class="my-8 text-2xl font-bold text-gray-700 dark:text-white md:text-4xl">
                        Teknologi Tanda Tangan Digital untuk Era Modern
                        </h2>
                        <p class="text-gray-600 dark:text-gray-300">Tanda tangan digital adalah solusi modern untuk otentikasi dokumen. Dengan SIMPEG, setiap dokumen kepegawaian dapat ditandatangani secara elektronik, mengurangi risiko pemalsuan, mempercepat proses birokrasi, dan ramah lingkungan tanpa kertas.</p>
                    </div>
                    <div
                        class="mt-16 grid divide-x divide-y divide-gray-100 dark:divide-gray-700 overflow-hidden rounded-3xl border border-gray-100 text-gray-600 dark:border-gray-700 sm:grid-cols-2 lg:grid-cols-4 lg:divide-y-0 xl:grid-cols-4"
                    >
                        <div class="group relative bg-white dark:bg-gray-800 transition hover:z-[1] hover:shadow-2xl hover:shadow-gray-600/10">
                        <div class="relative space-y-8 py-12 p-8">
                            <img
                            src="https://cdn-icons-png.flaticon.com/512/4341/4341139.png"
                            class="w-12"
                            width="512"
                            height="512"
                            alt="burger illustration"
                            />

                            <div class="space-y-2">
                            <h5
                                class="text-xl font-semibold text-gray-700 dark:text-white transition group-hover:text-secondary"
                            >
                                Aman & Terenkripsi
                            </h5>
                            <p class="text-gray-600 dark:text-gray-300">
                                Setiap tanda tangan digital dilindungi enkripsi dan dapat diverifikasi keasliannya.
                            </p>
                            </div>
                            <a href="#" class="flex items-center justify-between group-hover:text-secondary">
                            <span class="text-sm">Read more</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 -translate-x-4 text-2xl opacity-0 transition duration-300 group-hover:translate-x-0 group-hover:opacity-100">
                                <path fill-rule="evenodd" d="M12.97 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06l6.22-6.22H3a.75.75 0 010-1.5h16.19l-6.22-6.22a.75.75 0 010-1.06z" clip-rule="evenodd" />
                            </svg>                
                            </a>
                        </div>
                        </div>
                        <div class="group relative bg-white dark:bg-gray-800 transition hover:z-[1] hover:shadow-2xl hover:shadow-gray-600/10">
                        <div class="relative space-y-8 py-12 p-8">
                            <img
                            src="https://cdn-icons-png.flaticon.com/512/4341/4341134.png"
                            class="w-12"
                            width="512"
                            height="512"
                            alt="burger illustration"
                            />

                            <div class="space-y-2">
                            <h5
                                class="text-xl font-semibold text-gray-700 dark:text-white transition group-hover:text-secondary"
                            >
                                Efisien Tanpa Kertas
                            </h5>
                            <p class="text-gray-600 dark:text-gray-300">
                                Proses dokumen tanpa cetak, lebih cepat dan ramah lingkungan.
                            </p>
                            </div>
                            <a href="#" class="flex items-center justify-between group-hover:text-secondary">
                            <span class="text-sm">Read more</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 -translate-x-4 text-2xl opacity-0 transition duration-300 group-hover:translate-x-0 group-hover:opacity-100">
                                <path fill-rule="evenodd" d="M12.97 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06l6.22-6.22H3a.75.75 0 010-1.5h16.19l-6.22-6.22a.75.75 0 010-1.06z" clip-rule="evenodd" />
                            </svg>                
                            </a>
                        </div>
                        </div>
                        <div class="group relative bg-white dark:bg-gray-800 transition hover:z-[1] hover:shadow-2xl hover:shadow-gray-600/10">
                        <div class="relative space-y-8 py-12 p-8">
                            <img
                            src="https://cdn-icons-png.flaticon.com/512/4341/4341160.png"
                            class="w-12"
                            width="512"
                            height="512"
                            alt="burger illustration"
                            />

                            <div class="space-y-2">
                            <h5
                                class="text-xl font-semibold text-gray-700 dark:text-white transition group-hover:text-secondary"
                            >
                                Mudah Diverifikasi
                            </h5>
                            <p class="text-gray-600 dark:text-gray-300">
                                QR code pada dokumen memudahkan pengecekan keaslian secara instan.
                            </p>
                            </div>
                            <a href="#" class="flex items-center justify-between group-hover:text-secondary">
                            <span class="text-sm">Read more</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 -translate-x-4 text-2xl opacity-0 transition duration-300 group-hover:translate-x-0 group-hover:opacity-100">
                                <path fill-rule="evenodd" d="M12.97 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06l6.22-6.22H3a.75.75 0 010-1.5h16.19l-6.22-6.22a.75.75 0 010-1.06z" clip-rule="evenodd" />
                            </svg>                
                            </a>
                        </div>
                        </div>
                        <div
                        class="group relative bg-gray-50 dark:bg-gray-900 transition hover:z-[1] hover:shadow-2xl hover:shadow-gray-600/10"
                        >
                        <div
                            class="relative space-y-8 py-12 p-8 transition duration-300 group-hover:bg-white dark:group-hover:bg-gray-800"
                        >
                            <img
                            src="https://cdn-icons-png.flaticon.com/512/4341/4341025.png"
                            class="w-12"
                            width="512"
                            height="512"
                            alt="burger illustration"
                            />

                            <div class="space-y-2">
                            <h5
                                class="text-xl font-semibold text-gray-700 dark:text-white transition group-hover:text-secondary"
                            >
                                Dukungan Instansi
                            </h5>
                            <p class="text-gray-600 dark:text-gray-300">
                                Cocok untuk kebutuhan ASN, dosen, dan pegawai di berbagai instansi.
                            </p>
                            </div>
                            <a href="#" class="flex items-center justify-between group-hover:text-secondary">
                            <span class="text-sm">Read more</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 -translate-x-4 text-2xl opacity-0 transition duration-300 group-hover:translate-x-0 group-hover:opacity-100">
                                <path fill-rule="evenodd" d="M12.97 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06l6.22-6.22H3a.75.75 0 010-1.5h16.19l-6.22-6.22a.75.75 0 010-1.06z" clip-rule="evenodd" />
                            </svg>                
                            </a>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="solution">
                <div class="max-w-7xl mx-auto px-6 md:px-12 xl:px-6">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-sky-500">
                    <path fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z" clip-rule="evenodd" />
                </svg>
                
                <div class="space-y-6 justify-between text-gray-600 md:flex flex-row-reverse md:gap-6 md:space-y-0 lg:gap-12 lg:items-center">
                    <div class="md:w-5/12 lg:w-1/2">
                    <img
                        src="{{ asset('astro/images/pie.svg') }}"
                        alt="image"
                        loading="lazy"
                        width=""
                        height=""
                        class="w-full"
                    />
                    </div>
                    <div class="md:w-7/12 lg:w-1/2">
                    <h2 class="text-3xl font-bold text-gray-900 md:text-4xl dark:text-white">
                        Tanda Tangan Digital: Aman, Sah, dan Mudah
                    </h2>
                    <p class="my-8 text-gray-600 dark:text-gray-300">
                        Dengan tanda tangan digital, dokumen kepegawaian Anda tidak hanya lebih cepat diproses, tapi juga terjamin keasliannya. Setiap dokumen yang ditandatangani secara digital dapat diverifikasi melalui QR code, sehingga mencegah pemalsuan dan meningkatkan kepercayaan.
                    </p>
                    <div class="divide-y space-y-4 divide-gray-100 dark:divide-gray-800">
                        <div class="mt-8 flex gap-4 md:items-center">
                        <div class="w-12 h-12 flex gap-4 rounded-full bg-indigo-100 dark:bg-indigo-900/20">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 m-auto text-indigo-500 dark:text-indigo-400">
                            <path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0112 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 01-3.476.383.39.39 0 00-.297.17l-2.755 4.133a.75.75 0 01-1.248 0l-2.755-4.133a.39.39 0 00-.297-.17 48.9 48.9 0 01-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.68 3.348-3.97zM6.75 8.25a.75.75 0 01.75-.75h9a.75.75 0 010 1.5h-9a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5H12a.75.75 0 000-1.5H7.5z" clip-rule="evenodd" />
                            </svg>        
                        </div>
                        <div class="w-5/6">
                            <h3 class="font-semibold text-lg text-gray-700 dark:text-indigo-300">Layanan Bantuan 24/7</h3>
                            <p class="text-gray-500 dark:text-gray-400">Tim kami siap membantu Anda kapan saja terkait tanda tangan digital.</p>
                        </div> 
                        </div> 
                        <div class="pt-4 flex gap-4 md:items-center">
                        <div class="w-12 h-12 flex gap-4 rounded-full bg-teal-100 dark:bg-teal-900/20">  
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 m-auto text-teal-600 dark:text-teal-400">
                            <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>                                      
                        </div>
                        <div class="w-5/6">
                            <h3 class="font-semibold text-lg text-gray-700 dark:text-teal-300">Verifikasi Real Time</h3>
                            <p class="text-gray-500 dark:text-gray-400">Cek keaslian dokumen secara langsung melalui QR code.</p>
                        </div> 
                        </div> 
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <div class="text-gray-600 dark:text-gray-300" id="reviews">
                <div class="max-w-7xl mx-auto px-6 md:px-12 xl:px-6">
                    <div class="mb-20 space-y-4 px-6 md:px-0">
                    <h2 class="text-center text-2xl font-bold text-gray-800 dark:text-white md:text-4xl">
                        Testimoni Pengguna SIMPEG
                    </h2>
                    </div>
                    <div class="md:columns-2 lg:columns-3 gap-8 space-y-8">
                    <div class="aspect-auto p-8 border border-gray-100 rounded-3xl bg-white dark:bg-gray-800 dark:border-gray-700 shadow-2xl shadow-gray-600/10 dark:shadow-none">
                        <div class="flex gap-4">
                        <img class="w-12 h-12 rounded-full" src="{{ asset('astro/images/avatars/avatar.webp') }}" alt="user avatar" width="400" height="400" loading="lazy">
                        <div>
                            <h6 class="text-lg font-medium text-gray-700 dark:text-white">Daniella Doe</h6>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Mobile dev</p>
                        </div>
                        </div>
                        <p class="mt-8">Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum aliquid quo eum quae quos illo earum ipsa doloribus nostrum minus libero aspernatur laborum cum, a suscipit, ratione ea totam ullam! Lorem ipsum dolor sit amet consectetur, adipisicing elit. Architecto laboriosam deleniti aperiam ab veniam sint non cumque quis tempore cupiditate. Sint libero voluptas veniam at reprehenderit, veritatis harum et rerum.</p>
                    </div>
                    <div class="aspect-auto p-8 border border-gray-100 rounded-3xl bg-white dark:bg-gray-800 dark:border-gray-700 shadow-2xl shadow-gray-600/10 dark:shadow-none">
                        <div class="flex gap-4">
                        <img class="w-12 h-12 rounded-full" src="{{ asset('astro/images/avatars/avatar-1.webp') }}" alt="user avatar" width="200" height="200" loading="lazy">
                        <div>
                            <h6 class="text-lg font-medium text-gray-700 dark:text-white">Jane doe</h6>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Marketing</p>
                        </div>
                        </div>
                        <p class="mt-8"> Lorem ipsum dolor laboriosam deleniti aperiam ab veniam sint non cumque quis tempore cupiditate. Sint libero voluptas veniam at reprehenderit, veritatis harum et rerum.</p>
                    </div>
                    <div class="aspect-auto p-8 border border-gray-100 rounded-3xl bg-white dark:bg-gray-800 dark:border-gray-700 shadow-2xl shadow-gray-600/10 dark:shadow-none">
                        <div class="flex gap-4">
                        <img class="w-12 h-12 rounded-full" src="{{ asset('astro/images/avatars/avatar-2.webp') }}" alt="user avatar" width="200" height="200" loading="lazy">
                        <div>
                            <h6 class="text-lg font-medium text-gray-700 dark:text-white">Yanick Doe</h6>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Developer</p>
                        </div>
                        </div>
                        <p class="mt-8">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Architecto laboriosam deleniti aperiam ab veniam sint non cumque quis tempore cupiditate. Sint libero voluptas veniam at reprehenderit, veritatis harum et rerum.</p>
                    </div>
                    <div class="aspect-auto p-8 border border-gray-100 rounded-3xl bg-white dark:bg-gray-800 dark:border-gray-700 shadow-2xl shadow-gray-600/10 dark:shadow-none">
                        <div class="flex gap-4">
                        <img class="w-12 h-12 rounded-full" src="{{ asset('astro/images/avatars/avatar-3.webp') }}" alt="user avatar" width="200" height="200" loading="lazy">
                        <div>
                            <h6 class="text-lg font-medium text-gray-700 dark:text-white">Jane Doe</h6>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Mobile dev</p>
                        </div>
                        </div>
                        <p class="mt-8">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Architecto laboriosam deleniti aperiam ab veniam sint non cumque quis tempore cupiditate. Sint libero voluptas veniam at reprehenderit, veritatis harum et rerum.</p>
                    </div>
                    <div class="aspect-auto p-8 border border-gray-100 rounded-3xl bg-white dark:bg-gray-800 dark:border-gray-700 shadow-2xl shadow-gray-600/10 dark:shadow-none">
                        <div class="flex gap-4">
                        <img class="w-12 h-12 rounded-full" src="{{ asset('astro/images/avatars/avatar-4.webp') }}" alt="user avatar" width="200" height="200" loading="lazy">
                        <div>
                            <h6 class="text-lg font-medium text-gray-700 dark:text-white">Andy Doe</h6>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Manager</p>
                        </div>
                        </div>
                        <p class="mt-8"> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Architecto laboriosam deleniti aperiam ab veniam sint non cumque quis tempore cupiditate. Sint libero voluptas veniam at reprehenderit, veritatis harum et rerum.</p>
                    </div>
                    <div class="aspect-auto p-8 border border-gray-100 rounded-3xl bg-white dark:bg-gray-800 dark:border-gray-700 shadow-2xl shadow-gray-600/10 dark:shadow-none">
                        <div class="flex gap-4">
                        <img class="w-12 h-12 rounded-full" src="{{ asset('astro/images/avatars/avatar-2.webp') }}" alt="user avatar" width="400" height="400" loading="lazy">
                        <div>
                            <h6 class="text-lg font-medium text-gray-700 dark:text-white">Yanndy Doe</h6>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Mobile dev</p>
                        </div>
                        </div>
                        <p class="mt-8">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Architecto laboriosam deleniti aperiam ab veniam sint non cumque quis tempore cupiditate. Sint libero voluptas veniam at reprehenderit, veritatis harum et rerum.</p>
                    </div>
                    </div>
                </div>
            </div>

            <div class="relative py-16">
                <div aria-hidden="true" class="absolute inset-0 h-max w-full m-auto grid grid-cols-2 -space-x-52 opacity-40 dark:opacity-20">
                  <div class="blur-[106px] h-56 bg-gradient-to-br from-primary to-purple-400 dark:from-blue-700"></div>
                  <div class="blur-[106px] h-32 bg-gradient-to-r from-cyan-400 to-sky-300 dark:to-indigo-600"></div>
                </div>
                <div class="max-w-7xl mx-auto px-6 md:px-12 xl:px-6">
                  <div class="relative">
                    <div class="flex items-center justify-center -space-x-2">
                      <img
                        loading="lazy"
                        width="400"
                        height="400"
                        src="{{ asset('astro/images/avatars/avatar.webp') }}"
                        alt="member photo"
                        class="h-8 w-8 rounded-full object-cover"
                      />
                      <img
                        loading="lazy"
                        width="200"
                        height="200"
                        src="{{ asset('astro/images/avatars/avatar-2.webp') }}"
                        alt="member photo"
                        class="h-12 w-12 rounded-full object-cover"
                      />
                      <img
                        loading="lazy"
                        width="200"
                        height="200"
                        src="{{ asset('astro/images/avatars/avatar-3.webp') }}"
                        alt="member photo"
                        class="z-10 h-16 w-16 rounded-full object-cover"
                      />
                      <img
                        loading="lazy"
                        width="200"
                        height="200"
                        src="{{ asset('astro/images/avatars/avatar-4.webp') }}"
                        alt="member photo"
                        class="relative h-12 w-12 rounded-full object-cover"
                      />
                      <img
                        loading="lazy"
                        width="200"
                        height="200"
                        src="{{ asset('astro/images/avatars/avatar-1.webp') }}"
                        alt="member photo"
                        class="h-8 w-8 rounded-full object-cover"
                      />
                    </div>
                    <div class="mt-6 m-auto space-y-6 md:w-8/12 lg:w-7/12">
                      <h1 class="text-center text-4xl font-bold text-gray-800 dark:text-white md:text-5xl">Mulai Digitalisasi Tanda Tangan Anda</h1>
                      <p class="text-center text-xl text-gray-600 dark:text-gray-300">
                        Bergabunglah bersama ribuan pengguna yang telah merasakan kemudahan, keamanan, dan keabsahan tanda tangan digital di SIMPEG.
                      </p>
                      <div class="flex flex-wrap justify-center gap-6">
                        <a
                            href="#"
                            class="relative flex h-12 w-full items-center justify-center px-8 before:absolute before:inset-0 before:rounded-full before:bg-primary before:transition before:duration-300 hover:before:scale-105 active:duration-75 active:before:scale-95 sm:w-max"
                          >
                            <span class="relative text-base font-semibold text-white dark:text-dark"
                              >Daftar Sekarang</span
                            >
                          </a>
                          <a
                            href="#"
                            class="relative flex h-12 w-full items-center justify-center px-8 before:absolute before:inset-0 before:rounded-full before:border before:border-transparent before:bg-primary/10 before:bg-gradient-to-b before:transition before:duration-300 hover:before:scale-105 active:duration-75 active:before:scale-95 dark:before:border-gray-700 dark:before:bg-gray-800 sm:w-max"
                          >
                            <span
                              class="relative text-base font-semibold text-primary dark:text-white"
                              >Pelajari Lebih Lanjut</span
                            >
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
            </div>

            <div id="blog">
                <div class="max-w-7xl mx-auto px-6 md:px-12 xl:px-6">
                    <div class="mb-12 space-y-2 text-center">
                        <h2 class="text-3xl font-bold text-gray-800 md:text-4xl dark:text-white">Artikel Terbaru</h2>
                        <p class="lg:mx-auto lg:w-6/12 text-gray-600 dark:text-gray-300">
                        Temukan informasi, tips, dan update terbaru seputar tanda tangan digital dan kepegawaian di SIMPEG.
                        </p>
                    </div>
                    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <div class="group p-6 sm:p-8 rounded-3xl bg-white border border-gray-100 dark:shadow-none dark:border-gray-700 dark:bg-gray-800 bg-opacity-50 shadow-2xl shadow-gray-600/10">
                        <div class="relative overflow-hidden rounded-xl">
                            <img src="https://images.unsplash.com/photo-1661749711934-492cd19a25c3?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1674&q=80"
                            alt="art cover" loading="lazy" width="1000" height="667" class="h-64 w-full object-cover object-top transition duration-500 group-hover:scale-105"/>
                        </div>
                        <div class="mt-6 relative">
                            <h3 class="text-2xl font-semibold text-gray-800 dark:text-white">
                            Pentingnya Tanda Tangan Digital di Era Modern
                            </h3>
                            <p class="mt-6 mb-8 text-gray-600 dark:text-gray-300">
                            Tanda tangan digital bukan hanya soal kepraktisan, tapi juga keamanan dan keabsahan dokumen Anda.
                            </p>
                            <a class="inline-block" href="#">
                            <span class="text-info dark:text-blue-300">Read more</span>
                            </a>
                        </div>
                        
                        </div>
                        <div class="group p-6 sm:p-8 rounded-3xl bg-white border border-gray-100 dark:shadow-none dark:border-gray-700 dark:bg-gray-800 bg-opacity-50 shadow-2xl shadow-gray-600/10">
                        <div class="relative overflow-hidden rounded-xl">
                            <img src="https://images.unsplash.com/photo-1491895200222-0fc4a4c35e18?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1674&q=80"
                            alt="art cover" loading="lazy" width="1000" height="667" class="h-64 w-full object-cover object-top transition duration-500 group-hover:scale-105"/>
                        </div>
                        <div class="mt-6 relative">
                            <h3 class="text-2xl font-semibold text-gray-800 dark:text-white">
                            Tips Mengamankan Dokumen Digital
                            </h3>
                            <p class="mt-6 mb-8 text-gray-600 dark:text-gray-300">
                            Pastikan selalu memverifikasi QR code pada dokumen digital Anda untuk menghindari pemalsuan.
                            </p>
                            <a class="inline-block" href="#">
                            <span class="text-info dark:text-blue-300">Read more</span>
                            </a>
                        </div>
                        
                        </div>
                        <div class="group p-6 sm:p-8 rounded-3xl bg-white border border-gray-100 dark:shadow-none dark:border-gray-700 dark:bg-gray-800 bg-opacity-50 shadow-2xl shadow-gray-600/10">
                        <div class="relative overflow-hidden rounded-xl">
                            <img src="https://images.unsplash.com/photo-1620121692029-d088224ddc74?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2832&q=80"
                            alt="art cover" loading="lazy" width="1000" height="667" class="h-64 w-full object-cover object-top transition duration-500 group-hover:scale-105"/>
                        </div>
                        <div class="mt-6 relative">
                            <h3 class="text-2xl font-semibold text-gray-800 dark:text-white">
                            Proses Tanda Tangan Digital di SIMPEG
                            </h3>
                            <p class="mt-6 mb-8 text-gray-600 dark:text-gray-300">
                            Pelajari langkah-langkah mudah melakukan tanda tangan digital di SIMPEG dan manfaatnya untuk instansi Anda.
                            </p>
                            <a class="inline-block" href="#">
                            <span class="text-info dark:text-blue-300">Read more</span>
                            </a>
                        </div>
                        
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <footer class="border-t border-gray-100 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-6 md:px-12 xl:px-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 py-16">
                    <div class="col-span-2 md:col-span-1">
                        <a href="#home" aria-label="logo" class="flex space-x-2 items-center">
                            <span class="text-2xl font-bold text-gray-900 dark:text-white">SIMPEG</span>
                        </a>
                        <p class="text-gray-600 dark:text-gray-400 mt-4">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        </p>
                    </div>
                    <div>
                        <h6 class="text-lg font-medium text-gray-800 dark:text-white">Perusahaan</h6>
                        <ul class="mt-4 space-y-2">
                            <li>
                                <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Tentang Kami</a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Kontak</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h6 class="text-lg font-medium text-gray-800 dark:text-white">Legal</h6>
                        <ul class="mt-4 space-y-2">
                            <li>
                                <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Syarat & Ketentuan</a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary">Kebijakan Privasi</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="py-6 text-sm text-center text-gray-600 dark:text-gray-400">
                    &copy; 2024 SIMPEG. Hak cipta dilindungi undang-undang.
                </div>
            </div>
        </footer>
    </body>
</html> 