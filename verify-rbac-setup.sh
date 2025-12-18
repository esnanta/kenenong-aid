#!/bin/bash
# Verification script for RBAC system

echo "🔍 Checking RBAC Setup..."
echo ""

# Check database tables
echo "1. Checking database tables..."
mysql -uroot -pmyroot kenenong-aid -e "SHOW TABLES LIKE 't_auth%';" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ RBAC tables exist"
else
    echo "❌ Database connection failed or tables missing"
fi
echo ""

# Check if admin role exists
echo "2. Checking admin role..."
ADMIN_EXISTS=$(mysql -uroot -pmyroot kenenong-aid -se "SELECT COUNT(*) FROM t_auth_item WHERE name='admin';" 2>/dev/null)
if [ "$ADMIN_EXISTS" = "1" ]; then
    echo "✅ Admin role exists"
else
    echo "❌ Admin role not found"
fi
echo ""

# Check controller syntax
echo "3. Checking controller syntax..."
php -l controllers/RoleController.php > /dev/null 2>&1 && echo "✅ RoleController.php" || echo "❌ RoleController.php has syntax errors"
php -l controllers/PermissionController.php > /dev/null 2>&1 && echo "✅ PermissionController.php" || echo "❌ PermissionController.php has syntax errors"
php -l controllers/RuleController.php > /dev/null 2>&1 && echo "✅ RuleController.php" || echo "❌ RuleController.php has syntax errors"
echo ""

# Check cache directory
echo "4. Checking cache..."
if [ ! "$(ls -A runtime/cache/)" ]; then
    echo "✅ Cache is clear"
else
    echo "⚠️  Cache directory has files (may need clearing)"
fi
echo ""

# Check if React build exists
echo "5. Checking React build..."
if [ -d "web/dist" ] && [ -f "web/dist/.vite/manifest.json" ]; then
    echo "✅ React app built"
else
    echo "⚠️  React app may need rebuilding (npm run build)"
fi
echo ""

# List users for admin assignment
echo "6. Users in database (for admin role assignment):"
mysql -uroot -pmyroot kenenong-aid -e "SELECT id, username, email FROM t_user LIMIT 5;" 2>/dev/null
echo ""

# Check admin assignments
echo "7. Current admin role assignments:"
ADMIN_USERS=$(mysql -uroot -pmyroot kenenong-aid -se "SELECT user_id FROM t_auth_assignment WHERE item_name='admin';" 2>/dev/null)
if [ -z "$ADMIN_USERS" ]; then
    echo "⚠️  No users have admin role assigned yet"
    echo "   Run: mysql -uroot -pmyroot kenenong-aid -e \"INSERT INTO t_auth_assignment (item_name, user_id, created_at) VALUES ('admin', 'YOUR_USER_ID', UNIX_TIMESTAMP());\""
else
    echo "✅ Admin role assigned to user(s): $ADMIN_USERS"
fi
echo ""

echo "📋 Summary:"
echo "   - RBAC tables: Check above"
echo "   - Controllers: Check above"
echo "   - Cache: Check above"
echo "   - Next: Restart PHP server and test"
echo ""
echo "🚀 To start server: php -S localhost:8000 -t web"

