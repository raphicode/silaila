/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './public/**/*.{html,js,php}'
  ],
  theme: {
    extend: {
        screens: {
            sm: { min: "350px", max: "767px" },
            // => @media (min-width: 992px) { ... }
        },
        fontFamily: {
            inter: ["Inter"],
            heading: ["Figtree"],
            subheading1: ["Oswald"],
            subheading2: ["Futura PT"],
            text1: ["HvDTrial Brandon Grotesque"],
            text2: ["Frutiger"],
            poppins: ["Poppins"],
        },
        colors: {
            birutua: "#00406D",
            hijau01: "#3A5A40",
            hijau02: "#3AA640",
            hijau03: "#8FC757",
            hijau05: "#cdfccd",
            hijau04: "#38cf81",
            kuning: "#EFCF05",
        },
        backgroundImage: {
            // 'backgreen' : "url('/public/img/backgreen.svg')",
            backsilaila: "url('/silaila/img/bgsilaila.png')",
            "backgreen-blur": "url('/63-d3/img/backgreen-blur.png')",
            backwhite: "url('/63-d3/img/backwhite.svg')",
        },
        keyframes: {
            progressAnimation: {
                "0%": {
                    width: "0%",
                },
                "100%": {
                    width: "100%",
                },
            },
            zoomAnimation: {
                "0%": {
                    transform: "scale(0)",
                },
                "100%": {
                    transform: "scale(1)",
                },
            },
            popup1Animation: {
                "0%": {
                    transform: "translateX(30%)",
                },
                "50%": {
                    transform: "translateX(20%)",
                },
                "100%": {
                    transform: "translateX(0)",
                },
            },
            popup2Animation: {
                "0%": {
                    transform: "translateX(-60%)",
                },
                "50%": {
                    transform: "translateX(-50%)",
                },
                "100%": {
                    transform: "translateX(0)",
                },
            },
            popupdown1Animation: {
                "0%": {
                    transform: "translateY(20%)",
                },
                "50%": {
                    transform: "translateY(10%)",
                },
                "100%": {
                    transform: "translateY(0)",
                },
            },
            popupdown2Animation: {
                "0%": {
                    transform: "translateY(-80%)",
                },
                "50%": {
                    transform: "translateY(-50%)",
                },
                "100%": {
                    transform: "translateY(0)",
                },
            },
        },
        animation: {
            progress: "progressAnimation 3000ms linear forwards",
            "short-progress": "progressAnimation 1000ms linear forwards",
            zoom: "zoomAnimation 300ms linear forwards",
            popup1: "popup1Animation 300ms ease-in forwards",
            popup2: "popup2Animation 1000ms ease-out forwards",
            popdown1: "popupdown1Animation 200ms linear forwards",
            popdown2: "popupdown2Animation 1000ms ease-out forwards",
            popdownParadata: "popupdown2Animation 500ms linear forwards",
        },
    },
  },
  plugins: [],
}

