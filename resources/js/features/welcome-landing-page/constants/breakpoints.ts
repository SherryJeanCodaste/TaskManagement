/**
 * Responsive Breakpoint Constants for TaskHive Welcome Landing Page
 * Based on design specifications for mobile, tablet, and desktop layouts
 */

export const breakpoints = {
  mobile: 0,
  tablet: 768,
  desktop: 1024,
} as const;

export type Breakpoints = typeof breakpoints;

/**
 * Helper function to check if viewport is mobile
 */
export const isMobileViewport = (width: number): boolean => {
  return width < breakpoints.tablet;
};

/**
 * Helper function to check if viewport is tablet
 */
export const isTabletViewport = (width: number): boolean => {
  return width >= breakpoints.tablet && width < breakpoints.desktop;
};

/**
 * Helper function to check if viewport is desktop
 */
export const isDesktopViewport = (width: number): boolean => {
  return width >= breakpoints.desktop;
};
