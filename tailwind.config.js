/** @type {import('tailwindcss').Config} */
module.exports = {
    // 1. AKTIVASI DARK MODE: Memastikan Tailwind bisa beralih mode
    darkMode: 'class', // Atur agar dark mode diaktifkan melalui class 'dark' di <html>

    // 2. FILE SCAN: Lokasi file yang akan dipindai Tailwind
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // 3. PALET WARNA KUSTOM (Berdasarkan Logo MDS)
            colors: {
                // Warna Primer (Biru MDS)
                'mds-primary': {
                    DEFAULT: '#007BFF', // Biru Cerah
                    dark: '#0056B3', // Biru gelap untuk Dark Mode
                },
                // Warna Aksen 1 (Oranye/Pink MDS - untuk Update/Warning)
                'mds-accent-orange': {
                    DEFAULT: '#FF5733', // Oranye Terang
                    dark: '#CC452A',
                },
                // Warna Aksen 2 (Hijau MDS - untuk Success/Button Sekunder)
                'mds-accent-green': {
                    DEFAULT: '#33FF57', // Hijau Cerah
                    dark: '#2AA347',
                },
                // Warna Neubrutalism (Teks dan Shadow)
                'mds-black': '#111827', // Hitam/Abu-abu gelap tebal
            },

            // 4. BAYANGAN NEUBRUTALISM (Chunky Shadow)
            boxShadow: {
                // Bayangan tebal dan keras khas Neubrutalism
                'chunky': '4px 4px 0px 0px rgba(17, 24, 39, 1)', // mds-black
                'chunky-sm': '2px 2px 0px 0px rgba(17, 24, 39, 1)',
                'chunky-lg': '8px 8px 0px 0px rgba(17, 24, 39, 1)',
            },

            // 5. BATAS/BORDER NEUBRUTALISM
            borderWidth: {
                // Border tebal untuk elemen Neubrutalism
                '3': '3px',
                '4': '4px',
            }
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
    ],
};