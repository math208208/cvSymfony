const { defineConfig } = require('cypress')

module.exports = defineConfig({
  projectId: 'v98vc5',
  e2e: {
    baseUrl: 'http://localhost:8001',
  },
})