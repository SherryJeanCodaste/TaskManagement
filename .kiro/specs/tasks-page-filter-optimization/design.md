# Design Document: Tasks Page Filter Optimization

## Overview

The Tasks page currently displays both a Project column in the table and a Project filter dropdown, creating potential redundancy. This design evaluates the trade-offs of different approaches and recommends an optimal solution based on user workflows, visual clarity, and information architecture principles.

### Current State Analysis

**Existing Implementation:**
- Filter bar with 4 dropdowns: Project, Category (customers only), Status, Date
- Table with 5 columns: Task Title, Project, Category, Status, Created
- Client-side filtering using Vue computed properties
- Separate data flows for customers (see their created tasks) vs developers (see assigned tasks)

**Identified Issue:**
When a user selects a specific project from the Project filter, all visible rows in the table show the same project name in the Project column, creating visual redundancy.

### Design Decision: Remove Project Filter, Keep Project Column

**Rationale:**

1. **Information Density**: The Project column provides persistent context for each task without requiring interaction. Users can scan the table and immediately see project associations.

2. **Filter Efficiency**: With only 4 filters currently, removing one reduces cognitive load. Users can still achieve project-specific views by:
   - Scanning the Project column visually (for quick reference)
   - Using browser search (Ctrl+F) to find specific projects
   - Potentially adding a global search/filter input in future iterations

3. **Common Workflow Analysis**:
   - **Customers**: Typically work across multiple projects simultaneously. Seeing all tasks with project context is more valuable than filtering to one project.
   - **Developers**: Usually assigned to 1-3 projects. The Project column provides necessary context without overwhelming the view.

4. **Redundancy Elimination**: When Project filter is active, the Project column becomes redundant (all rows show same value). Removing the filter eliminates this redundancy.

5. **Table Width**: Removing one filter dropdown provides more horizontal space for the remaining filters and improves mobile responsiveness.

**Alternative Considered: Remove Project Column, Keep Project Filter**

This was rejected because:
- Loses persistent visual context when viewing all tasks
- Requires users to remember which project they're viewing
- Makes it harder to quickly scan and compare tasks across projects
- The filter would need to be "sticky" or prominently displayed to maintain context

## Architecture

### Component Structure

```
Tasks.vue (Main Component)
├── Filter Bar Section
│   ├── Category Filter (customers only)
│   ├── Status Filter
│   ├── Date Filter
│   └── Clear Filters Button
├── Stats Cards Section
│   ├── Total Tasks
│   ├── Pending
│   ├── In Progress
│   └── Completed
└── Task Table Section
    ├── Table Header
    │   ├── Task Title
    │   ├── Project
    │   ├── Category
    │   ├── Status
    │   └── Created
    └── Table Body (filtered rows)
```

### Data Flow

```
Props (from backend)
  ├── tasks: Task[]
  ├── projects: Project[] (no longer needed for filter)
  └── isCustomer: boolean
        ↓
Reactive State
  ├── categoryFilter: ref('all')
  ├── statusFilter: ref('all')
  └── dateFilter: ref('all')
        ↓
Computed Properties
  ├── filteredTasks (applies all filters)
  └── stats (calculates counts from filtered tasks)
        ↓
Template Rendering
  └── Table displays filteredTasks with Project column
```

## Components and Interfaces

### Modified Filter State

```typescript
// Remove projectFilter
// const projectFilter = ref('all'); // REMOVED

const categoryFilter = ref('all');
const statusFilter = ref('all');
const dateFilter = ref('all');
```

### Updated Filtering Logic

```typescript
const filteredTasks = computed(() => {
    let filtered = [...props.tasks];

    // Project filter REMOVED - users scan Project column instead

    // Category filter (customers only)
    if (categoryFilter.value !== 'all') {
        filtered = filtered.filter(t => t.category === categoryFilter.value);
    }

    // Status filter
    if (statusFilter.value !== 'all') {
        filtered = filtered.filter(t => t.status === statusFilter.value);
    }

    // Date filter
    if (dateFilter.value !== 'all') {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        
        filtered = filtered.filter(t => {
            const taskDate = new Date(t.created_at);
            const taskDay = new Date(taskDate.getFullYear(), taskDate.getMonth(), taskDate.getDate());
            
            if (dateFilter.value === 'today') {
                return taskDay.getTime() === today.getTime();
            } else if (dateFilter.value === 'week') {
                const weekAgo = new Date(today);
                weekAgo.setDate(weekAgo.getDate() - 7);
                return taskDay >= weekAgo;
            } else if (dateFilter.value === 'month') {
                const monthAgo = new Date(today);
                monthAgo.setMonth(monthAgo.getMonth() - 1);
                return taskDay >= monthAgo;
            }
            return true;
        });
    }

    return filtered;
});
```

### Updated Clear Filters Function

```typescript
const clearFilters = () => {
    // projectFilter.value = 'all'; // REMOVED
    categoryFilter.value = 'all';
    statusFilter.value = 'all';
    dateFilter.value = 'all';
};
```

### Updated Clear Filters Button Condition

```typescript
// Show clear button if any filter is active
v-if="categoryFilter !== 'all' || statusFilter !== 'all' || dateFilter !== 'all'"
```

### Backend Changes

**TaskController.php** - Remove projects from response for customers:

```php
public function index()
{
    $user = auth()->user();
    
    if ($user->isCustomer()) {
        $tasks = Task::with(['project', 'assignedUser'])
            ->where('customer_id', $user->id)
            ->latest()
            ->get();
        
        // Projects array no longer needed for filter dropdown
        // Keep empty array for backward compatibility
        $projects = [];
    } else {
        $tasks = Task::with(['project', 'customer'])
            ->where('assigned_to', $user->id)
            ->latest()
            ->get();
        
        $projects = [];
    }

    return Inertia::render('Tasks', [
        'tasks' => $tasks,
        'projects' => $projects, // Empty array
        'isCustomer' => $user->isCustomer(),
    ]);
}
```

## Data Models

### Task Interface (TypeScript)

```typescript
interface Task {
    id: number;
    title: string;
    description: string | null;
    category: 'frontend' | 'backend' | 'server';
    status: 'pending' | 'in_progress' | 'completed';
    project_id: number;
    customer_id: number;
    assigned_to: number | null;
    created_at: string;
    updated_at: string;
    project?: Project;
    customer?: User;
    assignedUser?: User;
}

interface Project {
    id: number;
    name: string;
}

interface Props {
    tasks: Task[];
    projects: Project[]; // Will be empty array after optimization
    isCustomer: boolean;
}
```

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Acceptance Criteria Testing Prework

1.1 THE System SHALL maintain the current Project column in the task table
  Thoughts: This is about the UI structure. We can test that the rendered table contains a column header for "Project" and that each row contains project data.
  Testable: yes - example

1.2 THE System SHALL maintain the current Project filter dropdown
  Thoughts: This requirement is being intentionally violated by the design decision. The design removes the Project filter.
  Testable: no (requirement superseded by design decision)

1.3 WHEN a user applies the Project filter, THE System SHALL filter tasks to show only those belonging to the selected project
  Thoughts: This requirement is no longer applicable since we're removing the Project filter.
  Testable: no (requirement superseded by design decision)

1.4 WHEN the Project column is displayed, THE System SHALL show the project name for each task
  Thoughts: This is testable. For any task with a project relationship, the rendered row should contain the project name.
  Testable: yes - property

3.1 WHEN a customer user has tasks across multiple projects, THE System SHALL provide a clear way to identify which project each task belongs to
  Thoughts: This is validated by the Project column being present and displaying project names.
  Testable: yes - example

3.2 WHEN a customer user wants to focus on a single project, THE System SHALL provide an efficient filtering mechanism
  Thoughts: After removing the Project filter, this is achieved through visual scanning and browser search. This is a UX goal rather than a testable property.
  Testable: no

3.3 WHEN a customer user views filtered results, THE System SHALL maintain visual clarity without redundant information
  Thoughts: This is a UX quality goal that's subjective.
  Testable: no

3.4 THE System SHALL preserve the Category filter for customer users regardless of Project filter changes
  Thoughts: This is testable. For customer users, the Category filter should always be present and functional.
  Testable: yes - property

4.1 WHEN a developer user has tasks across multiple projects, THE System SHALL provide a clear way to identify which project each task belongs to
  Thoughts: Same as 3.1, validated by Project column presence.
  Testable: yes - example

4.2 WHEN a developer user wants to focus on a single project, THE System SHALL provide an efficient filtering mechanism
  Thoughts: Same as 3.2, this is a UX goal.
  Testable: no

4.3 WHEN a developer user views the task table, THE System SHALL not display the Category filter
  Thoughts: This is testable. For developer users, the Category filter should not be rendered.
  Testable: yes - property

5.1 WHEN a user selects a project filter, THE System SHALL update the task table to show only tasks from that project
  Thoughts: No longer applicable since Project filter is removed.
  Testable: no (requirement superseded by design decision)

5.2 WHEN a user clears filters, THE System SHALL reset all filter dropdowns to "All" and display all tasks
  Thoughts: This is testable. After clearing filters, all filter refs should be 'all' and filteredTasks should equal all tasks.
  Testable: yes - property

5.3 WHEN filters are applied, THE System SHALL display the count of filtered tasks versus total tasks
  Thoughts: This is testable. The UI should show "Showing X of Y tasks" where X is filtered count and Y is total.
  Testable: yes - property

5.4 WHEN a user applies multiple filters, THE System SHALL combine them with AND logic
  Thoughts: This is testable. Applying multiple filters should result in tasks that match ALL filter criteria.
  Testable: yes - property

6.1 THE Task_Table SHALL display Task Title, Category, Status, and Created columns regardless of filter optimization decisions
  Thoughts: This is testable. The table should always have these column headers.
  Testable: yes - example

6.2 WHEN a user clicks on a task row, THE System SHALL navigate to the task detail page
  Thoughts: This is testable. Clicking a row should trigger navigation.
  Testable: yes - example

6.3 WHEN a developer user views the Status column, THE System SHALL display an interactive dropdown to update task status
  Thoughts: This is testable. For developer users, the status cell should contain a select element.
  Testable: yes - property

6.4 WHEN a customer user views the Status column, THE System SHALL display a read-only status badge
  Thoughts: This is testable. For customer users, the status cell should contain a span element, not a select.
  Testable: yes - property

8.1 WHEN a user changes any filter, THE System SHALL update the displayed tasks within 100ms
  Thoughts: This is a performance requirement. Since filtering is client-side with Vue computed properties, it should be nearly instantaneous, but testing exact timing in unit tests is not practical.
  Testable: no

8.2 WHEN the page loads, THE System SHALL render all tasks and filters within 500ms
  Thoughts: This is a performance requirement that depends on network, server, and browser performance. Not suitable for unit testing.
  Testable: no

8.3 THE System SHALL perform all filtering operations client-side without server requests
  Thoughts: This is testable. We can verify that filter changes don't trigger network requests.
  Testable: yes - example

9.1 WHEN filter dropdowns are displayed, THE System SHALL provide clear labels for each filter
  Thoughts: This is testable. Each filter select should have an associated label element.
  Testable: yes - example

9.2 WHEN a user interacts with filters, THE System SHALL provide visual feedback
  Thoughts: This is about CSS and visual design, which is subjective.
  Testable: no

9.3 THE System SHALL maintain keyboard navigation support for all filter controls
  Thoughts: This is testable. Filter controls should be focusable and operable via keyboard.
  Testable: yes - example

9.4 WHEN screen space is limited, THE System SHALL ensure filters remain usable on smaller screens
  Thoughts: This is about responsive design and is subjective/visual.
  Testable: no

### Property Reflection

Reviewing the testable properties:

- **1.4** (Project column shows project name) and **3.1/4.1** (users can identify project) are essentially the same - they all validate that the Project column displays project names. These can be combined.
- **3.4** (Category filter for customers) and **4.3** (no Category filter for developers) are complementary and should remain separate.
- **6.3** (developer status dropdown) and **6.4** (customer status badge) are complementary and should remain separate.
- **5.2, 5.3, 5.4** are all distinct filter behaviors and should remain separate.

All other properties provide unique validation value.

### Properties

**Property 1: Project column displays project names**
*For any* task in the task table that has an associated project, the rendered table row should display the project name in the Project column.
**Validates: Requirements 1.1, 1.4, 3.1, 4.1**

**Property 2: Category filter visible for customers only**
*For any* user session, if the user is a customer, the Category filter dropdown should be rendered; if the user is a developer, the Category filter should not be rendered.
**Validates: Requirements 3.4, 4.3**

**Property 3: Clear filters resets all filters**
*For any* filter state, when the clear filters action is triggered, all filter values should be reset to 'all' and the displayed tasks should equal the total tasks.
**Validates: Requirements 5.2**

**Property 4: Filter count displays correctly**
*For any* filter state, the displayed count should show the number of filtered tasks and the total number of tasks in the format "Showing X of Y tasks".
**Validates: Requirements 5.3**

**Property 5: Multiple filters use AND logic**
*For any* combination of active filters (category, status, date), the displayed tasks should only include tasks that satisfy ALL active filter conditions.
**Validates: Requirements 5.4**

**Property 6: Developer users see status dropdown**
*For any* task row when viewed by a developer user, the status cell should contain an interactive select element allowing status updates.
**Validates: Requirements 6.3**

**Property 7: Customer users see status badge**
*For any* task row when viewed by a customer user, the status cell should contain a read-only span element displaying the status.
**Validates: Requirements 6.4**

## Error Handling

### Filter State Errors

**Invalid Filter Values:**
- All filter dropdowns use predefined option values
- Vue's v-model binding ensures only valid values are set
- No additional validation needed

**Missing Project Data:**
- Tasks without associated projects should display empty string or "N/A" in Project column
- Handled by optional chaining: `task.project?.name`

### Data Loading Errors

**Empty Task List:**
- Display empty state message: "No tasks yet. Create your first task!"
- Provide "Create Task" button for customers

**No Filtered Results:**
- Display message: "No tasks match your filters."
- Provide "Clear Filters" button to reset

## Testing Strategy

### Unit Testing Approach

Use Vitest with Vue Test Utils for component testing. Focus on:

1. **Filter Logic**: Test that each filter correctly filters the task list
2. **User Role Behavior**: Test that customers and developers see appropriate UI elements
3. **Clear Filters**: Test that clearing filters resets state correctly
4. **Empty States**: Test that appropriate messages display when no tasks or no filtered results

### Property-Based Testing Approach

Use fast-check library for property-based testing with minimum 100 iterations per test.

**Test Configuration:**
```typescript
import fc from 'fast-check';

// Each property test should run 100+ iterations
fc.assert(
    fc.property(/* generators */, (/* inputs */) => {
        // property assertion
    }),
    { numRuns: 100 }
);
```

**Generators Needed:**
- Task generator (with various project, category, status, date combinations)
- User role generator (customer vs developer)
- Filter state generator (all combinations of filter values)

### Integration Testing

Test the complete filtering workflow:
1. Load page with tasks
2. Apply filters in various combinations
3. Verify filtered results
4. Clear filters
5. Verify all tasks displayed

### Visual Regression Testing

Since we're removing a UI element (Project filter), capture screenshots before and after to ensure:
- Filter bar layout is clean
- Remaining filters are properly spaced
- Mobile responsive layout works correctly

## Implementation Notes

### Files to Modify

1. **resources/js/Pages/Tasks.vue**
   - Remove `projectFilter` ref
   - Remove Project filter from template
   - Update `filteredTasks` computed property
   - Update `clearFilters` function
   - Update clear button condition

2. **app/Http/Controllers/TaskController.php**
   - Simplify `index()` method to not query projects
   - Return empty projects array

### Backward Compatibility

- Keep `projects` prop in component interface (as empty array) to avoid breaking changes
- If future features need project list, it's easy to re-populate

### Future Enhancements

1. **Global Search**: Add a search input that filters across all columns including project name
2. **Column Sorting**: Make Project column sortable to group tasks by project
3. **Project Grouping**: Add option to group table rows by project with collapsible sections
4. **Saved Filter Presets**: Allow users to save common filter combinations

### Migration Path

This is a non-breaking change:
- No database migrations needed
- No API changes needed
- Frontend-only modification
- Can be deployed independently
