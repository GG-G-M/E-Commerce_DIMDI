<?php

// Simple test script to test archive functionality
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "Testing Product Archive Functionality\n";
echo "=====================================\n\n";

// Test 1: Check if routes are properly defined
echo "1. Testing route registration...\n";
try {
    $router = app('router');
    $routes = $router->getRoutes();
    
    $archiveRoute = null;
    $unarchiveRoute = null;
    
    foreach ($routes as $route) {
        if ($route->getName() === 'admin.products.archive') {
            $archiveRoute = $route;
        }
        if ($route->getName() === 'admin.products.unarchive') {
            $unarchiveRoute = $route;
        }
    }
    
    if ($archiveRoute) {
        echo "✓ Archive route found: " . $archiveRoute->getPath() . "\n";
    } else {
        echo "✗ Archive route not found\n";
    }
    
    if ($unarchiveRoute) {
        echo "✓ Unarchive route found: " . $unarchiveRoute->getPath() . "\n";
    } else {
        echo "✗ Unarchive route not found\n";
    }
    
} catch (Exception $e) {
    echo "✗ Route test failed: " . $e->getMessage() . "\n";
}

echo "\n2. Testing Product model...\n";
try {
    $product = new App\Models\Product();
    echo "✓ Product model loaded successfully\n";
    
    // Check if archive/unarchive methods exist
    if (method_exists($product, 'archive')) {
        echo "✓ archive() method exists\n";
    } else {
        echo "✗ archive() method missing\n";
    }
    
    if (method_exists($product, 'unarchive')) {
        echo "✓ unarchive() method exists\n";
    } else {
        echo "✗ unarchive() method missing\n";
    }
    
} catch (Exception $e) {
    echo "✗ Product model test failed: " . $e->getMessage() . "\n";
}

echo "\n3. Testing controller...\n";
try {
    $controller = new App\Http\Controllers\Admin\ProductController();
    echo "✓ ProductController loaded successfully\n";
    
    if (method_exists($controller, 'archive')) {
        echo "✓ archive() method exists in controller\n";
    } else {
        echo "✗ archive() method missing in controller\n";
    }
    
    if (method_exists($controller, 'unarchive')) {
        echo "✓ unarchive() method exists in controller\n";
    } else {
        echo "✗ unarchive() method missing in controller\n";
    }
    
} catch (Exception $e) {
    echo "✗ Controller test failed: " . $e->getMessage() . "\n";
}

echo "\n4. Testing database connection...\n";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=test", "test", "test");
    echo "✓ Database connection test (placeholder)\n";
} catch (Exception $e) {
    echo "! Database test skipped: " . $e->getMessage() . "\n";
}

echo "\nTest completed!\n";
echo "Next steps:\n";
echo "- Check browser console for JavaScript errors\n";
echo "- Check Laravel logs for backend errors\n";
echo "- Verify CSRF token is properly included\n";