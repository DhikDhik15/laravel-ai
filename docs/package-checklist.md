# Packaging Checklist: Laravel AI Workspace

## Goal
Ship this project as a reusable package that can be installed in other Laravel applications with minimal setup.

## Current Progress
- [x] Package service provider with auto-discovery
- [x] Install command (`ai-workspace:install`)
- [x] Publish tags for config, migrations, docs, views
- [x] Idempotent package migrations for `chats` and `messages`
- [x] Configurable route prefix and route name prefix
- [x] Frontend endpoints resolved from server routes (no hardcoded URL)

## Next Milestones
- [ ] Add feature tests for install command options and route prefix mode
- [ ] Add integration tests for send + stream message flow
- [ ] Add provider adapter layer (Gemini/OpenAI/Anthropic)
- [ ] Add package events and extension points
- [ ] Add policy/authorization extension hooks
- [ ] Publish to Packagist and tag first stable release

## Suggested Release Steps
1. Run test suite in a clean host app fixture.
2. Validate install flow on MySQL and SQLite.
3. Tag `v0.1.0` after green tests and migration compatibility checks.
