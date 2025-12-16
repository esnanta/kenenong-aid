<?php
require __DIR__ . '/vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=kenenong_aid', 'root', 'myroot');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== Checking Usuario Tables ===\n\n";

    // Check t_user table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM t_user");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✓ t_user table exists\n";
    echo "  Total users: " . $result['count'] . "\n\n";

    // Check admin user
    $stmt = $pdo->prepare("SELECT id, username, email, confirmed_at FROM t_user WHERE username = ?");
    $stmt->execute(['admin']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        echo "✓ Admin user found!\n";
        echo "  ID: " . $admin['id'] . "\n";
        echo "  Username: " . $admin['username'] . "\n";
        echo "  Email: " . $admin['email'] . "\n";
        echo "  Confirmed: " . ($admin['confirmed_at'] ? 'Yes' : 'No') . "\n\n";
    } else {
        echo "✗ Admin user NOT found\n\n";
    }

    // Check profile table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM t_profile");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✓ t_profile table exists\n";
    echo "  Total profiles: " . $result['count'] . "\n\n";

    // Check auth tables
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM t_auth_item WHERE type = 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✓ t_auth_item table exists\n";
    echo "  Total roles: " . $result['count'] . "\n\n";

    // Check admin role
    $stmt = $pdo->prepare("SELECT * FROM t_auth_item WHERE name = ?");
    $stmt->execute(['admin']);
    $role = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($role) {
        echo "✓ Admin role found!\n";
        echo "  Name: " . $role['name'] . "\n";
        echo "  Type: " . ($role['type'] == 1 ? 'Role' : 'Permission') . "\n";
        echo "  Description: " . ($role['description'] ?? 'N/A') . "\n\n";
    }

    // Check role assignment
    if ($admin) {
        $stmt = $pdo->prepare("SELECT * FROM t_auth_assignment WHERE user_id = ? AND item_name = ?");
        $stmt->execute([$admin['id'], 'admin']);
        $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($assignment) {
            echo "✓ Admin role assigned to admin user!\n\n";
        } else {
            echo "✗ Admin role NOT assigned to admin user\n\n";
        }
    }

    echo "=== All checks completed! ===\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}

