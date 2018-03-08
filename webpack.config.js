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
    .enableSassLoader(function(sassOptions) {}, {
        resolveUrlLoader: false
    })
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
        './assets/js/feature-dynamic-sequence.js',
        './assets/js/form-species.js',
        './assets/js/images.js',
        './assets/js/locus-tooltip.js',
        './assets/js/modal-confirmation-message.js',
        './assets/js/password-control.js',
        './assets/js/popover.js',
        './assets/js/search-highlight.js',
        './assets/js/strains-filter.js',
        './assets/js/user-instant-search.js'
    ])
    .addStyleEntry('css/app', ['./assets/scss/app.scss'])
;

module.exports = Encore.getWebpackConfig();
