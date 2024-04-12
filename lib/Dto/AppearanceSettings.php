<?php

namespace Pracapl\ZnajdzPraceZPracapl\Dto;

class AppearanceSettings
{
    /** @var ?int $titleFontSize */
    private $titleFontSize = null;

    /** @var ?string $titleFontSize */
    private $titleColor = null;

    /**
     * @param int|string|null $titleFontSize
     * @param string|null $titleColor
     */
    public function __construct($titleFontSize, string $titleColor)
    {
        $this->titleFontSize = (int) $titleFontSize;
        $this->titleColor = $titleColor;
    }

    /**
     * @return ?int
     */
    public function getTitleFontSize(): int
    {
        return $this->titleFontSize;
    }

    /**
     * @return ?string
     */
    public function getTitleColor(): string
    {
        return $this->titleColor;
    }

    public function isTitleFontSizeEmpty(): bool
    {
        return $this->titleFontSize === null || $this->titleFontSize <= 0;
    }

    public function isTitleColorEmpty(): bool
    {
        return $this->titleColor === null || trim($this->titleColor) === '';
    }
}