<?php

declare(strict_types=1);

namespace CaptureHigherEd\LaravelJira\Models;

final class Worklog extends Model
{
    /**
     * @param  array<mixed>  $comment
     */
    private function __construct(
        private string $id = '',
        private string $self = '',
        private ?User $author = null,
        private ?User $updateAuthor = null,
        private array $comment = [],
        private string $started = '',
        private string $timeSpent = '',
        private int $timeSpentSeconds = 0,
        private string $issueId = '',
        private string $created = '',
        private string $updated = '',
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function make(array $data = []): self
    {
        return new self(
            id: $data['id'] ?? '',
            self: $data['self'] ?? '',
            author: isset($data['author']) ? User::make($data['author']) : null,
            updateAuthor: isset($data['updateAuthor']) ? User::make($data['updateAuthor']) : null,
            comment: $data['comment'] ?? [],
            started: $data['started'] ?? '',
            timeSpent: $data['timeSpent'] ?? '',
            timeSpentSeconds: (int) ($data['timeSpentSeconds'] ?? 0),
            issueId: $data['issueId'] ?? '',
            created: $data['created'] ?? '',
            updated: $data['updated'] ?? '',
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSelf(): string
    {
        return $this->self;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function getUpdateAuthor(): ?User
    {
        return $this->updateAuthor;
    }

    /**
     * @return array<mixed>
     */
    public function getComment(): array
    {
        return $this->comment;
    }

    public function getStarted(): string
    {
        return $this->started;
    }

    public function getTimeSpent(): string
    {
        return $this->timeSpent;
    }

    public function getTimeSpentSeconds(): int
    {
        return $this->timeSpentSeconds;
    }

    public function getIssueId(): string
    {
        return $this->issueId;
    }

    public function getCreated(): string
    {
        return $this->created;
    }

    public function getUpdated(): string
    {
        return $this->updated;
    }

    public function setId(string $value): self
    {
        $this->id = $value;

        return $this;
    }

    public function setSelf(string $value): self
    {
        $this->self = $value;

        return $this;
    }

    public function setAuthor(?User $value): self
    {
        $this->author = $value;

        return $this;
    }

    public function setUpdateAuthor(?User $value): self
    {
        $this->updateAuthor = $value;

        return $this;
    }

    /**
     * @param  array<mixed>  $value
     */
    public function setComment(array $value): self
    {
        $this->comment = $value;

        return $this;
    }

    public function setStarted(string $value): self
    {
        $this->started = $value;

        return $this;
    }

    public function setTimeSpent(string $value): self
    {
        $this->timeSpent = $value;

        return $this;
    }

    public function setTimeSpentSeconds(int $value): self
    {
        $this->timeSpentSeconds = $value;

        return $this;
    }

    public function setIssueId(string $value): self
    {
        $this->issueId = $value;

        return $this;
    }

    public function setCreated(string $value): self
    {
        $this->created = $value;

        return $this;
    }

    public function setUpdated(string $value): self
    {
        $this->updated = $value;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'self' => $this->self,
            'author' => $this->author?->toArray(),
            'updateAuthor' => $this->updateAuthor?->toArray(),
            'comment' => $this->comment,
            'started' => $this->started,
            'timeSpent' => $this->timeSpent,
            'timeSpentSeconds' => $this->timeSpentSeconds,
            'issueId' => $this->issueId,
            'created' => $this->created,
            'updated' => $this->updated,
        ];
    }
}
