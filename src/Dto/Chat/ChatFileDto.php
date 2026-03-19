<?php

namespace Twix\Dto\Chat;

/**
 * Class ChatFileDto
 *
 * DTO для файла в сообщении чата
 */
class ChatFileDto
{
    /**
     * @var string|null
     */
    private $pathOrigin;

    /**
     * @var string|null
     */
    private $pathPreview;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var int|null
     */
    private $width;

    /**
     * @var int|null
     */
    private $height;

    /**
     * @param array $data
     * @return self|null
     */
    public static function fromArray(?array $data): ?self
    {
        if ($data === null || empty($data)) {
            return null;
        }

        $dto = new self();

        $dto->pathOrigin = $data['path_origin'] ?? null;
        $dto->pathPreview = $data['path_preview'] ?? null;
        $dto->name = $data['name'] ?? null;
        $dto->width = isset($data['width']) ? (int) $data['width'] : null;
        $dto->height = isset($data['height']) ? (int) $data['height'] : null;

        return $dto;
    }

    /**
     * @return array|null
     */
    public function toArray(): ?array
    {
        if (!$this->hasFile()) {
            return null;
        }

        $data = [];

        if ($this->pathOrigin !== null) {
            $data['path_origin'] = $this->pathOrigin;
        }

        if ($this->pathPreview !== null) {
            $data['path_preview'] = $this->pathPreview;
        }

        if ($this->name !== null) {
            $data['name'] = $this->name;
        }

        if ($this->width !== null) {
            $data['width'] = $this->width;
        }

        if ($this->height !== null) {
            $data['height'] = $this->height;
        }

        return $data;
    }

    public function getPathOrigin(): ?string { return $this->pathOrigin; }
    public function getPathPreview(): ?string { return $this->pathPreview; }
    public function getName(): ?string { return $this->name; }
    public function getWidth(): ?int { return $this->width; }
    public function getHeight(): ?int { return $this->height; }
}
