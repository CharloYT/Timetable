# BUSINESS INFORMATION REQUIREMENTS DOCUMENT
## Timetable Management System

---

## DOCUMENT CONTROL

| Item | Details |
|------|---------|
| Project Name | Timetable Scheduling Management System |
| Document Version | 1.0 |
| Date | November 29, 2025 |
| Status | Draft |
| Author | System Analyst |
| Organization | Academic Institution |

---

## 1. EXECUTIVE SUMMARY

### 1.1 Project Overview
The Timetable Management System is a web-based application designed to automate and streamline the process of creating, managing, and viewing academic timetables for educational institutions. The system addresses the complex challenge of scheduling courses, allocating lecturers, managing rooms, and preventing scheduling conflicts.

### 1.2 Key Objectives
- Automate timetable creation and management processes
- Eliminate scheduling conflicts for rooms and lecturers
- Provide real-time visibility of schedules to all stakeholders
- Reduce administrative overhead in timetable management
- Ensure optimal utilization of institutional resources

### 1.3 Expected Benefits
- **Time Savings**: Reduce timetable creation time by 70%
- **Error Reduction**: Eliminate double-booking and scheduling conflicts
- **Resource Optimization**: Maximize room and lecturer utilization
- **Accessibility**: 24/7 access to timetable information
- **Transparency**: Real-time updates visible to all stakeholders

### 1.4 Project Scope
**In Scope:**
- Department management
- Course registration and management
- Lecturer profile management
- Room resource management
- Timeslot definition and allocation
- Schedule creation and conflict prevention
- Timetable viewing and reporting
- User authentication and authorization

**Out of Scope:**
- Student enrollment management
- Grade management
- Attendance tracking
- Financial management
- Mobile application (Phase 1)

---

## 2. BUSINESS CONTEXT

### 2.1 Background
Educational institutions face significant challenges in creating and managing academic timetables. The manual process is time-consuming, error-prone, and often results in scheduling conflicts. This system addresses these pain points by providing an automated, centralized solution.

### 2.2 Current Business Processes
**Current State:**
- Manual timetable creation using spreadsheets
- Multiple iterations due to conflicts
- Email-based communication for changes
- Printed timetables distributed weekly
- Limited visibility of resource availability

**Pain Points:**
- Frequent double-booking of rooms and lecturers
- Time-consuming manual conflict resolution
- Difficulty tracking lecturer workload
- Poor visibility of room utilization
- Last-minute changes causing confusion

### 2.3 Stakeholders

| Stakeholder | Role | Interest |
|-------------|------|----------|
| Academic Administrators | Primary Users | Create and manage timetables |
| Department Heads | Approvers | Review departmental schedules |
| Lecturers | Information Consumers | View teaching schedules |
| Students | Information Consumers | View class schedules |
| IT Department | System Maintainers | Ensure system availability |
| Management | Decision Makers | Resource utilization reports |

### 2.4 Business Drivers
- Increasing student enrollment
- Limited physical resources
- Need for operational efficiency
- Digital transformation initiatives
- Quality assurance requirements

---

## 3. FUNCTIONAL REQUIREMENTS

### 3.1 Department Management

#### FR-01: Create Department
**Priority**: High  
**Description**: System shall allow authorized users to create new academic departments.

**Requirements:**
- Capture department name
- Assign unique department code
- Validate uniqueness of department code
- Store department information in database

**Business Rules:**
- Department code must be unique
- Department code format: 3-10 alphanumeric characters
- Department name is mandatory

#### FR-02: View/Edit/Delete Department
**Priority**: High  
**Description**: System shall allow management of existing departments.

**Requirements:**
- List all departments
- Edit department details
- Delete departments (with validation)
- Prevent deletion if department has associated courses/lecturers

---

### 3.2 Course Management

#### FR-03: Course Registration
**Priority**: High  
**Description**: System shall allow registration of academic courses.

**Requirements:**
- Capture course code (unique identifier)
- Capture course title
- Assign credit hours/units
- Associate course with department
- Validate course code uniqueness

**Business Rules:**
- Course code must be unique
- Course code format: 6-20 alphanumeric characters
- Credits must be positive integer (1-6)
- Course must be associated with a department

#### FR-04: Course Management
**Priority**: High  
**Description**: System shall provide full CRUD operations for courses.

**Requirements:**
- View all courses with department information
- Edit course details
- Delete courses
- Filter courses by department
- Search courses by code or title

---

### 3.3 Lecturer Management

#### FR-05: Lecturer Registration
**Priority**: High  
**Description**: System shall maintain lecturer profiles.

**Requirements:**
- Capture first name and last name
- Capture unique email address
- Associate lecturer with department
- Store contact information

**Business Rules:**
- Email must be unique and valid format
- Lecturer must be assigned to a department
- Names are mandatory fields

#### FR-06: Lecturer Loading Analysis
**Priority**: Medium  
**Description**: System shall track and display lecturer workload.

**Requirements:**
- Calculate total courses assigned per lecturer
- Display weekly teaching hours
- Identify overloaded lecturers
- Generate workload reports

**Business Rules:**
- Maximum teaching load: 18 hours per week (configurable)
- Minimum rest period between classes: 30 minutes

---

### 3.4 Room Management

#### FR-07: Room Registration
**Priority**: High  
**Description**: System shall manage physical teaching spaces.

**Requirements:**
- Capture unique room name/number
- Record room capacity
- Specify room type (Lecture Hall, Laboratory, Seminar Room)
- Enable room availability tracking

**Business Rules:**
- Room name must be unique
- Capacity must be positive integer
- Room type must be from predefined list

#### FR-08: Room Utilization
**Priority**: Medium  
**Description**: System shall track room usage.

**Requirements:**
- Calculate room occupancy rate
- Identify underutilized rooms
- Generate room utilization reports

---

### 3.5 Timeslot Management

#### FR-09: Timeslot Definition
**Priority**: High  
**Description**: System shall define available time periods for classes.

**Requirements:**
- Specify day of week
- Set start time
- Set end time
- Prevent overlapping timeslots

**Business Rules:**
- Unique combination of day, start time, end time
- End time must be after start time
- Standard timeslots: 1, 2, or 3 hours duration
- Operating days: Monday to Friday (Saturday optional)
- Operating hours: 8:00 AM to 6:00 PM

---

### 3.6 Schedule Allocation

#### FR-10: Create Schedule
**Priority**: Critical  
**Description**: System shall allow creation of class schedules.

**Requirements:**
- Select course
- Assign lecturer
- Allocate room
- Assign timeslot
- Validate all constraints before saving

**Business Rules:**
- **No Room Conflicts**: A room cannot be booked for two different courses at the same timeslot
- **No Lecturer Conflicts**: A lecturer cannot teach two courses at the same timeslot
- Room capacity must accommodate expected class size
- Lecturer must be qualified for the course (same department preferred)

#### FR-11: Conflict Detection
**Priority**: Critical  
**Description**: System shall automatically detect and prevent scheduling conflicts.

**Requirements:**
- Real-time conflict checking during schedule creation
- Display clear error messages for conflicts
- Suggest alternative timeslots/rooms
- Prevent saving conflicting schedules

#### FR-12: Schedule Modification
**Priority**: High  
**Description**: System shall allow editing and deletion of schedules.

**Requirements:**
- Edit existing allocations
- Delete schedule entries
- Maintain audit trail of changes
- Re-validate constraints after modifications

---

### 3.7 Timetable Viewing

#### FR-13: View Timetable
**Priority**: High  
**Description**: System shall display organized timetable views.

**Requirements:**
- Filter by department
- Filter by lecturer
- Filter by room
- Display in weekly grid format
- Show course code, title, lecturer, room, time
- Export/print functionality

**Business Rules:**
- Default view: Current week
- Grid format: Days (columns) x Timeslots (rows)

---

### 3.8 User Authentication & Authorization

#### FR-14: User Registration
**Priority**: High  
**Description**: System shall provide secure user registration.

**Requirements:**
- Capture username (unique)
- Capture email (unique, validated)
- Secure password storage (hashed)
- Assign user role

**Business Rules:**
- Username: 4-50 characters
- Password: Minimum 8 characters
- Email must be valid institutional email
- Default role: Viewer

#### FR-15: User Login
**Priority**: High  
**Description**: System shall authenticate users securely.

**Requirements:**
- Login with username/email and password
- Session management
- Secure password verification
- Login attempt tracking

**Business Rules:**
- Lock account after 5 failed attempts
- Session timeout: 30 minutes of inactivity

#### FR-16: Role-Based Access Control
**Priority**: High  
**Description**: System shall enforce role-based permissions.

**User Roles:**

| Role | Permissions |
|------|-------------|
| Administrator | Full access to all modules |
| Scheduler | Create/edit schedules, manage resources |
| Department Head | View all, edit own department |
| Lecturer | View own schedule |
| Student/Viewer | View published timetables only |

---

## 4. NON-FUNCTIONAL REQUIREMENTS

### 4.1 Performance Requirements

**NFR-01: Response Time**
- Page load time: < 3 seconds on standard connection
- Schedule conflict check: < 1 second
- Timetable rendering: < 2 seconds

**NFR-02: Capacity**
- Support up to 100 concurrent users
- Handle up to 10,000 schedule entries
- Support 500 courses, 200 lecturers, 100 rooms

**NFR-03: Database Performance**
- Query response time: < 500ms for complex queries
- Transaction processing: < 1 second

### 4.2 Security Requirements

**NFR-04: Data Security**
- All passwords must be hashed using bcrypt or similar
- SQL injection prevention through prepared statements
- CSRF protection on all forms
- Secure session management

**NFR-05: Access Control**
- Role-based access control enforced at application layer
- User authentication required for all operations except timetable viewing
- Session timeout after 30 minutes inactivity

**NFR-06: Data Privacy**
- Comply with data protection regulations
- Protect sensitive user information
- Secure storage of personal data

### 4.3 Reliability Requirements

**NFR-07: Availability**
- System uptime: 99% during business hours (8AM - 6PM)
- Planned maintenance during off-peak hours
- Automated database backups daily

**NFR-08: Data Integrity**
- Foreign key constraints enforced
- Transaction rollback on errors
- Data validation at database and application layers

### 4.4 Usability Requirements

**NFR-09: User Interface**
- Intuitive navigation with clear menu structure
- Responsive design for desktop and tablet
- Consistent design language (glassmorphism aesthetic)
- Help text and tooltips for key functions

**NFR-10: Accessibility**
- WCAG 2.1 Level A compliance
- Keyboard navigation support
- Clear error messages
- Visual feedback for user actions

### 4.5 Compatibility Requirements

**NFR-11: Browser Support**
- Google Chrome (latest 2 versions)
- Mozilla Firefox (latest 2 versions)
- Microsoft Edge (latest 2 versions)
- Safari (latest 2 versions)

**NFR-12: Technical Stack**
- Frontend: HTML5, CSS3, JavaScript
- Backend: PHP 7.4+
- Database: MySQL 5.7+ or MariaDB 10.3+
- Web Server: Apache 2.4+

### 4.6 Scalability Requirements

**NFR-13: Growth Support**
- Architecture should support 50% growth in users/data without redesign
- Database design should support multiple institutions (future)
- Modular code structure for feature additions

---

## 5. BUSINESS RULES

### 5.1 Scheduling Rules

**BR-01: Conflict Prevention**
- A room can only be allocated to one course per timeslot
- A lecturer can only teach one course per timeslot
- No back-to-back classes in different buildings (future consideration)

**BR-02: Workload Limits**
- Maximum lecturer teaching hours: 18 per week
- Minimum gap between classes: 30 minutes
- Maximum consecutive teaching hours: 4 hours

**BR-03: Resource Matching**
- Laboratory courses must be assigned to Laboratory rooms
- Room capacity must meet minimum class size requirements
- Lecturer assignment should prioritize department match

### 5.2 Data Validation Rules

**BR-04: Code Formats**
- Department Code: 3-10 alphanumeric characters, uppercase
- Course Code: 6-20 alphanumeric characters
- Room Name: 2-50 characters

**BR-05: Mandatory Fields**
- All entity names are mandatory
- Email addresses must be unique and valid
- Credit hours must be 1-6 range

### 5.3 Operational Rules

**BR-06: Academic Calendar**
- Timeslots: Monday-Friday (Saturday optional)
- Operating hours: 8:00 AM - 6:00 PM
- Break periods: 12:00 PM - 1:00 PM (recommended)

**BR-07: Data Retention**
- Active schedules: Retained indefinitely
- Historical data: Archived annually
- User accounts: Deactivated after 1 year of inactivity

---

## 6. DATA REQUIREMENTS

### 6.1 Data Entities

The system manages the following core entities:

#### Departments
- Department ID (Primary Key)
- Department Name
- Department Code (Unique)

#### Courses
- Course ID (Primary Key)
- Course Code (Unique)
- Course Title
- Credits
- Department ID (Foreign Key)

#### Lecturers
- Lecturer ID (Primary Key)
- First Name
- Last Name
- Email (Unique)
- Department ID (Foreign Key)

#### Rooms
- Room ID (Primary Key)
- Room Name (Unique)
- Capacity
- Room Type

#### Timeslots
- Timeslot ID (Primary Key)
- Day of Week
- Start Time
- End Time
- Unique combination constraint

#### Schedule Allocations
- Allocation ID (Primary Key)
- Course ID (Foreign Key)
- Lecturer ID (Foreign Key)
- Room ID (Foreign Key)
- Timeslot ID (Foreign Key)
- Unique constraints: (Room, Timeslot), (Lecturer, Timeslot)

#### Users
- User ID (Primary Key)
- Username (Unique)
- Email (Unique)
- Password Hash
- Role
- Created Date

### 6.2 Data Relationships

```
Departments (1) ←→ (Many) Courses
Departments (1) ←→ (Many) Lecturers
Courses (1) ←→ (Many) Schedule Allocations
Lecturers (1) ←→ (Many) Schedule Allocations
Rooms (1) ←→ (Many) Schedule Allocations
Timeslots (1) ←→ (Many) Schedule Allocations
```

### 6.3 Data Quality Standards

- **Completeness**: All mandatory fields must be populated
- **Accuracy**: Data validation on input and update
- **Consistency**: Referential integrity maintained via foreign keys
- **Timeliness**: Real-time updates reflected immediately
- **Uniqueness**: Unique constraints enforced on key identifiers

### 6.4 Data Retention & Archival

- **Active Data**: Current academic year schedules kept in primary database
- **Historical Data**: Previous years archived annually
- **Backup Policy**: Daily automated backups, retained for 30 days
- **Recovery**: Point-in-time recovery capability for last 7 days

---

## 7. USER REQUIREMENTS

### 7.1 User Personas

#### Persona 1: Academic Administrator (Sarah)
- **Role**: Timetable Coordinator
- **Goals**: Create conflict-free schedules efficiently
- **Pain Points**: Manual conflict detection, frequent changes
- **Technical Proficiency**: Moderate
- **Key Features**: Schedule creation, conflict detection, resource management

#### Persona 2: Department Head (Dr. Johnson)
- **Role**: Academic Manager
- **Goals**: Ensure optimal resource utilization for department
- **Pain Points**: Limited visibility, unbalanced lecturer workload
- **Technical Proficiency**: Basic
- **Key Features**: Workload reports, department schedule view

#### Persona 3: Lecturer (Prof. Adams)
- **Role**: Faculty Member
- **Goals**: View teaching schedule, plan activities
- **Pain Points**: Schedule changes not communicated promptly
- **Technical Proficiency**: Basic
- **Key Features**: Personal schedule view, schedule notifications

### 7.2 Use Cases

#### UC-01: Create Weekly Timetable
**Actor**: Academic Administrator  
**Precondition**: All courses, lecturers, and rooms are registered  
**Main Flow**:
1. Administrator logs into system
2. Navigates to Schedule Allocation
3. Selects course from dropdown
4. Assigns lecturer to course
5. Selects available room
6. Chooses timeslot
7. System validates for conflicts
8. If no conflicts, saves allocation
9. Repeats for all courses

**Postcondition**: Complete timetable created without conflicts

#### UC-02: Check Lecturer Workload
**Actor**: Department Head  
**Precondition**: Schedules are created  
**Main Flow**:
1. Department Head logs in
2. Navigates to Lecturer Load page
3. Views list of lecturers with assigned hours
4. Identifies overloaded/underloaded lecturers
5. Requests adjustments if needed

**Postcondition**: Workload visibility achieved

#### UC-03: View Personal Schedule
**Actor**: Lecturer  
**Precondition**: User has lecturer account  
**Main Flow**:
1. Lecturer logs in
2. Navigates to View Timetable
3. Filters by own name
4. Views weekly schedule
5. Prints or exports schedule

**Postcondition**: Lecturer aware of teaching commitments

### 7.3 User Interface Expectations

- **Design Aesthetic**: Modern glassmorphism design with vibrant colors
- **Navigation**: Clear menu structure, max 3 clicks to any feature
- **Forms**: Inline validation, clear error messages
- **Tables**: Sortable columns, pagination for large datasets
- **Feedback**: Visual confirmation for all actions
- **Responsiveness**: Works on desktops (1920x1080) and tablets (768px+)

### 7.4 Training & Support Needs

- **Administrator Training**: 2-day comprehensive training on all features
- **User Training**: 1-hour introduction to viewing and basic features
- **Documentation**: User manual, FAQ, video tutorials
- **Support**: Help desk email, response within 24 hours

---

## 8. INTEGRATION REQUIREMENTS

### 8.1 Current Integrations

**INT-01: Database Integration**
- MySQL/MariaDB database backend
- Connection via PHP Data Objects (PDO) or MySQLi
- Prepared statements for security

### 8.2 Future Integration Opportunities

**INT-02: Email System (Phase 2)**
- SMTP integration for notifications
- Schedule change alerts
- Password reset functionality

**INT-03: Calendar Export (Phase 2)**
- iCal format export
- Integration with Google Calendar, Outlook

**INT-04: Student Information System (Phase 3)**
- Import student enrollment data
- Link class sizes to room allocation

**INT-05: Learning Management System (Phase 3)**
- Sync schedules with LMS
- Automatic course creation

---

## 9. SUCCESS CRITERIA

### 9.1 Measurable Objectives

| Objective | Metric | Target | Timeline |
|-----------|--------|--------|----------|
| Reduce schedule creation time | Hours per semester | 70% reduction | 6 months |
| Eliminate conflicts | Number of conflicts per semester | Zero | 3 months |
| Improve room utilization | % occupancy | 75%+ | 12 months |
| User adoption | Active users | 90% of staff | 6 months |
| System availability | Uptime % | 99%+ | Ongoing |

### 9.2 Key Performance Indicators (KPIs)

**Operational KPIs:**
- Average time to create timetable: Target < 4 hours
- Number of schedule revisions: Target < 3 per semester
- Scheduling conflicts detected: Target 100% before saving

**User Experience KPIs:**
- User satisfaction score: Target > 4.0/5.0
- Average page load time: Target < 3 seconds
- User training completion rate: Target 95%

**Technical KPIs:**
- System uptime: Target 99%
- Data backup success rate: Target 100%
- Security incidents: Target 0

### 9.3 Acceptance Criteria

**Phase 1 (Core System)**
- ✅ All CRUD operations functional for all entities
- ✅ Conflict detection prevents double-booking
- ✅ Role-based access control implemented
- ✅ Timetable viewing with filtering options
- ✅ User authentication and session management

**Phase 2 (Enhanced Features)**
- Workload analysis and reporting
- Email notifications
- Export functionality (PDF, Excel)
- Audit trail for changes

**User Acceptance Testing:**
- 95% of test cases passed
- No critical bugs
- Performance targets met
- User feedback > 4/5

---

## 10. CONSTRAINTS & ASSUMPTIONS

### 10.1 Budget Constraints

- Development budget: Limited to open-source technologies
- Infrastructure: Utilize existing institutional servers
- Training: Self-service materials and in-house trainers

### 10.2 Timeline Constraints

- **Phase 1 Delivery**: 3 months from project start
- **Testing Period**: 1 month
- **Deployment**: Before next academic semester
- **Full Adoption**: Within 6 months

### 10.3 Technical Constraints

- Must use existing WAMP/LAMP stack
- PHP 7.4+ required
- MySQL 5.7+ or MariaDB 10.3+
- No additional software licenses permitted
- Must work on existing network infrastructure

### 10.4 Organizational Constraints

- Limited IT support staff
- Users have varying technical proficiency
- Change management required for adoption
- Integration with existing systems limited

### 10.5 Assumptions

- Institution has stable internet connectivity
- All lecturers have email addresses
- Room information is accurate and current
- Academic calendar is defined in advance
- System will be used primarily during business hours
- Administrative staff will maintain data accuracy
- Users have access to modern web browsers

---

## 11. RISKS & MITIGATION

### 11.1 Technical Risks

| Risk | Impact | Probability | Mitigation Strategy |
|------|--------|-------------|---------------------|
| Database performance degradation | High | Medium | Optimize queries, add indexes, implement caching |
| Security vulnerabilities | Critical | Low | Regular security audits, penetration testing, updates |
| Data loss | Critical | Low | Automated daily backups, disaster recovery plan |
| Browser compatibility issues | Medium | Medium | Cross-browser testing, graceful degradation |
| Server downtime | High | Low | Redundant servers, monitoring, SLA with hosting |

### 11.2 Operational Risks

| Risk | Impact | Probability | Mitigation Strategy |
|------|--------|-------------|---------------------|
| User resistance to change | High | Medium | Change management, training, stakeholder engagement |
| Data entry errors | Medium | High | Validation rules, data verification workflows |
| Incomplete data migration | High | Medium | Thorough data cleansing, validation before migration |
| Insufficient training | Medium | Medium | Comprehensive training program, documentation |

### 11.3 Project Risks

| Risk | Impact | Probability | Mitigation Strategy |
|------|--------|-------------|---------------------|
| Scope creep | Medium | High | Clear requirements, change control process |
| Resource unavailability | High | Low | Cross-training, documentation, backup resources |
| Timeline delays | Medium | Medium | Agile methodology, prioritized features, MVP approach |
| Budget overrun | Low | Low | Open-source technologies, in-house development |

### 11.4 Mitigation Monitoring

- Weekly risk review meetings
- Risk register maintained and updated
- Contingency plans documented
- Early warning indicators defined

---

## 12. DEPENDENCIES

### 12.1 Internal Dependencies

- Availability of IT infrastructure
- Accuracy of current room and course data
- Stakeholder availability for requirements validation
- Administrative support for data migration
- User availability for training

### 12.2 External Dependencies

- Third-party library availability (if used)
- Browser vendor updates
- PHP/MySQL community support
- Internet service provider reliability

### 12.3 Critical Path Items

1. Database schema finalization
2. User authentication implementation
3. Conflict detection algorithm
4. User acceptance testing
5. Data migration from legacy system

---

## 13. GLOSSARY

| Term | Definition |
|------|------------|
| Allocation | Assignment of a course to a specific lecturer, room, and timeslot |
| Conflict | Situation where a resource (room/lecturer) is double-booked |
| Credit Hours | Academic units representing course workload |
| CRUD | Create, Read, Update, Delete operations |
| Department | Academic division within the institution |
| Lecturer Load | Total teaching hours assigned to a lecturer |
| Timeslot | Specific time period defined by day, start time, and end time |
| Timetable | Complete schedule showing all course allocations |
| Role-Based Access Control (RBAC) | Security model restricting access based on user roles |
| Session | Period of user interaction with the system after authentication |

---

## 14. APPENDICES

### Appendix A: Database Schema

Refer to `create_db.sql` for complete database structure including:
- All tables and columns
- Primary and foreign key relationships
- Unique constraints
- Referential integrity rules

### Appendix B: User Interface Mockups

*To be added during design phase*

### Appendix C: API Documentation

*To be developed for future integrations*

### Appendix D: Test Cases

*To be developed during testing phase*

---

## 15. APPROVAL

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Project Sponsor | _______________ | _______________ | _______________ |
| Business Owner | _______________ | _______________ | _______________ |
| IT Manager | _______________ | _______________ | _______________ |
| End User Representative | _______________ | _______________ | _______________ |

---

## 16. REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Nov 29, 2025 | System Analyst | Initial document creation |
|  |  |  |  |
|  |  |  |  |

---

**END OF DOCUMENT**
