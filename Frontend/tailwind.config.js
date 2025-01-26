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
        "bitchest-success": "#08D41D",
      },
      fontFamily: {
        bitchest: ["Celias", "sans-serif"],
      },
      screens: {
        xs: "480px",
        sm: "640px",
        md: "768px",
        lg: "1024px",
        xl: "1280px",
        "2xl": "1536px",
      },
    },
  },
  plugins: [],
};
