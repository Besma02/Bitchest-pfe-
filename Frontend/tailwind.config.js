/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'], 
  theme: {
    extend: {
      colors: {
        "bitchest-primary": "#38618C",
        "bitchest-secondary": "#35A7FF",
        "bitchest-white": "#FFFFFF",
        "bitchest-black": "#434445",
        "bitchest-alert": "#FF5964",
        "bitchest-success": "#01FF19",
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
