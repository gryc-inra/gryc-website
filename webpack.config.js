var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('web/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .autoProvidejQuery()
    .autoProvideVariables({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
        Popper: ['popper.js', 'default']
    })
    .enableVersioning()
    .enableSourceMaps(!Encore.isProduction())
    .enableSassLoader()
    .createSharedEntry('js/vendor', ['jquery', 'popper.js', 'bootstrap'])
    .addEntry('js/app', [
        './assets/js/auto-dismiss-alert.js',
        './assets/js/blast-scrollspy.js',
        './assets/js/blast-select-change.js',
        './assets/js/cart-btn.js',
        './assets/js/cart-fasta.js',
        './assets/js/cart-form.js',
        './assets/js/collection-type.js',
        './assets/js/copy2clipboard.js',
        './assets/js/delay.js',
        './assets/js/live-sequence-display.js',
        './assets/js/locus-tooltip.js',
        './assets/js/password-control.js',
        './assets/js/search-keyword-highlight.js',
        './assets/js/strains-filter.js',
        './assets/js/user-admin-strains.js',
        './assets/js/user-instant-search.js'
    ])
     .addStyleEntry('css/app', ['./assets/scss/app.scss'])
;

module.exports = Encore.getWebpackConfig();
