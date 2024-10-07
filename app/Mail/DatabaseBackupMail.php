<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

/**
 * Database Backup Mail
 *
 * @package App\Mail
 */
class DatabaseBackupMail extends BaseMail
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $filePath = $this->data['file_path'];

        $fileName = basename($filePath);
        $mimeType = File::mimeType($filePath);

        return $this->subject(__('notifications.database_backup.subject'))
            ->markdown('mails.database-backup-mail')
            ->attach($filePath, [
                'as'   => $fileName,
                'mime' => $mimeType,
            ]);
    }
}
