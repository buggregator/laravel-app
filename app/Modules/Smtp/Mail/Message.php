<?php
declare(strict_types=1);

namespace Modules\Smtp\Mail;

class Message implements \JsonSerializable
{
    public function __construct(
        private ?string $id,
        private string  $raw,
        private array   $sender,
        private array   $recipients,
        private array   $ccs,
        private string  $subject,
        private string  $htmlBody,
        private string  $textBody,
        private array $replyTo,
        private array   $allRecipients,
        private array   $attachments
    )
    {
    }

    /**
     * @return array[]
     */
    private function attachmentsToArray(): array
    {
        return array_map(function (Attachment $attachment) {
            return [
                'id' => $attachment->getId(),
                'name' => $attachment->getFilename(),
                'url' => "/api/messages/{$this->id}/attachments/{$attachment->getId()}"
            ];
        }, $this->attachments);
    }

    /**
     * @return string[]
     */
    private function getAttachmentNames(): array
    {
        return array_map(function (Attachment $attachment) {
            return $attachment->getFilename();
        }, $this->attachments);
    }

    /**
     * BCCs are recipients passed as RCPTs but not
     * in the body of the mail.
     * @return string[]
     */
    private function getBccs(): array
    {
        return array_values(array_filter($this->allRecipients, function (string $recipient) {
            foreach (array_merge($this->recipients, $this->ccs) as $publicRecipient) {
                if (strpos($publicRecipient, $recipient) !== false) {
                    return false;
                }
            }
            return true;
        }));
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'from' => $this->sender,
            'reply_to' => $this->replyTo,
            'subject' => $this->subject,
            'to' => $this->recipients,
            'cc' => $this->ccs,
            'bcc' => $this->getBccs(),
            'text' => $this->textBody,
            'html' => $this->htmlBody,
            'raw' => $this->raw,
            'attachments' => $this->attachmentsToArray()
        ];
    }
}
