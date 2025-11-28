<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title inertia>Yii2 - Modern Starter Kit</title>
    <?php
    // Register CSRF meta tags for Yii2
    $csrfParam = Yii::$app->request->csrfParam;
    $csrfToken = Yii::$app->request->csrfToken;
    if ($csrfParam && $csrfToken) {
        echo '<meta name="csrf-param" content="' . htmlspecialchars($csrfParam, ENT_QUOTES, 'UTF-8') . '">' . "\n    ";
        echo '<meta name="csrf-token" content="' . htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') . '">' . "\n    ";
    }
    ?>
    <?php if (YII_ENV_DEV): ?>
        <!-- Development: Vite dev server -->
        <script type="module" src="http://localhost:5173/@vite/client"></script>
        <script type="module">
            import RefreshRuntime from 'http://localhost:5173/@react-refresh';
            RefreshRuntime.injectIntoGlobalHook(window);
            window.$RefreshReg$ = () => {};
            window.$RefreshSig$ = () => (type) => type;
            window.__vite_plugin_react_preamble_installed__ = true;
        </script>
        <script type="module" src="http://localhost:5173/resources/js/app.jsx"></script>
    <?php else: ?>
        <!-- Production: Built assets -->
        <?php
        $manifestPath = Yii::getAlias('@webroot/dist/.vite/manifest.json');
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            $appEntry = $manifest['resources/js/app.jsx'] ?? null;
            if ($appEntry) {
                if (isset($appEntry['css'])) {
                    foreach ($appEntry['css'] as $css) {
                        echo '<link rel="stylesheet" crossorigin href="/dist/' . $css . '">' . "\n        ";
                    }
                }
                if (isset($appEntry['file'])) {
                    echo '<script type="module" crossorigin src="/dist/' . $appEntry['file'] . '"></script>' . "\n        ";
                }
            }
        }
        ?>
    <?php endif; ?>
</head>
<body>
    <div id="app" data-page="<?= htmlspecialchars(json_encode($page), ENT_QUOTES, 'UTF-8') ?>"></div>
</body>
</html>