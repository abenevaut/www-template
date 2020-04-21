require('dotenv').config();
const mix = require('laravel-mix');
const path = require('path');
const imageminMozjpeg = require('imagemin-mozjpeg');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const CopyWebpackPlugin = require('copy-webpack-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const FaviconsWebpackPlugin = require('favicons-webpack-plugin')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

if (mix.inProduction()) {
  mix.version();
}

mix
  .autoload({
    jquery: ['$', 'window.jQuery', 'jQuery', 'window.$'],
    moment: ['moment', 'window.moment'],
    'pusher-js': ['Pusher', 'window.Pusher'],
  })
  .webpackConfig({
    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'resources/js'),
      },
      extensions: ['.js', '.vue'],
    },
    module: {
      rules: [{
        enforce: 'pre',
        test: /\.(js|vue)$/,
        loader: 'eslint-loader',
        exclude: /(node_modules|tests)/,
      }, {
        // Exposes jQuery for use outside Webpack build
        test: require.resolve('jquery'),
        use: [{
          loader: 'expose-loader',
          options: 'jQuery',
        }, {
          loader: 'expose-loader',
          options: '$',
        }],
      }],
    },
    plugins: [
      new StyleLintPlugin({
        configFile: '.stylelintrc',
        context: 'resources/sass',
      }),
      new CopyWebpackPlugin([
        {
          from: 'resources/images',
          to: 'images',
        },
      ]),
      new ImageminPlugin({
        test: /\.(jpe?g|png|gif)$/i, // |svg
        pngquant: {
          quality: '65-80',
        },
        plugins: [
          imageminMozjpeg({
            quality: 65,
            // Set the maximum memory to use in kbytes
            maxMemory: 1000 * 512,
          }),
        ],
      }),
      new FaviconsWebpackPlugin({
        logo: path.resolve(__dirname, 'resources/images/logo.png'),
        prefix: 'images/',
        cache: true,
        inject: false,
        mode: 'webapp',
        devMode: 'webapp',
        favicons: {
          background: '#fff',
          theme_color: '#fff',
          icons: {
            coast: false,
            yandex: false,
          },
        },
      }),
    ],
  })
  .sourceMaps(false, 'eval')
  .js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css');
