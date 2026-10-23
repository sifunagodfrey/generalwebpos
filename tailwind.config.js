/* -------------------
   Tailwind configuration.

   Two things here are load bearing. The colour scale exposes the brand palette
   as utility classes so markup can use text-primary, bg-primary-dark and the
   like. The border radius scale is flattened to zero across every step, which
   squares off every rounded utility already in the templates without those
   templates needing to be touched. Use the is-round helper class on the rare
   element that genuinely needs a circle, such as an avatar or a spinner.

   Named colours (DEFAULT, dark, deep, light, and the ink/steel/line group)
   read from the RGB-triplet CSS custom properties in resources/css/app.css
   through withOpacity() below, rather than a fixed hex. That gives two things
   at once: a class like bg-primary or text-ink still resolves at the browser
   and automatically repaints when the .dark class toggles the variables,
   exactly like the hand written nm-* component classes already do, and
   opacity modifiers such as bg-primary/10 or border-ink/50 keep working,
   since Tailwind can slot the alpha value into the rgb() call itself. Only
   the numbered 50-900 scale is a fixed hex per step, since those shades have
   no dark mode counterpart to switch to and nothing in the templates applies
   an opacity modifier to them.
------------------- */

/** @type {import('tailwindcss').Config} */

/* Reads an rgb "R G B" custom property and lets Tailwind fold an opacity
   modifier into it, falling back to fully opaque when none is given. */
function withOpacity(variableName) {
    return ({ opacityValue }) => {
        if (opacityValue === undefined) {
            return `rgb(var(${variableName}))`;
        }

        return `rgb(var(${variableName}) / ${opacityValue})`;
    };
}

export default {
    content: [
        './platform/**/*.blade.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/**/*.php',
    ],

    darkMode: 'class',

    theme: {
        /* -------------------
           Squared corners everywhere. Every step maps to zero so rounded,
           rounded-lg, rounded-xl and rounded-full all render as sharp edges.
        ------------------- */
        borderRadius: {
            none: '0px',
            sm: '0px',
            DEFAULT: '0px',
            md: '0px',
            lg: '0px',
            xl: '0px',
            '2xl': '0px',
            '3xl': '0px',
            full: '0px',
            card: '4px',
            search: '16px',
            pill: '9999px',
        },

        extend: {
            colors: {
                primary: {
                    DEFAULT: withOpacity('--primary-color-rgb'),
                    dark: withOpacity('--primary-dark-rgb'),
                    deep: withOpacity('--primary-deep-rgb'),
                    light: withOpacity('--primary-light-rgb'),
                    50: '#F7F2FD',
                    100: '#EBDDF9',
                    200: '#D9BEF3',
                    300: '#C29CEB',
                    400: '#9961DF',
                    500: '#713FCE',
                    600: '#6234B8',
                    700: '#502994',
                    800: '#3F2075',
                    900: '#301855',
                },
                secondary: {
                    DEFAULT: withOpacity('--secondary-color-rgb'),
                    dark: withOpacity('--secondary-dark-rgb'),
                    deep: withOpacity('--secondary-deep-rgb'),
                    light: withOpacity('--secondary-light-rgb'),
                    50: '#FAF5FD',
                    100: '#F2E7F9',
                    200: '#E5CBF2',
                    300: '#D3A9E8',
                    400: '#C18BDD',
                    500: '#AD70D4',
                    600: '#9255BF',
                    700: '#75429D',
                    800: '#592F79',
                    900: '#3D2054',
                },
                ink: {
                    DEFAULT: withOpacity('--ink-color-rgb'),
                    soft: withOpacity('--ink-soft-rgb'),
                    muted: withOpacity('--muted-color-rgb'),
                },
                steel: {
                    DEFAULT: withOpacity('--band-grey-rgb'),
                    dark: withOpacity('--steel-dark-rgb'),
                },
                line: withOpacity('--border-color-rgb'),
                surface: withOpacity('--surface-rgb'),
                overlay: withOpacity('--overlay-color-rgb'),
            },

            fontFamily: {
                heading: ['Lato', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                body: ['Lato', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                ui: ['Lato', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },

            letterSpacing: {
                label: '0.14em',
            },

            boxShadow: {
                flat: '0 1px 0 0 #D5D6DE',
                panel: '0 2px 14px -6px rgba(22, 29, 69, 0.22)',
                lift: '0 10px 28px -14px rgba(22, 29, 69, 0.42)',
            },

            maxWidth: {
                wrap: '80rem',
            },
        },
    },

    plugins: [],
};
