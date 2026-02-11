# TaskHive Welcome Landing Page

This feature implements the TaskHive welcome/landing page with a modern, light-themed design featuring Electric Indigo and Cyan/Teal as primary colors.

## Directory Structure

```
welcome-landing-page/
├── components/          # Vue components for the landing page
├── constants/          # Color palette and breakpoint constants
│   ├── colors.ts       # Color palette definitions
│   └── breakpoints.ts  # Responsive breakpoint definitions
├── styles/            # Component-specific styles
├── tests/             # Unit and property-based tests
│   └── setup.ts       # Test environment setup
└── README.md          # This file
```

## Color Palette

The landing page uses the following color scheme:

- **Primary Colors**: Electric Indigo (#5B21B6), Cyan (#06B6D4)
- **Secondary Colors**: Soft Light Gray (#F9FAFB), Dark Slate (#1E293B), Muted Gray (#CBD5E1)
- **Accent Colors**: Mint Green (#22C55E), Coral (#F97316), Rose Red (#EF4444), Amber (#FACC15)

## Responsive Breakpoints

- **Mobile**: 0-767px
- **Tablet**: 768-1023px
- **Desktop**: 1024px+

## Testing

The feature uses Vitest for unit testing and fast-check for property-based testing.

### Run Tests

```bash
npm test                 # Run tests in watch mode
npm run test:ui         # Run tests with UI
npm run test:run        # Run tests once
```

## Requirements

This feature implements requirements from the welcome-landing-page specification:
- Requirements 1.1-1.6: Header with authentication links
- Requirements 2.1-2.5: Split-screen layout
- Requirements 3.1-3.8: Content and branding areas
- Requirements 4.1-4.8: Feature cards
- Requirements 5.1-5.5: Footer
- Requirements 6.1-6.6: Typography and visual hierarchy
- Requirements 7.1-7.4: Responsive behavior
- Requirements 8.1-8.8: Button styling and interaction
- Requirements 9.1-9.5: Content minimalism
- Requirements 10.1-10.5: Content constraints
