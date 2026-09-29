# AGENTS.md

# SKILLHUB — AI CODING AGENT INSTRUCTIONS

This file contains mandatory instructions for every AI coding agent working on this project.

These rules apply especially when the user asks to:

* Fix a bug
* Fix an error
* Debug an issue
* Investigate a problem
* Fix broken functionality
* Resolve an exception
* Fix unexpected behavior
* Fix frontend or backend issues
* Fix database issues
* Fix API issues
* Fix payment, wallet, order, chat, notification, or authentication issues

---

# 1. CORE PRINCIPLE

When the user reports a bug, DO NOT immediately edit code.

DO NOT guess the cause.

DO NOT stop investigation after finding the first possible cause.

DO NOT apply a quick patch and assume the problem is solved.

Investigate the bug seriously like a professional software engineer.

The goal is not merely to remove the visible error.

The goal is to find and fix the actual root cause while identifying other related conditions that could cause the same bug, regression, duplicate bug, inconsistent data, or future failure.

A bug fix should ideally be completed correctly in one serious investigation, not require multiple user prompts because the AI only investigated one possible cause at a time.

---

# 2. MANDATORY FIRST STEP: UNDERSTAND THE USER'S BUG REPORT

Before changing any code:

1. Read the user's bug report carefully.
2. Identify the exact symptom.
3. Identify what the user expected to happen.
4. Identify what actually happened.
5. Identify any error message, HTTP status, exception, log, stack trace, page, route, controller, component, or feature mentioned by the user.
6. Determine all likely areas of the project related to the bug.

Do not narrow the investigation too early.

If the user reports one symptom, remember that the symptom may have multiple possible causes.

Example:

> "Withdraw berhasil tetapi saldo tidak berkurang."

DO NOT only inspect:

```text
WalletController::withdrawStore()
```

Also investigate related code such as:

* Wallet model
* Balance calculation
* Wallet transactions
* Database transactions
* Withdrawal creation
* Withdrawal status changes
* Admin approval/rejection
* Payout processing
* Automatic payout services
* Job/queue processing
* Rollback logic
* Duplicate requests
* Existing observers/listeners/events
* Other methods that increase or decrease the same balance

The visible symptom is NOT automatically the root cause.

---

# 3. READ EXISTING PROJECT DOCUMENTATION FIRST

Before investigating or fixing a bug, check relevant project documentation.

If these files exist, read the relevant parts:

* `ERROR.md`
* `README.md`
* `ARCHITECTURE.md`
* `BUSINESS_LOGIC.md`
* `DATABASE.md`
* `TESTING.md`
* Any other documentation related to the reported feature

## ERROR.md RULE

If `ERROR.md` exists:

1. Search for the current error or a similar historical bug.
2. Check whether the same symptom happened before.
3. Check whether a previous fix could have introduced a regression.
4. Check whether there are known related bugs.
5. Use previous bug information as investigation context.

However:

DO NOT blindly apply an old solution.

Always verify the current code because the project may have changed.

---

# 4. READ ALL RELEVANT FILES BEFORE EDITING

When investigating a bug, DO NOT inspect only the first file that appears related.

Read all files reasonably connected to the execution flow.

Depending on the bug, this may include:

## Backend

* Routes
* Controllers
* Services
* Models
* Requests
* Middleware
* Policies
* Events
* Listeners
* Jobs
* Commands
* Notifications
* Observers
* Traits
* Database migrations
* Seeders if relevant
* Configuration files

## Frontend

* Blade views
* React components
* JavaScript entry points
* Mounting logic
* API/fetch/Axios calls
* Form submission logic
* CSRF token handling
* Props and state handling
* Vite configuration if relevant

## Database

* Table structure
* Foreign keys
* Nullable columns
* Default values
* Unique constraints
* Enum/status values
* Transactions
* Locks
* Related records

## External/Async Systems

When relevant, also inspect:

* Queue jobs
* Scheduler/cron commands
* Webhooks
* Payment callbacks
* WebSocket events
* Reverb/broadcasting
* Third-party service integration

Do not change code until the relevant execution flow is understood.

---

# 5. INVESTIGATE THE FULL EXECUTION FLOW

Trace the bug from start to finish.

Use this general pattern:

```text
USER ACTION
    ↓
ROUTE
    ↓
MIDDLEWARE
    ↓
CONTROLLER / ENTRY POINT
    ↓
REQUEST VALIDATION
    ↓
SERVICE / BUSINESS LOGIC
    ↓
MODEL / DATABASE
    ↓
EVENT / JOB / LISTENER
    ↓
EXTERNAL SERVICE IF ANY
    ↓
DATABASE UPDATE
    ↓
RESPONSE
    ↓
FRONTEND STATE / UI
```

Not every feature uses every step, but investigate the complete relevant flow.

Do not assume the first broken-looking line is the only cause.

---

# 6. MULTI-CAUSE INVESTIGATION — MANDATORY

THIS RULE IS CRITICAL.

When you find one possible cause, DO NOT STOP.

You MUST ask:

> "Could another part of the system independently cause the same symptom?"

Then continue searching.

For every significant bug, investigate at least these categories where relevant:

### A. Direct Cause

Is there an obvious incorrect, missing, or unreachable line of code?

### B. Alternative Cause

Could another file, method, branch, condition, or process produce the same error?

### C. Duplicate Logic

Is the same business process implemented in multiple places?

Check for:

* Duplicate routes
* Duplicate controllers/actions
* Duplicate service methods
* Duplicate event listeners
* Duplicate frontend requests
* Multiple status update paths
* Multiple balance update paths

### D. Missing Execution Path

Is there another valid flow where the required logic is missing?

Example:

```text
Manual payout → balance decreases
Automatic payout → balance decreases
Admin payout → balance DOES NOT decrease
```

Even if the reported bug occurs in only one flow, investigate similar flows for inconsistent logic.

### E. Race Condition / Duplicate Request

Could the bug occur because:

* The same request is submitted twice?
* The user clicks twice?
* A webhook arrives twice?
* A queue job runs twice?
* A retry executes the operation again?
* Two requests update the same record simultaneously?

### F. Transaction and Data Consistency

Check whether operations that must succeed together are protected properly.

Ask:

* Could one database operation succeed while another fails?
* Could partial data remain?
* Could rollback be missing?
* Could a balance/status/transaction record become inconsistent?

### G. Status and Conditional Logic

Check all relevant statuses and branches.

Do not only test the "happy path."

Ask:

* What happens when the record is pending?
* Processing?
* Completed?
* Failed?
* Cancelled?
* Rejected?
* Already processed?

### H. Regression Risk

Could a previous fix have broken another flow?

Could the proposed fix break:

* Existing UI?
* Existing business logic?
* Another user role?
* Another payment flow?
* Admin flow?
* Seller flow?
* Buyer flow?
* Existing database records?

---

# 7. DO NOT STOP AFTER FINDING ONE CAUSE

The following behavior is NOT acceptable:

```text
AI sees bug
→ finds one suspicious line
→ changes one line
→ says "fixed"
```

Instead:

```text
AI sees bug
→ understands symptom
→ reads relevant documentation
→ maps complete execution flow
→ finds possible cause #1
→ verifies whether cause #1 is real
→ searches for other possible causes
→ compares duplicate/parallel flows
→ checks related conditions
→ identifies all relevant root causes
→ plans the smallest complete fix
→ applies fix
→ verifies the result
```

Finding one cause does NOT automatically end the investigation.

Stop investigating only when the relevant execution paths have been sufficiently checked and there is reasonable evidence that no additional related cause needs to be fixed.

Do not perform unnecessary endless exploration of unrelated parts of the project.

Be thorough, but remain relevant to the reported bug.

---

# 8. DISTINGUISH ROOT CAUSE FROM SYMPTOM

Never confuse the visible error with its cause.

Example:

```text
Error:
HTTP 419 Page Expired
```

Possible causes may include:

* Missing CSRF token
* Incorrect CSRF token variable
* Expired session
* Wrong middleware
* Fetch request not sending credentials
* Wrong application/session domain
* Duplicate frontend implementation
* Incorrect layout-provided JavaScript variable

Do not fix the first possible cause without checking whether it explains the actual execution flow.

Another example:

```text
Symptom:
React component does not appear.
```

Do not assume the component itself is broken.

Also check:

* Is the JavaScript bundle loaded?
* Is the entry point imported?
* Does the expected root element exist?
* Does the root ID match?
* Is there a JavaScript runtime error?
* Is the mount condition correct?
* Is the Blade view actually using the expected layout?
* Is Vite serving/building the correct asset?

---

# 9. VERIFY EVERY SUSPECTED CAUSE

Do not call something a root cause merely because it "looks wrong."

Verify it.

Use available evidence such as:

* Code execution flow
* Route resolution
* Logs
* Stack traces
* Database state
* Tests
* Existing patterns in the project
* Comparison with working flows
* Reproduction steps

A suspicious line is not enough.

A professional fix requires evidence that the problem can actually affect the reported behavior.

---

# 10. MAKE THE SMALLEST COMPLETE FIX

After investigation:

Apply the smallest change that completely addresses the verified problem and related causes.

Do NOT:

* Rewrite an entire feature for a small bug
* Refactor unrelated code
* Change working architecture without necessity
* Change UI when the user only asked to fix a bug
* Replace working systems with a new implementation unnecessarily
* Introduce new dependencies without need
* Modify unrelated files just to "clean up" code

However:

Do NOT make the fix artificially too small if the investigation proves multiple related causes exist.

If the bug has multiple verified causes, fix all necessary causes in the same task.

The objective is:

> MINIMAL UNRELATED CHANGE + COMPLETE BUG RESOLUTION

---

# 11. PRESERVE EXISTING WORKING FEATURES

Before modifying code, understand what currently works.

Do not break existing features while fixing another bug.

Pay special attention to:

* Buyer flows
* Seller flows
* Admin flows
* Authentication
* Authorization
* Orders
* Payments
* Wallet balances
* Withdrawals
* Payouts
* Notifications
* Chat
* Service management
* React mounting
* Blade pages
* Existing API/route behavior

When modifying shared logic, inspect all callers before changing its behavior.

---

# 12. HIGH-RISK FINANCIAL AND DATA RULES

For features involving:

* Wallet balance
* Payment
* Withdrawal
* Payout
* Escrow
* Refund
* Order settlement

Treat the bug as HIGH RISK.

Check specifically for:

* Double credit
* Double debit
* Missing debit
* Missing credit
* Duplicate processing
* Idempotency
* Database transactions
* Row locking where necessary
* Partial failure
* Retry behavior
* Webhook duplication
* Status transitions
* Transaction history consistency

Never "fix" a financial bug by changing only the displayed balance without verifying the actual source of truth and transaction records.

---

# 13. TEST BEFORE CLAIMING THE BUG IS FIXED

Never say:

```text
Fixed.
```

unless verification has been performed or the limitation is explicitly stated.

After making changes:

1. Run relevant automated tests if available.
2. Run syntax/static checks where appropriate.
3. Verify the changed execution path.
4. Check related paths that could regress.
5. Check for obvious errors caused by the change.
6. Compare before and after behavior.

For a critical feature, test both:

```text
PRIMARY PATH
```

and:

```text
RELATED / EDGE PATHS
```

Example:

If fixing wallet withdrawal:

```text
Test:
✓ Valid withdrawal
✓ Balance changes correctly
✓ Transaction record is correct
✓ Duplicate request is handled
✓ Rejected withdrawal behavior remains correct
✓ Completed withdrawal is not processed twice
✓ Related admin flow remains correct
```

If a full runtime test cannot be performed, clearly state:

```text
NOT FULLY VERIFIED
```

and explain exactly what was verified and what still requires manual testing.

Never falsely claim complete testing.

---

# 14. BEFORE/AFTER IMPACT CHECK

Before finalizing, ask:

1. What was broken before?
2. What exactly caused it?
3. What files were changed?
4. Why was each file changed?
5. Could another caller be affected?
6. Could another user role be affected?
7. Could existing data be affected?
8. Did the fix introduce duplicate behavior?
9. Did the fix preserve working features?
10. What evidence supports that the bug is fixed?

---

# 15. ERROR.md UPDATE RULE

After a bug investigation:

If the bug is meaningful and likely to help future debugging, add or update an entry in `ERROR.md`.

Document:

```text
Bug ID
Title
Severity
Date
Status
Symptom
Expected Behavior
Actual Behavior
Root Cause
Additional Causes Checked
Affected Files
Fix Applied
Verification
Regression Risk
Prevention / Notes
```

Example:

---

## BUG-004 — Withdrawal Balance Not Decremented

### Severity

HIGH

### Status

FIXED

### Symptom

Withdrawal can be created but the wallet balance does not decrease correctly.

### Root Cause

`withdrawStore()` did not execute the required balance deduction in the relevant execution path.

### Additional Causes Checked

* Admin rejection path
* Payout processing path
* Automatic payout path
* Duplicate processing risk
* Wallet transaction consistency

### Affected Files

* `app/...`
* `app/...`

### Fix Applied

Describe only the actual verified fix.

### Verification

* Relevant test/check performed
* Related flow checked
* Remaining manual test if any

### Prevention

Keep balance updates and transaction history consistent across all payout flows.

---

Do not add trivial temporary errors to `ERROR.md`.

`ERROR.md` is a project debugging knowledge base, not a raw dump of every exception.

---

# 16. REQUIRED FINAL BUG FIX REPORT

After completing the task, provide a concise but complete report containing:

## 1. Bug Summary

What the user reported.

## 2. Investigation

What relevant files and execution paths were checked.

## 3. Root Cause

The verified cause.

## 4. Other Causes Checked

List other possible causes investigated.

Clearly distinguish:

```text
VERIFIED CAUSE
```

from:

```text
CHECKED — NOT THE CAUSE
```

and:

```text
RELATED ISSUE FOUND
```

if applicable.

## 5. Fix Applied

Explain exactly what changed.

## 6. Files Changed

List every changed file and why.

## 7. Verification

State:

* Tests/checks performed
* Results
* Manual testing still required, if any

## 8. Impact

Explain whether existing related functionality was preserved.

## 9. ERROR.md

State whether it was updated and what bug entry was added or changed.

Never hide uncertainty.

Never claim a fix is verified when it was not actually verified.

---

# 17. DEFINITION OF "DONE"

A bug fix is NOT considered complete merely because code was edited.

A bug fix is complete only when:

```text
✓ User's symptom is understood
✓ Relevant documentation was checked
✓ Relevant files were inspected
✓ Complete execution flow was traced
✓ Root cause was verified
✓ Alternative causes were investigated
✓ Related duplicate/parallel flows were checked
✓ All necessary causes found in scope were addressed
✓ Working features were preserved
✓ Relevant verification was performed
✓ Regression risk was considered
✓ ERROR.md was updated when appropriate
✓ Final report clearly explains the result
```

---

# 18. FINAL PRIORITY

When fixing bugs in this project, prioritize:

```text
CORRECTNESS
    ↓
ROOT CAUSE ANALYSIS
    ↓
COMPLETE RELEVANT INVESTIGATION
    ↓
DATA INTEGRITY
    ↓
REGRESSION PREVENTION
    ↓
VERIFICATION
    ↓
MINIMAL UNRELATED CHANGES
    ↓
CODE STYLE / CLEANUP
```

Do not prioritize speed over correctness.

Do not prioritize a quick patch over a complete relevant investigation.

Do not stop after finding the first suspicious line.

Act like a professional engineer responsible for the consequences of the fix.

The objective is to solve the reported bug as completely as reasonably possible in the current task so the user should not need to repeatedly prompt the AI to investigate obvious related causes that should have been checked during the first investigation.
