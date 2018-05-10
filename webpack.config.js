var Encore = require('@symfony/webpack-encore');

Encore
  // the project directory where all compiled assets will be stored
  .setOutputPath('web/assets/dist')
  // the public path used by the web server to access the previous directory
  .setPublicPath('/assets/dist')

  .addEntry('bootstrap', './app/Resources/public/assets/js/bootstrap.js')
  .addEntry('scripts_dso', './src/AppBundle/Resources/public/js/script.js')

  // allow legacy applications to use $/jQuery as a global variable
  .autoProvidejQuery()

  // empty the outputPath dir before each build
  .cleanupOutputBeforeBuild()
;

module.exports = Encore.getWebpackConfig();