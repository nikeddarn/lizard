<?php

namespace App\Console\Commands\Backup;

use Illuminate\Console\Command;

class BackupApplication extends Command
{
    const LOCAL_BACKUP_DIR = '/var/www';

    const FTP_SERVER = "backup60.freehost.com.ua";

    const  FTP_USER = "cf1045383";

    const FTP_PASSWORD = "RRn1pAytRJu";

    const REMOTE_BACKUP_DIR = "/backup/www";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup application files';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle()
    {
        $this->deleteOldFilesFromBackupServer();
        $this->putFilesToBackupServer();
    }

    /**
     * Delete old files from backup server.
     */
    private function deleteOldFilesFromBackupServer()
    {
        $command = '$(which lftp) -u ' . self::FTP_USER . ',' . self::FTP_PASSWORD . ' ' . self::FTP_SERVER . ' <<END_SCRIPT

cd ' . self::REMOTE_BACKUP_DIR . '

glob -a rm -r ' . self::REMOTE_BACKUP_DIR . '/*

quit
END_SCRIPT';

        exec($command);
    }

    /**
     * Put new files to backup server.
     */
    private function putFilesToBackupServer()
    {
        $command = '$(which ncftp) -u ' . self::FTP_USER . ' -p ' . self::FTP_PASSWORD . ' ' . self::FTP_SERVER . ' <<END_SCRIPT
        
cd ' . self::REMOTE_BACKUP_DIR . '
lcd ' . self::LOCAL_BACKUP_DIR . '

put -R *

quit
END_SCRIPT';

        exec($command);
    }
}
