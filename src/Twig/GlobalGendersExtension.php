<?php

namespace App\Twig;

use App\Repository\GenderRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalGendersExtension extends AbstractExtension implements GlobalsInterface
{
    private GenderRepository $genderRepository;

    public function __construct(GenderRepository $genderRepository)
    {
        $this->genderRepository = $genderRepository;
    }

    public function getGlobals(): array
    {
        return [
            'genders_global' => $this->genderRepository->findAll(),
        

        ];
    }
}
