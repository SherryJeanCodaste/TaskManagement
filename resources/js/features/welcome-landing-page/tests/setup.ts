/**
 * Test setup file for Vitest
 * Configures the testing environment for Vue components
 */

import { expect, afterEach } from 'vitest';
import { cleanup } from '@testing-library/vue';
import '@testing-library/jest-dom';

// Cleanup after each test
afterEach(() => {
  cleanup();
});
