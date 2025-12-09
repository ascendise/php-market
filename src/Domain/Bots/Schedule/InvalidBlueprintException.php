<?php

declare(strict_types=1);

namespace App\Domain\Bots\Schedule;

final class InvalidBlueprintException extends \Exception
{
    public function __construct(
        private readonly BotBlueprint $invalidBlueprint,
        private readonly InvalidBlueprintError $error,
        ?\Throwable $previous = null,
    ) {
        parent::__construct(code: $error->value, previous: $previous);
        $this->message = $this->__toString();
    }

    public function error(): InvalidBlueprintError
    {
        return $this->error;
    }

    public function __toString(): string
    {
        $type = $this->invalidBlueprint->type();
        $args = $this->invalidBlueprint->args();
        $args = json_encode($args);

        return match ($this->error) {
            InvalidBlueprintError::InvalidArgs => "Invalid blueprint arguments for type $type: $args",
            InvalidBlueprintError::UnknownType => "Unknown bot type: $type",
        };
    }
}
