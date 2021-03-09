module.exports = {
  root: true,
  env: {
    node: true
  },
  extends: ["plugin:vue/strongly-recommended", "@vue/standard"],
  rules: {
    "no-console": process.env.NODE_ENV === "production" ? "error" : "off",
    "no-debugger": process.env.NODE_ENV === "production" ? "error" : "off",
    "no-trailing-spaces": ["error", { skipBlankLines: true }],
    "vue/html-self-closing": [
      "error",
      {
        html: {
          normal: "never"
        }
      }
    ]
  },
  overrides: [
    {
      files: ["*.vue"],
      rules: {
        indent: "off"
      }
    }
  ],
  parserOptions: {
    parser: "babel-eslint"
  }
};
