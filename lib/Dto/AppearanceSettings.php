<?php

namespace Pracapl\ZnajdzPraceZPracapl\Dto;

class AppearanceSettings
{
    /** @var ?int $titleFontSize */
    private $titleFontSize = null;

    /** @var ?string $titleFontSize */
    private $titleColor = null;

    /** @var ?int $additionalInfoFontSize */
    private $additionalInfoFontSize = null;

    /** @var ?string $additionalInfoFontSize */
    private $additionalInfoColor = null;

    /**
     * @param int|string|null $titleFontSize
     * @param string|null $titleColor
     * @param int|string|null $additionalInfoFontSize
     * @param string|null $additionalInfoColor
     */
    public function __construct($titleFontSize, $titleColor, $additionalInfoFontSize, $additionalInfoColor)
    {
        $this->titleFontSize = (int) $titleFontSize;
        $this->titleColor = $titleColor;
        $this->additionalInfoFontSize = (int) $additionalInfoFontSize;
        $this->additionalInfoColor = $additionalInfoColor;
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

    /**
     * @return ?int
     */
    public function getAdditionalInfoFontSize(): int
    {
        return $this->additionalInfoFontSize;
    }

    /**
     * @return ?string
     */
    public function getAdditionalInfoColor(): string
    {
        return $this->additionalInfoColor;
    }

    public function isAdditionalInfoFontSizeEmpty(): bool
    {
        return $this->additionalInfoFontSize === null || $this->additionalInfoFontSize <= 0;
    }

    public function isAdditionalInfoColorEmpty(): bool
    {
        return $this->additionalInfoColor === null || trim($this->additionalInfoColor) === '';
    }
}