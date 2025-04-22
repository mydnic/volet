<?php

namespace Mydnic\Volet\Features;

interface Feature
{
    public function getId(): string;

    public function getLabel(): string;

    public function setLabel(string $label): static;

    public function getIcon(): ?string;

    public function setIcon(string $icon): static;

    public function isEnabled(): bool;

    public function getConfig(): array;

    public function getComponentName(): ?string;

    public function getScripts(): ?string;
}
