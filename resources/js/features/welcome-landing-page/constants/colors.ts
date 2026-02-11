/**
 * Color Palette Constants for TaskHive Welcome Landing Page
 * Based on design specifications with Electric Indigo and Cyan/Teal primary colors
 */

export const colors = {
  primary: {
    electricIndigo: '#5B21B6',
    cyan: '#06B6D4',
  },
  secondary: {
    softLightGray: '#F9FAFB',
    darkSlate: '#1E293B',
    mutedGray: '#CBD5E1',
  },
  accent: {
    mintGreen: '#22C55E',
    coral: '#F97316',
    roseRed: '#EF4444',
    amber: '#FACC15',
  },
  button: {
    cyan: '#06B6D4',
    cyanHover: '#0891B2',
    white: '#FFFFFF',
    darkText: '#1E293B',
  },
  header: {
    background: '#5B21B6',
    text: '#FFFFFF',
  },
  footer: {
    background: '#1E293B',
    text: '#CBD5E1',
    textHover: '#E5E7EB',
  },
  text: {
    primary: '#1E293B',
    secondary: '#64748B',
    muted: '#CBD5E1',
    white: '#FFFFFF',
  },
} as const;

export const taskStatusColors = {
  completed: '#22C55E',
  pending: '#F97316',
  overdue: '#EF4444',
  warning: '#FACC15',
} as const;

export type ColorPalette = typeof colors;
export type TaskStatusColors = typeof taskStatusColors;
