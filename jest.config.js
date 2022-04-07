module.exports = {
    testEnvironment: "jsdom",
    transform: {
      ".*\\.(vue)$": "<rootDir>/node_modules/vue-jest",
      "^.+\\.js$": "<rootDir>/node_modules/babel-jest",
    },
    testMatch: [
        "<rootDir>/resources/js/tests/*.js"
    ],
    moduleFileExtensions: ["js", "vue"],
  };