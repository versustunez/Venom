<?php


namespace Venom\Entities;


use Venom\Core\Database\Entity;

class DataEntity extends Entity
{
    public const TYPE_CONTENT = 'content';
    public const TYPE_FORM = 'form';

    public int $active = 1;

    public function __construct(
        public string $id,
        public string $type = self::TYPE_CONTENT,
        public string $raw = '',
        public string $generated = ''
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getRaw(): string
    {
        return $this->raw;
    }

    public function setRaw(string $raw): void
    {
        $this->raw = $raw;
    }

    public function getGenerated(): string
    {
        return $this->generated;
    }

    public function setGenerated(string $generated): void
    {
        $this->generated = $generated;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function validate(): bool
    {
        return $this->type !== '' && $this->id !== '' && $this->generated !== '' && $this->raw !== '';
    }

    public function isActive(): bool
    {
        return $this->active === 1;
    }

    public function getActive(): int
    {
        return $this->active;
    }

    public function setActive(bool $value): void
    {
        $this->active = $value ? 1 : 0;
    }
}