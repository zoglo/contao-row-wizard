const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('./public/')
    .setPublicPath(Encore.isDevServer() ? '/public/' : '/bundles/contaosimplecolumnwizard/')
    .setManifestKeyPrefix('')

    .cleanupOutputBeforeBuild()
    .disableSingleRuntimeChunk()
    .enableBuildNotifications()

    .addEntry('simple-column-wizard', './assets/column-wizard.js')

    .enablePostCssLoader()
    .enableVersioning(Encore.isProduction())

    .configureDevServerOptions((options) => Object.assign({}, options, {
        static: false,
        hot: true,
        liveReload: true,
        allowedHosts: 'all',
        watchFiles: ['assets/*', 'contao/**/*', 'src/**/*', 'translations/**/*'],
        client: {
            overlay: false
        }
    }));

module.exports = Encore.getWebpackConfig();
