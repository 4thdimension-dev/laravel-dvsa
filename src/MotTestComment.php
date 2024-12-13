<?php

namespace FourthDimension\Dvsa;

class MotTestComment
{
    public string $type;

    public string $text;

    public bool $dangerous;

    public function __construct(array $rawData)
    {
        $rawCollection = collect($rawData);

        $this->type = $rawCollection->get('type');
        $this->text = $rawCollection->get('text');
        $this->dangerous = $rawCollection->get('dangerous');
    }
}
