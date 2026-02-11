/**
 * Unit tests for constants
 * Verifies color palette and breakpoint constants are correctly defined
 */

import { describe, it, expect } from 'vitest';
import { colors, taskStatusColors, breakpoints, isMobileViewport, isTabletViewport, isDesktopViewport } from '../constants';

describe('Color Constants', () => {
  it('should have correct primary colors', () => {
    expect(colors.primary.electricIndigo).toBe('#5B21B6');
    expect(colors.primary.cyan).toBe('#06B6D4');
  });

  it('should have correct secondary colors', () => {
    expect(colors.secondary.softLightGray).toBe('#F9FAFB');
    expect(colors.secondary.darkSlate).toBe('#1E293B');
    expect(colors.secondary.mutedGray).toBe('#CBD5E1');
  });

  it('should have correct accent colors', () => {
    expect(colors.accent.mintGreen).toBe('#22C55E');
    expect(colors.accent.coral).toBe('#F97316');
    expect(colors.accent.roseRed).toBe('#EF4444');
    expect(colors.accent.amber).toBe('#FACC15');
  });

  it('should have correct button colors', () => {
    expect(colors.button.cyan).toBe('#06B6D4');
    expect(colors.button.cyanHover).toBe('#0891B2');
    expect(colors.button.white).toBe('#FFFFFF');
    expect(colors.button.darkText).toBe('#1E293B');
  });

  it('should have correct task status colors', () => {
    expect(taskStatusColors.completed).toBe('#22C55E');
    expect(taskStatusColors.pending).toBe('#F97316');
    expect(taskStatusColors.overdue).toBe('#EF4444');
    expect(taskStatusColors.warning).toBe('#FACC15');
  });
});

describe('Breakpoint Constants', () => {
  it('should have correct breakpoint values', () => {
    expect(breakpoints.mobile).toBe(0);
    expect(breakpoints.tablet).toBe(768);
    expect(breakpoints.desktop).toBe(1024);
  });

  it('should correctly identify mobile viewport', () => {
    expect(isMobileViewport(767)).toBe(true);
    expect(isMobileViewport(768)).toBe(false);
    expect(isMobileViewport(320)).toBe(true);
  });

  it('should correctly identify tablet viewport', () => {
    expect(isTabletViewport(768)).toBe(true);
    expect(isTabletViewport(1023)).toBe(true);
    expect(isTabletViewport(767)).toBe(false);
    expect(isTabletViewport(1024)).toBe(false);
  });

  it('should correctly identify desktop viewport', () => {
    expect(isDesktopViewport(1024)).toBe(true);
    expect(isDesktopViewport(1920)).toBe(true);
    expect(isDesktopViewport(1023)).toBe(false);
  });
});
