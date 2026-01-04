<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistem Rapor Sekolah - MIS AL-KHAIRIYAH PABEAN</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus+jakarta+sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Plus Jakarta Sans', 'sans-serif'],
                        },
                        colors: {
                            primary: {
                                50: '#eff6ff',
                                100: '#dbeafe',
                                200: '#bfdbfe',
                                300: '#93c5fd',
                                400: '#60a5fa',
                                500: '#3b82f6',
                                600: '#2563eb',
                                700: '#1d4ed8',
                                800: '#1e40af',
                                900: '#1e3a8a',
                            }
                        }
                    }
                }
            }
        </script>

        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }

            @keyframes pulse-slow {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }

            @keyframes gradient-x {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            .animate-float {
                animation: float 6s ease-in-out infinite;
            }

            .animate-float-delayed {
                animation: float 6s ease-in-out infinite;
                animation-delay: 2s;
            }

            .animate-pulse-slow {
                animation: pulse-slow 3s ease-in-out infinite;
            }

            .bg-gradient-animated {
                background-size: 200% 200%;
                animation: gradient-x 8s ease infinite;
            }

            .glass-card {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .dark .glass-card {
                background: rgba(30, 41, 59, 0.5);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .text-gradient {
                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .dark .text-gradient {
                background: linear-gradient(135deg, #60a5fa 0%, #93c5fd 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
        </style>
    </head>
    <body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

        <div class="min-h-screen overflow-hidden">
            <div class="fixed inset-0 -z-10">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary-400/30 rounded-full blur-3xl animate-float"></div>
                <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-400/30 rounded-full blur-3xl animate-float-delayed"></div>
                <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-indigo-400/20 rounded-full blur-3xl animate-pulse-slow"></div>
            </div>

            <nav class="fixed top-0 left-0 right-0 z-50 glass-card">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <span class="font-bold text-xl text-gray-900 dark:text-white">Sistem Rapor</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <button onclick="document.documentElement.classList.toggle('dark')" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-300 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-300 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </button>
                            @auth
                                <a href="{{ url('/admin') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary-600 to-indigo-600 hover:from-primary-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ url('/admin') }}" class="hidden sm:inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-primary-600 to-indigo-600 hover:from-primary-700 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    Login
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <main class="pt-24 pb-16">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="text-center">
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 rounded-full text-sm font-medium mb-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Modern & Efisien
                        </div>

                        <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
                            Sistem Rapor
                            <span class="text-gradient">Digital</span>
                        </h1>

                        <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-10">
                            Kelola nilai, buat rapor, dan monitoring akademik siswa dengan mudah dan cepat. Satu platform untuk semua kebutuhan sekolah.
                        </p>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            @guest
                                <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-primary-600 to-indigo-600 hover:from-primary-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-2xl hover:shadow-primary-500/25 transition-all duration-200 transform hover:scale-105">
                                    <span>Mulai Sekarang</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            @endguest

                            <a href="#fitur" class="inline-flex items-center gap-2 px-8 py-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 font-semibold rounded-xl shadow-lg hover:shadow-xl border border-gray-200 dark:border-gray-700 transition-all duration-200">
                                <span>Pelajari Lebih Lanjut</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="mt-20 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="fitur">
                        <div class="glass-card rounded-2xl p-8 hover:transform hover:scale-105 transition-all duration-300">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-blue-500/25">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Kelola Data Siswa</h3>
                            <p class="text-gray-600 dark:text-gray-300">Tingkatkan efisiensi pengelolaan data siswa melalui sistem terpadu yang mempermudah pencarian dan pembaruan informasi.</p>
                        </div>

                        <div class="glass-card rounded-2xl p-8 hover:transform hover:scale-105 transition-all duration-300">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-purple-500/25">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Input Nilai</h3>
                            <p class="text-gray-600 dark:text-gray-300">Input nilai harian, UTS, dan UAS dengan mudah. Sistem bulk input mempercepat proses penilaian secara massal.</p>
                        </div>

                        <div class="glass-card rounded-2xl p-8 hover:transform hover:scale-105 transition-all duration-300">
                            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-green-500/25">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Generate Rapor</h3>
                            <p class="text-gray-600 dark:text-gray-300">Buat rapor siswa otomatis dalam format Excel dan Word dengan desain profesional dan data yang akurat.</p>
                        </div>

                        <div class="glass-card rounded-2xl p-8 hover:transform hover:scale-105 transition-all duration-300">
                            <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-orange-500/25">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Manajemen Kelas</h3>
                            <p class="text-gray-600 dark:text-gray-300">Atur kelas, tahun ajaran, dan wali kelas dengan sistem terpadu. Setiap guru hanya melihat kelas yang diampu.</p>
                        </div>

                        <div class="glass-card rounded-2xl p-8 hover:transform hover:scale-105 transition-all duration-300">
                            <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-red-500/25">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Dashboard Interaktif</h3>
                            <p class="text-gray-600 dark:text-gray-300">Visualisasi data dengan grafik dan statistik real-time. Pantau performa siswa dan sekolah dengan mudah.</p>
                        </div>

                        <div class="glass-card rounded-2xl p-8 hover:transform hover:scale-105 transition-all duration-300">
                            <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-cyan-500/25">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Role-Based Access</h3>
                            <p class="text-gray-600 dark:text-gray-300">Akses berdasarkan peran user (Admin & Guru). Data siswa dan kelas hanya dapat diakses oleh user yang berwenang.</p>
                        </div>
                    </div>

                    <div class="mt-20 text-center">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-8">
                            Dipercaya oleh <span class="text-gradient">MIS AL-KHAIRIYAH PABEAN</span>
                        </h2>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                            <div class="glass-card rounded-xl p-6 text-center">
                                <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">100+</div>
                                <div class="text-gray-600 dark:text-gray-300 text-sm">Siswa</div>
                            </div>
                            <div class="glass-card rounded-xl p-6 text-center">
                                <div class="text-4xl font-bold text-purple-600 dark:text-purple-400 mb-2">10+</div>
                                <div class="text-gray-600 dark:text-gray-300 text-sm">Guru</div>
                            </div>
                            <div class="glass-card rounded-xl p-6 text-center">
                                <div class="text-4xl font-bold text-green-600 dark:text-green-400 mb-2">6+</div>
                                <div class="text-gray-600 dark:text-gray-300 text-sm">Kelas</div>
                            </div>
                            <div class="glass-card rounded-xl p-6 text-center">
                                <div class="text-4xl font-bold text-orange-600 dark:text-orange-400 mb-2">99%</div>
                                <div class="text-gray-600 dark:text-gray-300 text-sm">Kepuasan</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-20 glass-card rounded-2xl p-8 md:p-12 bg-gradient-animated bg-gradient-to-r from-primary-600 via-indigo-600 to-purple-600">
                        <div class="text-center">
                            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                                Siap Memulai?
                            </h2>
                            <p class="text-xl text-white/80 mb-8 max-w-2xl mx-auto">
                                Bergabunglah dengan sistem rapor digital modern dan efisien. Kelola akademik sekolah dengan cara yang lebih baik.
                            </p>
                            @guest
                                <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-primary-600 font-bold rounded-xl shadow-2xl hover:shadow-xl hover:scale-105 transition-all duration-200">
                                    <span>Masuk Sekarang</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </main>

            <footer class="border-t border-gray-200 dark:border-gray-800 mt-16">
                <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
                    <div class="text-center text-gray-600 dark:text-gray-400">
                        <p>&copy; 2025 MIS AL-KHAIRIYAH PABEAN. Hak Cipta Dilindungi.</p>
                    </div>
                </div>
            </footer>
        </div>

    </body>
</html>
