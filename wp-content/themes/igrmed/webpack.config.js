const path = require("path");
const TerserPlugin = require("terser-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");

module.exports = (env, argv) => {
  const mode = argv?.mode || "production";
  const isProd = mode === "production";

  return {
    mode,
  entry: {
    main: "./assets/js/main.js",
  },
  output: {
    filename: "js/[name].bundle.js",
    chunkFilename: "js/[name].chunk.js",
    path: path.resolve(__dirname, "dist"),
    publicPath: "/wp-content/themes/igrmed/dist/",
    clean: true,
  },
  cache: {
    type: "filesystem",
  },
  stats: "errors-warnings",
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          "css-loader",
          {
            loader: "postcss-loader",
            options: {
              postcssOptions: {
                config: path.resolve(__dirname, "postcss.config.js"),
              },
            },
          },
          {
            loader: "sass-loader",
            options: {
              api: "modern",
              sassOptions: {
                silenceDeprecations: ["legacy-js-api", "import"],
              },
            },
          },
        ],
      },
      {
        test: /\.css$/,
        use: [
          MiniCssExtractPlugin.loader,
          "css-loader",
          {
            loader: "postcss-loader",
            options: {
              postcssOptions: {
                config: path.resolve(__dirname, "postcss.config.js"),
              },
            },
          },
        ],
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: [
              [
                "@babel/preset-env",
                {
                  targets: { safari: "12", ios: "12" },
                  useBuiltIns: "usage",
                  corejs: 3,
                },
              ],
            ],
            plugins: ["@babel/plugin-transform-runtime"],
            cacheDirectory: true,
          },
        },
      },
      {
        test: /\.(woff|woff2|ttf|otf|eot)$/,
        type: "asset/resource",
        generator: {
          filename: "fonts/[name][ext]",
        },
      },
      {
        test: /\.(webp|svg)$/,
        type: "asset/resource",
        generator: {
          filename: "images/[name][ext]",
        },
      },
      {
        test: /\.(png|jpe?g|gif|avif)$/i,
        type: "asset/resource",
        generator: {
          filename: "images/[name][ext]",
        },
      },
    ],
  },
  optimization: {
    minimizer: [new TerserPlugin(), new CssMinimizerPlugin()],
    moduleIds: "deterministic",
    chunkIds: "deterministic",
    splitChunks: {
      cacheGroups: {
        vendorCore: {
          test: /[\\/]node_modules[\\/](core-js|@babel\/runtime)/,
          name: "vendors-core",
          chunks: "all",
          enforce: true,
        },
      },
    },
  },
  performance: {
    maxEntrypointSize: 512000,
    maxAssetSize: 512000,
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "css/[name].bundle.css",
      chunkFilename: "css/[name].chunk.css",
    }),
  ],
  devtool: isProd ? "source-map" : "eval-cheap-module-source-map",
  };
};
