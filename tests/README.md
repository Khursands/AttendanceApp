# AttendanceApp — API Integration Tests (JavaScript)

The AttendanceApp backend is a **headless PHP JSON API** (no browser UI), so
this suite validates it at the HTTP layer with **Mocha**, **Chai** and
**axios** rather than a browser driver.

> Note: browser-based Selenium tooling lives in the sibling Online-Pharmacy
> project, which has an actual UI. For a pure JSON API, contract/integration
> tests are the meaningful QA surface.

## What it covers
- **Health** — every `getAll*.php` read endpoint is reachable and returns a
  JSON envelope.
- **Auth** — `UserLogin.php` rejects empty bodies, malformed payloads, missing
  credentials and unknown users.
- **Users** — validation on `addUser.php` / `getUserbyId.php`, plus an opt-in
  happy-path create.

## Running
```bash
cd tests
cp .env.example .env       # set BASE_URL to your running backend
npm install
npm test                   # read-only + validation checks
RUN_MUTATIONS=1 npm test   # also exercise create paths (writes to DB)
```

Serve the PHP backend locally, e.g.:
```bash
php -S localhost:8000      # from the repo root
```

## Design notes
- The client treats any HTTP status as non-throwing because the API returns a
  `200` JSON error envelope; assertions inspect the body, not just the status.
- Write operations are gated behind `RUN_MUTATIONS=1` so the default run is safe
  against shared databases.
