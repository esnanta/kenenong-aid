<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * RbacController is used to initialize RBAC data from SQL file.
 * php yii rbac/init
 */
class RbacController extends Controller
{
    /**
     * Initializes RBAC data from sql/rbac.sql
     * @return int Exit code
     */
    public function actionInit()
    {
        $sqlFile = Yii::getAlias('@app/sql/rbac.sql');

        if (!file_exists($sqlFile)) {
            $this->stdout("SQL file not found: {$sqlFile}\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $sql = file_get_contents($sqlFile);

        if ($sql === false) {
            $this->stdout("Failed to read SQL file: {$sqlFile}\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Split SQL by semicolon, but be careful with potential semicolons inside strings
            // For rbac.sql, it seems safe to split by ;\n or just execute as a single blob if the driver supports it.
            // Yii2's createCommand()->execute() usually doesn't support multiple statements in one call depending on driver.

            // However, a common way to handle this in Yii2 for small scripts is to split.
            // But let's try to execute it as a whole first if possible, or split properly.

            // Clean up the SQL - remove comments and empty lines might help but let's try simple split first
            $statements = explode(';', $sql);

            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    Yii::$app->db->createCommand($statement)->execute();
                }
            }

            $transaction->commit();
            $this->stdout("RBAC data successfully initialized from {$sqlFile}\n", Console::FG_GREEN);
            return ExitCode::OK;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->stdout("Error initializing RBAC data: " . $e->getMessage() . "\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
