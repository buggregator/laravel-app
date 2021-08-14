const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors')

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    variants: {
        extend: {
            opacity: ['disabled'],
            borderWidth: ['hover']
        }
    },
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            transitionProperty: {
                'height': 'height'
            },
            boxShadow: {
                bottom: 'inset 0 -38px 38px -38px #ececec',
            },
        },
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            black: colors.black,
            white: colors.white,
            gray: colors.trueGray,
            purple: colors.indigo,
            green: colors.green,
            blue: colors.blue,
            red: colors.rose,
            orange: colors.amber,
        }
    }
};
