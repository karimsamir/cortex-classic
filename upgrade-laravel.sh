#!/bin/bash

# Laravel 12 Upgrade & Local Packages Migration - Bash Wrapper
# This wrapper provides an easy interface to the PHP upgrade script

set -e

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SCRIPT="$PROJECT_ROOT/upgrade-to-laravel12.php"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Check if PHP is available
if ! command -v php &> /dev/null; then
    echo -e "${RED}❌ PHP is not installed or not in PATH${NC}"
    exit 1
fi

# Display usage
usage() {
    cat << EOF
${BLUE}Laravel 12 Upgrade & Local Packages Migration${NC}

Usage: $(basename "$0") [OPTIONS]

Options:
    --dry-run       Run in dry-run mode (no changes will be made)
    --help          Show this help message
    --version       Show PHP version

Example:
    $(basename "$0") --dry-run
    $(basename "$0")

EOF
}

# Parse arguments
DRY_RUN=""

while [[ $# -gt 0 ]]; do
    case $1 in
        --dry-run)
            DRY_RUN="--dry-run"
            shift
            ;;
        --help)
            usage
            exit 0
            ;;
        --version)
            php --version
            exit 0
            ;;
        *)
            echo -e "${RED}Unknown option: $1${NC}"
            usage
            exit 1
            ;;
    esac
done

echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${GREEN}Laravel 12 Upgrade & Local Packages Migration${NC}"
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""

# Display PHP version
echo -e "${BLUE}PHP Version:${NC}"
php --version | head -n 1
echo ""

# Run the PHP script
echo -e "${YELLOW}Starting upgrade process...${NC}"
echo ""

php "$SCRIPT" $DRY_RUN

EXIT_CODE=$?

echo ""
echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

if [ $EXIT_CODE -eq 0 ]; then
    echo -e "${GREEN}✅ Upgrade script completed successfully${NC}"
else
    echo -e "${RED}❌ Upgrade script failed with exit code $EXIT_CODE${NC}"
fi

echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""

exit $EXIT_CODE
