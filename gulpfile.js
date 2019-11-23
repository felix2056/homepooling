var elixir = require('laravel-elixir');
// require('laravel-elixir-vue-2');
require('laravel-elixir-webpack-official'); // recommended for vue 2
elixir(function(mix) {
	mix.sass('app.scss')
	.webpack(['app.js','datepicker.js','jquery.bxslider.js'],'./public/js/app.js');
});
