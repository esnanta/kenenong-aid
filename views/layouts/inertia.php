<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title inertia>Yii2 - Modern Starter Kit</title>

    <style>
        body {
            margin: 0;
            /* Match your app's background color (e.g., slate-950 or white) */
            background-color: #ffffff;
        }

        #app-loader {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.3s ease;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #29d;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        /* Dark mode support (if using tailwind 'dark' class) */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #020817;
            }

            .spinner {
                border-color: #1e293b;
                border-top-color: #3b82f6;
            }
        }
    </style>


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
            window.$RefreshReg$ = () => {
            };
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
<div id="app-loader">
    <div class="spinner"></div>
</div>

<div id="app" data-page="<?= htmlspecialchars(json_encode($page), ENT_QUOTES, 'UTF-8') ?>"></div>
</body>
</html>