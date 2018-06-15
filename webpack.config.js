var Encore = require('@symfony/webpack-encore');

Encore
  // the project directory where all compiled assets will be stored
  .setOutputPath('web/assets/dist')
  // the public path used by the web server to access the previous directory
  .setPublicPath('/assets/dist')

  .addEntry('scripts_dso', './src/AppBundle/Resources/public/js/script.js')
  // .addStyleEntry('style_dso', './src/AppBundle/Resources/public/styles/dso.scss')

  // allow legacy applications to use $/jQuery as a global variable
  .autoProvidejQuery()

  .enableSassLoader(function(sassOptions) {}, {
    resolveUrlLoader: false
  })

  // For bootstrap
  .autoProvideVariables({ Popper: ['popper.js', 'default'] })

  // empty the outputPath dir before each build
  .cleanupOutputBeforeBuild()
;

module.exports = Encore.getWebpackConfig();