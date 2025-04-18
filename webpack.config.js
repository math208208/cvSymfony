const Encore = require('@symfony/webpack-encore');

Encore
    // Setup de base
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .cleanupOutputBeforeBuild()
    .enableSingleRuntimeChunk()
    .enableSassLoader()
    .enableSourceMaps(!Encore.isProduction()) // Source maps en dev seulement


    // Features
    .enableSassLoader(options => {
        options.sassOptions = {
            outputStyle: 'expanded', // Format lisible en dev
            sourceMap: true // Active les source maps
        };
    })

    .copyFiles({
        from: './assets/images',  // Dossier source
        to: 'images/[path][name].[hash:8].[ext]',  // Dossier de destination
        pattern: /\.(png|jpg|jpeg|gif|ico|svg|webp)$/  // Types de fichiers
    })
    
    .autoProvidejQuery()
    .enableVersioning(Encore.isProduction()); // Versioning en prod seulement

module.exports = Encore.getWebpackConfig();