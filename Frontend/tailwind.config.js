/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'], 
  theme: {
    extend: {
      colors: {
        lightGray: '#F8F8F8',
        neonGreen: '#01FF19',
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
};
