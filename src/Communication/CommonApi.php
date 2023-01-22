<?php
namespace App\Communication;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

abstract class CommonApi
{

    private float $latitude;

    private float $longitude;

    protected ContainerBagInterface $containerBag;

    private array $data = array();

    public function __construct(ContainerBagInterface $containerBag)
    {
        $this->containerBag = $containerBag;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    protected function getLatitude(): float
    {
        return $this->latitude;
    }

    protected function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getOptions(): array
    {
        return [];
    }

    public function setLoadData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getLoadData(): array
    {
        return $this->data;
    }

    abstract public function getUrl(array $params = []): string;

    abstract public function isValidData(): bool;
}