export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans:  ['"DM Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                khmer: ['"Noto Sans Khmer"', 'sans-serif'],
                mono:  ['"DM Mono"', 'ui-monospace', 'monospace'],
            },
            colors: {
                brand: {
                    50:  '#F0FDF9',
                    100: '#CCFBEF',
                    200: '#99F6E0',
                    300: '#5EEAD4',
                    400: '#2DD4BF',
                    500: '#0EA88A',
                    600: '#0D9276',
                    700: '#0A7560',
                    800: '#085F4D',
                    900: '#064D3E',
                },
                warm: {
                    50:  '#FFF8F0',
                    100: '#FEEBD0',
                    200: '#FDD5A0',
                    300: '#FCBA70',
                    400: '#F99D3F',
                    500: '#F97316',
                    600: '#EA6A0A',
                    700: '#C4570A',
                    800: '#9D4509',
                    900: '#7A3508',
                },
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
            boxShadow: {
                'card': '0 1px 3px 0 rgba(0,0,0,0.06), 0 1px 2px -1px rgba(0,0,0,0.04)',
                'card-hover': '0 4px 12px 0 rgba(0,0,0,0.08), 0 2px 4px -1px rgba(0,0,0,0.04)',
            },
        },
    },
    plugins: [],
}
