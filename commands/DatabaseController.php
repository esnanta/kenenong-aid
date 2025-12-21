<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Controller untuk mengelola database melalui console.
 */
class DatabaseController extends Controller
{
    /**
     * Membuat database jika belum ada.
     * @return int Exit code
     */
    public function actionCreate()
    {
        $db = Yii::$app->db;
        $dsn = $db->dsn;
        
        // Ekstrak nama database dari DSN
        preg_match('/dbname=([^;]+)/', $dsn, $matches);
        $dbName = isset($matches[1]) ? $matches[1] : null;

        if (!$dbName) {
            $this->stderr("Nama database tidak ditemukan di konfigurasi DSN.\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        // Buat koneksi tanpa memilih database
        $tempDsn = preg_replace('/dbname=[^;]+/', '', $dsn);
        try {
            $pdo = new \PDO($tempDsn, $db->username, $db->password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            $this->stdout("Memeriksa database '$dbName'...\n", Console::FG_CYAN);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->stdout("Database '$dbName' berhasil dibuat atau sudah ada.\n", Console::FG_GREEN);
            
            return ExitCode::OK;
        } catch (\PDOException $e) {
            $this->stderr("Gagal membuat database: " . $e->getMessage() . "\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    /**
     * Menghapus database jika ada.
     * @return int Exit code
     */
    public function actionDrop()
    {
        $db = Yii::$app->db;
        $dsn = $db->dsn;
        
        // Ekstrak nama database dari DSN
        preg_match('/dbname=([^;]+)/', $dsn, $matches);
        $dbName = isset($matches[1]) ? $matches[1] : null;

        if (!$dbName) {
            $this->stderr("Nama database tidak ditemukan di konfigurasi DSN.\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        if (!$this->confirm("Apakah Anda yakin ingin menghapus database '$dbName'? SEMUA DATA AKAN HILANG!")) {
            return ExitCode::OK;
        }

        // Buat koneksi tanpa memilih database
        $tempDsn = preg_replace('/dbname=[^;]+/', '', $dsn);
        try {
            // Tutup koneksi DB Yii jika ada untuk menghindari error "database in use" pada beberapa sistem
            $db->close();

            $pdo = new \PDO($tempDsn, $db->username, $db->password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            $this->stdout("Menghapus database '$dbName'...\n", Console::FG_CYAN);
            $pdo->exec("DROP DATABASE IF EXISTS `$dbName` ");
            $this->stdout("Database '$dbName' berhasil dihapus.\n", Console::FG_GREEN);
            
            return ExitCode::OK;
        } catch (\PDOException $e) {
            $this->stderr("Gagal menghapus database: " . $e->getMessage() . "\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    /**
     * Mengeksekusi file sql/kenenong-aid.sql ke database.
     * @return int Exit code
     */
    public function actionImport()
    {
        $sqlFile = Yii::getAlias('@app/sql/kenenong-aid.sql');
        
        if (!file_exists($sqlFile)) {
            $this->stderr("File SQL tidak ditemukan: $sqlFile\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $db = Yii::$app->db;
        $this->stdout("Mengimpor $sqlFile ke database...\n", Console::FG_CYAN);

        try {
            $sql = file_get_contents($sqlFile);
            
            // Eksekusi SQL
            // Catatan: Yii2 pdo->exec() mungkin kesulitan dengan file SQL yang sangat besar atau berisi delimiter khusus.
            // Namun untuk dump standar MySQL biasanya aman.
            $db->createCommand($sql)->execute();
            
            $this->stdout("Impor berhasil.\n", Console::FG_GREEN);
            return ExitCode::OK;
        } catch (\Exception $e) {
            $this->stderr("Gagal mengimpor SQL: " . $e->getMessage() . "\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
