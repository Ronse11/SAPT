/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      // fontSize: (() => {
      //   const sizes = {};
      //   for (let i = 1; i <= 50; i++) {
      //     sizes[`${i}px`] = `${i}px`; // Creates '1px': '1px', '2px': '2px', ..., '50px': '50px'
      //   }
      //   return sizes;
      // })(),
      fontSize: {
        '14px': '14px',
        '15px': '15px',
        '16px': '16px',
        '17px': '17px',
        '18px': '18px',
        '19px': '19px',
        '20px': '20px',
        '21px': '21px',
        '22px': '22px',
        '23px': '23px',
        '24px': '24px',
        '25px': '25px',
      },
      fontFamily: {
        title: ["Poppins", "sans-serif"],
        copperplate: ["Cinzel", "serif"],
        playfair: ["Playfair Display", "serif"],
        arial: ["Roboto Condensed", "sans-serif"],
        calibri: ["Roboto", "sans-serif"],
        noto: ["Noto Sans", "sans-serif"]
      },
      colors: {
        bgcolor: "#fefeff",
        primary: "#e4e7eb",
        // sgcolor: "#f2f4f6",
        sgcolor: "#f5f5f5",
        // sgcolor: "#f7f7f7",
        sgcolorSub: "#ececec",
        sgcolorBG: "#f8f8f8",
        // sgline: "#d9d9d9",
        sgline: "#e2e5e7",
        hoverColor: "#f0f2f3",
        mainText: "#333333",
        mainBg: "#3454d1",
        // mainText: "#6f8088",
        mainTexts: "#333333",
        subtText: "#CCCCCC",
        // subtText: "#b7bfc3",
        highlight: "#000",
        cursor: "#ccc",
        // cursor: "#d3d3d3",
        navHover: "#e2e5e7",
        placeHolderText: "#808080",
        roomColor: "#afc9dc",
        bg_red: "#FF0000",
        bg_blue: "#0000FF",
        bg_yellow: "#FFFF00",
        bg_green: "#00B050",
        bg_orange: "#FFA500",
        bg_purple: "#800080"
      },
      gridTemplateColumns: {
        "16": "repeat(16, minmax(0, 1fr))",
        "adjust": "repeat(1, minmax(0, 1fr))",
      },
      gridTemplateRows: {
        "row": "repeat(autofill, minmax(200px, 200px))",
        "15": "repeat(15, minmax(0, 1fr))",
      },
      gridColumn: {
        "span-16": "span 16 / span 16",
      },
      gridRow: {
        "span-13": "span 13 / span 13",
      },
      maxHeight: {
        "maxH": "100%",
      },
      screens: {
        "3xl": "1700px",
        "cp": "706px",
        "tablet": "1281px",
        "fade-text": "1197px",
      },
      backgroundColor: {
        bColor: "rgba(183, 191, 195, .7)",
      },
      animation: {
        jump: "jump 1.8s infinite",
        jump1: "jump1 1.8s infinite",
        jump2: "jump2 1.8s infinite",
        jump3: "jump3 1.8s infinite",
        side: "side 5s infinite"
      },
      keyframes: {
        jump: {
          "0%, 100%": { transform: "translateY(10px)" },
          "35%": { transform: "translateY(-30px)" },
        },
        jump1: {
          "0%, 100%": { transform: "translateY(10px)" },
          "45%": { transform: "translateY(-30px)" },
        },
        jump2: {
          "0%, 100%": { transform: "translateY(10px)" },
          "55%": { transform: "translateY(-30px)" },
        },
        jump3: {
          "0%, 100%": { transform: "translateY(10px)" },
          "65%": { transform: "translateY(-30px)" },
        },
        side: {
          "0%, 100%": { transform: "translateX(50px)" },
          "50%": { transform: "translateX(-50px)" },
        },
      },
    },
  },
  plugins: [],
}

