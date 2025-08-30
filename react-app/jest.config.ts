import type { Config } from 'jest'

const config: Config = {
  preset: 'ts-jest/presets/default-esm',
  testEnvironment: 'jsdom',
  globals: {
    'ts-jest': {
      tsconfig: '<rootDir>/tsconfig.test.json',
      useESM: true,
    },
  },
  setupFilesAfterEnv: ['<rootDir>/jest.setup.ts'],
  moduleNameMapper: {
    '^.+\\.(css|less|scss|sass)$': 'identity-obj-proxy',
  },
  extensionsToTreatAsEsm: ['.ts', '.tsx'],
  testPathIgnorePatterns: ['/node_modules/', '/e2e/'],
}

export default config
