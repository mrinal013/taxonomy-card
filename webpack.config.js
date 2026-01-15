const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const NodePolyfillPlugin = require("node-polyfill-webpack-plugin");

module.exports = {
	...defaultConfig,
	plugins: [
		...defaultConfig.plugins,
		new NodePolyfillPlugin({
			additionalAliases: ["process"],
		}),
	],
};
