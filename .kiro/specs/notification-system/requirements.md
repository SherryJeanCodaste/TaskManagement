# Notification System Requirements

## Overview
A lightweight notification system that provides awareness alerts for task-related events. Notifications are informational tools, not task managers - they inform users about changes without replacing the actual task management interface.

## User Stories

### US-1: View Notifications
**As a** user (developer, customer, admin)  
**I want to** see a notification bell icon in the header with a badge count  
**So that** I know how many unread notifications I have

**Acceptance Criteria:**
- 1.1 Bell icon is visible in the header for all authenticated users
- 1.2 Badge shows count of unread notifications (e.g., 🔔 5)
- 1.3 Badge displays "99+" if count exceeds 99
- 1.4 Badge is hidden when count is 0
- 1.5 Badge color is red (#EF4444) for visibility

### US-2: Open Notification Panel
**As a** user  
**I want to** click the bell icon to open a dropdown panel  
**So that** I can view my notifications

**Acceptance Criteria:**
- 2.1 Clicking bell icon toggles dropdown panel
- 2.2 Panel appears below the bell icon, aligned to the right
- 2.3 Panel has white background with shadow and border
- 2.4 Panel has max height with scroll for many notifications
- 2.5 Clicking outside panel closes it
- 2.6 Panel shows "No notifications" message when empty

### US-3: View Notification List
**As a** user  
**I want to** see a list of my notifications in the panel  
**So that** I can stay informed about task events

**Acceptance Criteria:**
- 3.1 Each notification shows:
  - Type icon (colored dot or emoji)
  - Short title (bold if unread)
  - Short description (1 line max)
  - Relative timestamp (e.g., "2 minutes ago", "1 hour ago", "Yesterday")
- 3.2 Unread notifications have:
  - Bold text
  - Slight background highlight
- 3.3 Read notifications have:
  - Normal text weight
  - No background highlight
- 3.4 Notifications are sorted by newest first
- 3.5 Each notification is clickable

### US-4: Mark All as Read
**As a** user  
**I want to** mark all notifications as read  
**So that** I can clear the unread badge without deleting notifications

**Acceptance Criteria:**
- 4.1 "Mark all as read" button is visible in panel header
- 4.2 Clicking button marks all notifications as read
- 4.3 Badge count resets to 0
- 4.4 All notification highlights are removed
- 4.5 Notifications remain in the list
- 4.6 Action is immediate (no confirmation needed)

### US-5: Clear All Notifications
**As a** user  
**I want to** clear all notifications  
**So that** I can remove old notification history

**Acceptance Criteria:**
- 5.1 "Clear all" button is visible in panel header
- 5.2 Clicking button deletes all notifications
- 5.3 Panel shows "No notifications" message
- 5.4 Badge count resets to 0
- 5.5 Notifications are permanently removed
- 5.6 Tasks are NOT affected (only notifications are deleted)
- 5.7 Action is immediate (no confirmation needed)

### US-6: Click Notification
**As a** user  
**I want to** click a notification  
**So that** I can navigate to the related task and mark it as read

**Acceptance Criteria:**
- 6.1 Clicking notification marks it as read
- 6.2 Badge count decrements by 1
- 6.3 User is redirected to the related task detail page
- 6.4 Notification panel closes after click
- 6.5 Notification highlight is removed

### US-7: Receive New Notifications
**As a** user  
**I want to** automatically receive notifications when events occur  
**So that** I stay informed without manual checking

**Acceptance Criteria:**
- 7.1 Notification is created when:
  - New task is assigned (for developers)
  - Task status changes (for customers and developers)
  - Customer adds comment (for developers)
  - Task is approved (for developers)
  - Task is rejected (for developers)
- 7.2 Badge count increases automatically
- 7.3 New notification appears at top of list
- 7.4 New notification is marked as unread by default

## Notification Types

### Type 1: New Task Assigned
- **Icon:** 🆕 or blue dot
- **Title:** "New Task Assigned"
- **Description:** "[Task Title]"
- **Recipient:** Developer
- **Trigger:** When admin/customer assigns task to developer

### Type 2: Task Status Updated
- **Icon:** 🔄 or yellow dot
- **Title:** "Task Status Updated"
- **Description:** "[Task Title] is now [Status]"
- **Recipient:** Customer and Developer
- **Trigger:** When task status changes

### Type 3: Customer Commented
- **Icon:** 💬 or purple dot
- **Title:** "Customer Commented"
- **Description:** "[Comment preview]"
- **Recipient:** Developer
- **Trigger:** When customer adds comment to task

### Type 4: Task Approved
- **Icon:** ✅ or green dot
- **Title:** "Task Approved"
- **Description:** "[Task Title]"
- **Recipient:** Developer
- **Trigger:** When customer/admin approves completed task

### Type 5: Task Rejected
- **Icon:** ❌ or red dot
- **Title:** "Task Rejected"
- **Description:** "[Task Title]"
- **Recipient:** Developer
- **Trigger:** When customer/admin rejects task

## Design Requirements

### Visual Design
- Clean white background
- Soft dividers between notifications
- Minimal color usage (only for type indicators)
- Max height: 384px (24rem) with scroll
- Width: 320px (20rem)
- Smooth transitions and hover effects

### Color Coding
- New Task: Blue (#3B82F6)
- Status Update: Yellow (#F59E0B)
- Comment: Purple (#8B5CF6)
- Approved: Green (#10B981)
- Rejected: Red (#EF4444)

### Typography
- Title: 14px, medium weight (bold if unread)
- Description: 12px, normal weight
- Timestamp: 12px, gray color (#9CA3AF)
- Header: 14px, semibold

## Technical Requirements

### Database Schema
```
notifications table:
- id (primary key)
- user_id (foreign key to users)
- type (enum: new_task, status_update, comment, approved, rejected)
- title (string)
- message (text)
- task_id (foreign key to tasks, nullable)
- is_read (boolean, default: false)
- created_at (timestamp)
- updated_at (timestamp)
```

### API Endpoints
- `GET /api/notifications` - Fetch user notifications
- `POST /api/notifications/{id}/read` - Mark single notification as read
- `POST /api/notifications/mark-all-read` - Mark all as read
- `DELETE /api/notifications/clear-all` - Delete all notifications
- `GET /api/notifications/unread-count` - Get unread count

### Frontend State
- Notification count (reactive)
- Notification list (reactive)
- Panel open/closed state
- Loading states for actions

## Business Rules

### BR-1: Notification Lifecycle
- Notifications are created automatically by system events
- Notifications start as unread (is_read = false)
- Clicking notification marks it as read (is_read = true)
- "Mark all as read" updates all to is_read = true
- "Clear all" deletes all notification records
- Notifications do NOT affect task status or data

### BR-2: Notification Retention
- Notifications are kept indefinitely by default
- Optional: Auto-delete notifications older than 30 days
- Clearing notifications does not affect tasks
- Users can only see their own notifications

### BR-3: Badge Count Logic
- Badge shows count of unread notifications (is_read = false)
- Count updates in real-time when:
  - New notification is created
  - Notification is marked as read
  - Notification is deleted
  - All are marked as read
  - All are cleared

### BR-4: Notification Access
- Users can only access their own notifications
- Admins do NOT see all notifications (only their own)
- Notifications are user-specific, not role-specific

## Non-Functional Requirements

### Performance
- Notification panel loads in < 500ms
- Badge count updates in < 200ms
- Smooth animations (300ms transitions)
- Efficient database queries (indexed user_id and is_read)

### Usability
- One-click access to notifications
- Clear visual distinction between read/unread
- Intuitive actions (no confirmation dialogs)
- Mobile-responsive design

### Scalability
- Support up to 1000 notifications per user
- Pagination for large notification lists
- Efficient real-time updates (optional: WebSockets)

## Out of Scope (Not Included)
- Email notifications
- Push notifications
- Notification preferences/settings
- Notification categories/filtering
- Notification search
- Notification archiving
- Real-time updates (WebSockets) - Phase 2
- Notification sounds
- Desktop notifications

## Success Metrics
- Users can view notifications within 1 click
- Badge count accurately reflects unread notifications
- Notification actions complete in < 1 second
- Zero data loss when clearing notifications
- Tasks remain unaffected by notification operations

## Dependencies
- Existing task management system
- User authentication system
- Task status workflow
- Comment system (if implemented)

## Future Enhancements (Phase 2)
- Real-time notifications via WebSockets
- Notification preferences (enable/disable types)
- Email digest of notifications
- Notification filtering by type
- Mark individual notification as read without clicking
- Undo clear action (30-second window)
- Notification grouping (e.g., "3 new tasks assigned")
