const { defineConfig } = require("cypress");

module.exports = defineConfig({
  e2e: {
    baseUrl: 'http://localhost:8000',
    experimentalRunAllSpecs: true,
  },
  viewportWidth: 1500,
  viewportHeight: 1000,
  pageLoadTimeout: 60000,

});
