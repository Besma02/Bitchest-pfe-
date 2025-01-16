/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],

  theme: {
    extend: {
      colors: {
        "bitchest-primary": "#38618C",
        "bitchest-secondary": "#35A7FF",
        "bitchest-white": "#FFFFFF",
        "bitchest-black": "#434445",
        "bitchest-alert": "#FF5964",
        "bitchest-success": "#01FF19",
      },
      fontFamily: {
        bitchest: ["Celias", "sans-serif"],
      },
    },
  },
  plugins: [],
};
