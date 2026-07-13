from textwrap import dedent
import pypandoc

text=dedent("""
# Warranty Dashboard Design System

## Purpose
Design system for a modern Laravel + Tailwind warranty management application.
Focus on enterprise dashboards, data clarity, and operational efficiency.

## Design Goals
- Data-first
- Premium, minimal
- Fast CRUD workflows
- Accessible
- Responsive

## Brand
Primary: #2563EB
Success: #16A34A
Warning: #F59E0B
Danger: #DC2626
Background: #F8FAFC
Surface: #FFFFFF
Border: #E5E7EB
Text: #0F172A
Secondary: #64748B

## Typography
Display: Geist or Outfit
Body: Outfit
Monospace: Geist Mono

## Layout
1. Sticky top navigation (60px).
2. Max width 1440px.
3. Page padding: 32px desktop, 16px mobile.
4. 24px spacing grid.

## Dashboard
Order:
1. Hero
2. KPI cards
3. Charts
4. Recent warranty table
5. Activity timeline

### KPI Cards
Every card contains:
- Icon
- Number
- Label
- Trend
- Progress indicator

Statuses:
- Pending
- Warning
- Breach
- Repair
- Replacement
- Distribution
- Completed

## Charts
Preferred:
- Area
- Line
- Bar
- Donut

No 3D charts.

## Tables
Features:
- Search
- Filter
- Sorting
- Pagination
- Sticky header
- Hover state

## Forms
- Labels above fields
- 48px inputs
- Rounded 16px
- Clear validation

## Buttons
Primary:
- Blue
- Rounded 16px

Secondary:
- White
- Border

Danger:
- Red

## Badges
Pending: Gray
Repair: Blue
Replacement: Orange
Completed: Green
Breach: Red

Rounded pill badges.

## Timeline
Newest first.
Avatar + action + timestamp.

## Empty State
Illustration + action button.
Never "No data".

## Motion
- Hover translateY(-2px)
- Spring animations
- Fade-in cards
- Transform/opacity only

## Responsive
Desktop: 4 KPI columns
Tablet: 2 columns
Mobile: 1 column

No horizontal scrolling.

## Accessibility
- Contrast AA
- 44px touch targets
- Keyboard focus
- Visible validation

## Tailwind
Prefer:
- rounded-2xl
- shadow-sm
- gap-6
- grid
- container

Avoid inline styles.

## Laravel
Use Blade components:
- x-card
- x-button
- x-input
- x-modal
- x-table
- x-badge

## AI Rules
Generate interfaces similar in quality to Stripe Dashboard, Linear, Vercel, GitHub, and Notion.
Prioritize whitespace, typography, and hierarchy.
Avoid Bootstrap-like admin templates.

## Anti-patterns
- Bootstrap AdminLTE look
- Purple gradients
- Giant marketing hero
- Heavy shadows
- Tiny fonts
- Equal-width feature cards
- Circular spinners
- Excessive glassmorphism
""")
outfile="/mnt/data/Warranty-Dashboard-Design-System.md"
pypandoc.convert_text(text,'md',format='md',outputfile=outfile,extra_args=['--standalone'])
print(outfile)
