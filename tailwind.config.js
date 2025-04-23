/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {

    },
    
    fontFamily: {
      sans: ['Inter', 'sans-serif'],
    },

    fontSize: {
      // DISPLAY
      'display-xl': ['4.5rem', {
        lineHeight: '130%',
        letterSpacing: '0%',
      }],
      'display-lg': ['3.75rem', {
        lineHeight: '130%',
        letterSpacing: '0%',
      }],
      'display-md': ['3rem', {
        lineHeight: '130%',
        letterSpacing: '0%',
      }],

      // HEADING
      'heading-xl': ['2.25rem', {
        lineHeight: '130%',
        letterSpacing: '-2%',
      }],
      'heading-lg': ['1.875rem', {
        lineHeight: '130%',
        letterSpacing: '-2%',
      }],
      'heading-md': ['1.5rem', {
        lineHeight: '130%',
        letterSpacing: '-2%',
      }],
      'heading-sm': ['1.25rem', {
        lineHeight: '130%',
        letterSpacing: '-2%',
      }],

      // BODY
      'body-xl': ['1.125rem', {
        lineHeight: '1.75rem',
        letterSpacing: '-2%',
      }],
      'body-lg': ['1rem', {
        lineHeight: '1.25rem',
        letterSpacing: '-2%',
      }],
      'body-md': ['0.875rem', {
        lineHeight: '1.5rem',
        letterSpacing: '-2%',
      }],
      'body-sm': ['0.75rem', {
        lineHeight: '1.25rem',
        letterSpacing: '-2%',
      }],

      // LABEL
      'label': ['0.75rem', {
        lineHeight: '1.125rem',
        letterSpacing: '-2%',
      }],
    },
    
    spacing: {
        '0' : '0rem',
        '0.5': '0.25rem',
        '1': '0.5rem',
        '2': '1rem',
        '3': '1.5rem',
        '4': '2rem',
        '5': '2.5rem',
        '6': '3rem',
        '7': '3.5rem',
        '8': '4rem',
        '9': '4.5rem',
        '10': '5rem',
        '11': '5.5rem',
        '12': '6rem',
        '13': '6.5rem',
        '14': '7rem',
        '15': '7.5rem',
      },

    colors: {
      'transparent': 'transparent',
      'current': 'currentColor',
        // NEUTRAL COLORS
        'surface': {
          DEFAULT: '#FCF8F8',
          dark: '#3A3939',
        },
        'surface-dim': {
          DEFAULT: '#dcd9d9',
          dark: '#131313',
        },
        'shadow': {
          DEFAULT: '#000000',
          dark: '#000000',
        },
        'inverse-surface': {
          DEFAULT: '#313030',
          dark: '#e5e2e1',
        },
        'on-inverse-surface': {
          DEFAULT: '#f3f0ef',
          dark: '#313030',
        },
        'surface-container-lowest': {
          DEFAULT: '#ffffff',
          dark: '#0e0e0e',
        },
        'surface-container-low': {
          DEFAULT: '#f6f3f2',
          dark: '#1c1b1b',
        },
        'surface-container': {
          DEFAULT: '#f1edec',
          dark: '#201f1f',
        },
        'surface-container-high': {
          DEFAULT: '#ebe7e7',
          dark: '#2a2a2a',
        },
        'surface-container-highest': {
          DEFAULT: '#e5e2e1',
          dark: '#353534',
        },
        'on-surface': {
          DEFAULT: '#1c1b1b',
          dark: '#e5e2e1',
        },
        'on-surface-variant': {
          DEFAULT: '#44474a',
          dark: '#c4c7cb',
        },
        'outline': {
          DEFAULT: '#74777B',
          dark: '#8e9195',
        },
        'outline-variant': {
          DEFAULT: '#c4c7cb',
          dark: '#44474a',
        },
  
        // PRIMARY COLORS
        'primary': {
          DEFAULT: '#0059bb',
          dark: '#adc7ff',
        },
        'primary-container': {
          DEFAULT: '#0070ea',
          dark: '#4a8eff',
        },
        'inverse-primary': {
          DEFAULT: '#adc7ff',
          dark: '#0059bb',
        },
        'on-primary': {
          DEFAULT: '#ffffff',
          dark: '#002e68',
        },
        'on-primary-container': {
          DEFAULT: '#fefcff',
          dark: '#001537',
        },
  
        // SECONDARY COLORS
        'secondary': {
          DEFAULT: '#7e5700',
          dark: '#ffd89c',
        },
        'secondary-container': {
          DEFAULT: '#ffb400',
          dark: '#ffb400',
        },
        'on-secondary': {
          DEFAULT: '#ffffff',
          dark: '#422c00',
        },
        'on-secondary-container': {
          DEFAULT: '#6b4900',
          dark: '#6b4900',
        },
  
        // TERTIARY COLORS
        'tertiary': {
          DEFAULT: '#006C53',
          dark: '#42e0b5',
        },
        'tertiary-container': {
          DEFAULT: '#00c49a',
          dark: '#00c49a',
        },
        'on-tertiary': {
          DEFAULT: '#ffffff',
          dark: '#00382a',
        },
        'on-tertiary-container': {
          DEFAULT: '#004a39',
          dark: '#004a39',
        },
  
        // ERROR COLORS
        'error': {
          DEFAULT: '#b9172f',
          dark: '#ffb3b2',
        },
        'error-container': {
          DEFAULT: '#dc3545',
          dark: '#dc3545',
        },
        'on-error': {
          DEFAULT: '#ffffff',
          dark: '#680013',
        },
        'on-error-container': {
          DEFAULT: '#ffffff',
          dark: '#ffffff',
        },
  
        // SUCCESS COLORS
        'success': {
          DEFAULT: '#006e25',
          dark: '#66df75',
        },
        'success-container': {
          DEFAULT: '#28a745',
          dark: '#28a745',
        },
        'on-success': {
          DEFAULT: '#ffffff',
          dark: '#00390f',
        },
        'on-success-container': {
          DEFAULT: '#00330d',
          dark: '#00330d',
        },
  
        // WARNING COLORS
        'warning': {
          DEFAULT: '#785900',
          dark: '#ffe4af',
        },
        'warning-container': {
          DEFAULT: '#ffc107',
          dark: '#ffc107',
        },
        'on-warning': {
          DEFAULT: '#ffffff',
          dark: '#3f2e00',
        },
        'on-warning-container': {
          DEFAULT: '#6d5100',
          dark: '#6d5100',
        },
      },
  },
  plugins: [],
}

