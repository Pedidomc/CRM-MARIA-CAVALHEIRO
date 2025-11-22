#!/usr/bin/env bash
set -euo pipefail

# Simple syntax check for all PHP files in the project.
find "$(dirname "$0")/.." -name "*.php" -print -exec php -l {} \;
