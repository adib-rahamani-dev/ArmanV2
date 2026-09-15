# REDT ecosystem redesign

## Information architecture

- `/` — focused REDT consultation landing page. One primary conversion: `درخواست مشاوره`.
- `/studio/` — creative services, method, verified-work area, and project inquiry.
- `/digital/` — searchable product/service catalog, filters, comparison, ordering, support, and order tracking.
- `/arman/` — friendly public platform for real events, tickets, posts, announcements, achievements, and community activities.
- `/privacy.php` and `/terms.php` — readable legal foundations. Both require final legal review before commercial launch.

Production can map the branches to `studio.*`, `digital.*`, and `arman.*` by setting the corresponding base URL environment variables and pointing all domains at the same document root.

## Shared system

The `Neo-Corporate Creative Intelligence` layer is implemented in `assets/css/neo-system.css`. It defines semantic light/dark tokens, the shared grid and spacing rhythm, three allowed control/panel radii, typography, focus states, reduced-motion behavior, responsive navigation, and the `REDT Signal System`.

The Signal System uses an architectural grid, numbered nodes, a single controlled red route, directional movement, and a solid impact square. Main, Studio, Digital, and Arman reuse the same logic with surface-specific composition.

## Analytics event map

Events are pushed to `window.dataLayer` and also emitted as `redt:analytics` browser events. No third-party analytics library is loaded by default.

- `consultation_cta_click`
- `consultation_form_start`
- `consultation_form_step`
- `consultation_form_error`
- `consultation_form_submit`
- `consultation_form_abandon`
- `path_studio_click`
- `path_digital_click`
- `theme_change`
- `faq_open`

Connect a consent-aware analytics provider to `dataLayer` only after the privacy policy and measurement plan are approved.

## Data still required

- Confirmed corporate email, phone, city, and social URLs. The public UI does not invent defaults.
- Legal entity details, payment/refund terms, and legal approval of privacy/terms copy.
- Verified Studio case studies: client permission, context, challenge, audience, strategic insight, execution, deliverables, outcomes, feedback, and credits.
- Confirmation that every Digital price, delivery time, activation condition, warranty statement, and course claim in `data/catalog.php` is current and supportable.
- Arman Rajaei biography, portrait policy, factual awards/achievements, articles, social links, and the first real event.
- For each real event: title, status, dates, venue, capacity, eligibility, ticket rules, confirmation delivery, and cancellation policy. Event schema should be added only when this data exists.
- Rules and anti-abuse policy before any prize wheel or game is enabled.

## Deployment notes

Do not index unfinished case-study or event detail routes. Configure `APP_BASE_URL`, branch base URLs, contact variables, production database, admin credentials, auth secret, and encryption key before deployment. The current project intentionally does not deploy itself.
