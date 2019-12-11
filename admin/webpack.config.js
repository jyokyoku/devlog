module.exports = {
	cache: true,
	externals: {
		'jquery': 'jQuery',
		'react': 'React',
		'react-dom': 'ReactDOM',
	},
	devtool: '#eval-cheap-module-source-map',
	output: {
		filename: "[name].js"
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /(node_modules|bower_components)/,
				use: [
					{
						loader: 'babel-loader',
						options: {
							presets: [
								"@babel/preset-env",
								"@babel/preset-react"
							]
						}
					}
				]
			}
		]
	}
};