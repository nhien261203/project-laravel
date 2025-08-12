/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      zIndex: {
        '60': '60',
        '70': '70',
      },
      

      animation: {
        spin: 'spin 1s linear infinite',
      },
      colors: {
        primary: '#f42c37',
        secondary: '#f42c37',
        brandYellow: '#fdc62e',
        brandGreen: '#2dcc6f',
        brandBlue: '#1376f4',
        brandWhite: '#eeeeee',

      },

      container: {
        center: true,
        padding: {
          DEFAULT: '1rem',
          sm: '2rem',
          lg: '4rem',
          xl: '6rem',
          '2xl': '8rem',
        },
      },

      fontFamily: {
        poppins: ['Poppins', 'sans-serif'],
        arial: ['Arial', 'sans-serif'],

        sans: ['Inter', 'sans-serif'],

      },
    },
  },
  plugins: [
    
  ],
}
