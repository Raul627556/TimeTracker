<?php

namespace App\DTO;

use DateTime;

class TaskDTO
{

    /** @var int  */
    private int $id;

    /** @var string */
    private string $name;

    /** @var DateTime  */
    private ?DateTime $startTime = null;

    /** @var DateTime  */
    private ?DateTime $endTime = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): TaskDTO
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): TaskDTO
    {
        $this->name = $name;
        return $this;
    }

    public function setStartTime(?DateTime $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function setEndTime(?DateTime $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getStartTime(): ?DateTime
    {
        return $this->startTime;
    }

    public function getEndTime(): ?DateTime
    {
        return $this->endTime;
    }


}