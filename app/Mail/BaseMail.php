<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Mail;

use Illuminate\Mail\Mailable;

/**
 * Base Mail
 *
 * @package \App\Mail
 */
abstract class BaseMail extends Mailable
{
    /**
     * @var string
     */
    public $greeting;

    /**
     * @var array
     */
    public $introLines;

    /**
     * @var string
     */
    public $actionText;

    /**
     * @var string
     */
    public $actionUrl;

    /**
     * @var string
     */
    public $displayableActionUrl;

    /**
     * @var array
     */
    public $outroLines;

    /**
     * @var string
     */
    public $salutation;

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
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    abstract public function build();
}
