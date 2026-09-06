# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

**Primary users:**
- Students (sellers): Students with skills who want to offer services and earn money
- Students (buyers): Students who need services for school projects, personal needs
- School administrators: Manage platform, approve services, handle disputes

**Secondary users:**
- Teachers: May use platform to find student services
- School staff: Administrative users

**User situation:**
- Within school environment, trusted community
- Need for safe, verified transactions between students
- Educational context where quality and learning are important

## Product Purpose

SkillHub is a student services marketplace platform where students can offer their skills and services to other students within their school community. It facilitates safe transactions through escrow, builds entrepreneurial skills, and creates a trusted marketplace for student talent.

Success means:
- Students successfully offer and sell services
- Safe transactions with escrow protection
- Positive learning and entrepreneurial experience
- School-approved quality control

## Positioning

**Differentiating mechanism:**
- School-community based marketplace with built-in trust
- Escrow payment system specifically for student transactions
- Service approval process by school administrators
- Integrated within educational environment (not generic marketplace)

**What neighboring products couldn't truthfully copy:**
- Deep integration with school ecosystem and verification
- Educational-first approach to marketplace transactions
- Focus on building student entrepreneurial skills

## Operating Context

**Workflows:**
- Seller creates service → admin approves → buyer orders → escrow holds payment → service delivered → payment released
- Categories and subcategories for service organization
- Chat system for service communication
- Review and rating system

**Tools & environment:**
- Laravel web application
- Tailwind CSS frontend
- MySQL database
- Payment processing integration
- School/student verification systems

**Materials:**
- Student portfolios and service listings
- Service categories (design, coding, writing, etc.)
- Transaction records
- Chat conversations
- Reviews and ratings

## Capabilities and Constraints

**Confirmed functionality:**
- Service creation and management (CRUD)
- Service categories and subcategories
- Order system with escrow payments
- Chat/messaging between buyers and sellers
- Wallet system for sellers
- Admin approval system for services
- Time slot booking system (for time-based services)

**Technical constraints:**
- Laravel PHP framework
- Blade templates + Tailwind CSS
- MySQL database
- Existing user authentication system (Breeze)
- Role-based permissions (seller, buyer, admin)

**Terminology:**
- Jasa = Service
- Seller = Penyedia jasa
- Buyer = Penerima jasa
- Escrow = Sistem penahanan dana

**Undecided product facts:**
- Specific school integration level beyond current implementation
- Expansion to multiple schools (multi-tenant) vs single school focus

## Brand Commitments

**Existing identity:**
- Name: SkillHub
- Logo: exists (SKILLHUB branding)
- Color scheme: Black/white with accent colors (blue, green, red accents observed)
- Typography: Inter font (primary), Plus Jakarta Sans observed in some views
- Voice tone: Professional but student-friendly, Indonesian language primary

**Explicit constraints from user brief:**
- Must feel like "Premium marketplace designed by senior UI/UX designer"
- Cannot look like AI-generated dashboard template
- Cannot change existing PBO/backend system
- Must maintain visual consistency with existing SkillHub pages
- Editorial/clean aesthetic inspired by premium fashion/sports brands (but not literal copy)

## Evidence on Hand

**Real content available:**
- Service listings data (titles, descriptions, prices, categories)
- User profiles (student names, basic info)
- Service images (when provided)
- Existing SkillHub copy and messaging throughout application
- Navigation structure and user flows

**Missing evidence (future work cannot fabricate):**
- School-specific branding beyond SkillHub logo
- Detailed school verification processes
- Real transaction volume data
- Student testimonials beyond existing placeholder content

## Product Principles

1. **Trust through verification:** Every transaction must feel safe within school community context
2. **Educational empowerment:** Platform should build entrepreneurial skills, not just facilitate transactions
3. **Clarity over decoration:** UI must prioritize understanding over visual decoration
4. **Contextual appropriateness:** Design must suit student users while maintaining professionalism
5. **Workflow efficiency:** Sellers need clear management tools without unnecessary complexity

## Accessibility & Inclusion

**Required standards:**
- WCAG compliance for web accessibility
- Screen reader compatibility
- Keyboard navigation support
- Color contrast requirements
- Mobile-responsive design

**Specific user needs:**
- Student users with varying technical proficiency
- Mobile-first usage likely common
- Clear language (Indonesian primary) with minimal jargon
- Intuitive workflows for first-time marketplace users
