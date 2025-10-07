/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        mds: {
          // Primary Blue
          blue: {
            100: '#B3E0FF',
            500: '#007BFF',  // original
            700: '#003D80',
          },
          purple: { 
            100: '#E0B3FF',
            500: '#A020F0', // Contoh warna ungu yang jelas
            700: '#6A1B9A',
          },
          // Accent Orange
          orange: {
            100: '#FFC0B3',
            500: '#FF5733',  // original
            700: '#CC3B1A',
          },
          // Accent Green
          green: {
            100: '#B3FFC5',
            500: '#33FF57',  // original
            700: '#22A347',
          },
          // Black/Gray
          black: '#111827',
          gray: {
            100: '#F3F4F6',
          }
        },
      },
      boxShadow: {
        neubrutal: '8px 8px 0 0 rgba(0, 0, 0, 0.7)',
      },
      borderWidth: {
        '6': '6px',
      },
      opacity: {
        '80': '0.8',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}